<?php
// TODO: 是否限制IP注册
class Common_User
{
    public static $order_str=['uid','uname','gid','signup'];

    public static function userExist(string $user):bool
    {
        return self::user2Id($user)!==0;
    }
    public static function user2Id(string $user):int
    {
        $q=new Query('SELECT uid FROM #{users} where LOWER(uname) = LOWER(:uname) LIMIT 1;');
        $q->values(['uname'=>$user]);
        if ($get=$q->fetch()) {
            return $get['uid'];
        }
        return 0;
    }
    
    public static function emailExist(string $email):bool
    {
        $q=new Query('SELECT uid FROM #{users} where LOWER(email) = LOWER(:email) LIMIT 1;');
        $q->values(['email'=>$email]);
        if ($get=$q->fetch()) {
            return true;
        }
        return false;
    }
    public static function createVerify(int $uid):string
    {
        // 猜猜是啥
        static $mis='5246-687261-5852-6C';
        $q='UPDATE `#{users}` SET `verify` = :verify , `expriation`=:time  WHERE `#{users}`.`uid` = :uid;';
        $verify=md5('DXCore-'.CORE_VERSION.'-'.$uid.'-'.time().'-'.$mis);
        if ((new Query($q, ['verify'=>$verify, 'time'=>time(), 'uid'=>$uid]))->exec()) {
            return $verify;
        }
        return '';
    }
    public static function verify(int $uid, string $hash, int $expriaton=0)
    {
        return (new Query('UPDATE `#{users}` SET `email_verify` =\'Y\' WHERE `uid`=:uid AND `verify` = :hash AND `expriation` > :expriation LIMIT 1;', ['uid'=>$uid, 'hash'=>$hash, 'expriation'=>$expriaton]))->exec();
    }
    public static function signUp(string $user, string $passwd, string $email):int
    {
        $token=md5(Request::ip().time());
        if (($q=new Query('INSERT INTO #{users} (`uname`,`upass`,`email`,`signup`,`lastip`,`token`) VALUES ( :uname, :passwd, :email, :signup ,:lastip , :token );'))->values([
            'uname'=>$user,
            'passwd'=>password_hash($passwd, PASSWORD_DEFAULT),
            'email'=>$email,
            'signup'=>time(),
            'lastip'=>Request::ip(),
            'token'=>$token,
        ])->exec()) {
            $uid=$q->lastInsertId();
            self::setDefaulInfo($uid,0,'Ta很懒，神马都没留下');
            // 登陆日志记录
            (new Query('INSERT INTO `#{signin_historys}` (`uid`,`ip`,`time`) VALUES (:uid,:ip,:time)'))->values([
                 'uid'=>$uid,
                'ip'=>Request::ip(),
                'time'=>time(),
            ])->exec();
            Session::regenerate(true);
            // 设置登陆状态
            Session::set('signin', true);
            // 登陆信息
            Session::set('user_id', $uid);
            // 登陆状态保留（只能临时用~~)
            Session::set('token', $token.$uid);
            //信息缓存
            Cache::set('user:'.$uid, $user);
            Cache::set('uid:'.$user, $uid);
            return $uid;
        }
        return 0;
    }

    
    public function sendMail($uid)
    {
        if ($info=Common_User::getBaseInfo($uid)) {
            $return=($mail=new Mail())
                ->from('user-center@atd3.cn', '用户中心')
                ->to($info['email'], $info['uname'])
                ->subject('DxCore 邮箱验证')
                ->use('mail')
                ->send([
                    'title'=>'来至 DxCore 的验证邮箱',
                    'site_name'=>'DxCore',
                    'message'=>'欢迎注册DxCore账号！',
                    'user'=>$info['uname'],
                    'verify'=>PageUrl::verifyMailUrl($uid, Common_User::createVerify($uid)),
                    'hosturl'=>'//atd3.cn',
                    'hostname'=>'atd3.cn',
                ]);
            return $return;
        }
    }

    public static function signIn(string $name, string $passwd, string $keep):int
    {
        $token=md5(Request::ip().time());
        if ($get=(new Query('SELECT `upass`,`uid` FROM #{users} WHERE LOWER(uname)=LOWER(:uname)LIMIT 1;'))->values(['uname'=>$name])->fetch()) {
            //信息缓存
            Cache::set('user:'.$get['uid'], $name);
            Cache::set('uid:'.$name, $get['uid']);
            if (password_verify($passwd, $get['upass'])) {
                if ((new Query('UPDATE `#{users}` set signin=:signin,lastip:=:lastip,token=:token where uid=:uid LIMIT 1;'))->values([
                    'uid'=>$get['uid'],
                    'signin'=>time(),
                    'lastip'=>Request::ip(),
                    'token'=>$token,
                ])->exec()) {
                    // 登陆日志记录
                    (new Query('INSERT INTO `#{signin_historys}` (`uid`,`ip`,`time`) VALUES (:uid,:ip,:time)'))->values([
                        'uid'=>$get['uid'],
                        'ip'=>Request::ip(),
                        'time'=>time(),
                    ])->exec();
                    Session::regenerate(true);
                    // 设置登陆状态
                    Session::set('signin', true);
                    // 登陆信息
                    Session::set('user_id', $get['uid']);
                    Session::set('token', $token.$get['uid']);
                    if ($keep) {
                        // 登陆状态保留
                        Cookie::set('token', $token.$get['uid'], 2592000)->httpOnly();
                    }
                    return 0;
                }
                return 3;// system error
            } else {
                return 2; // passwd error
            }
        } else {
            return 1; // no user
        }
    }
    public static function getSigninLogs(int $uid) :array
    {
        if ($history = (new Query('SELECT `ip`,`time`  FROM `#{signin_historys}` WHERE `uid` = :uid  ORDER BY `time` DESC LIMIT 5; '))
        -> values(
            ['uid'=>$uid]
        )->fetchAll()) {
            return $history;
        }
        return [];
    }

    public static function hasSignin()
    {
        if ($get=self::getLastUserInfo()) {
            // 设置登陆状态
            Session::set('signin', true);
            // 登陆信息
            Session::set('user_id', $get['uid']);
            return $get;
        }
        return false;
    }

    public static function getLastUserInfo()
    {
        static $info=null;
        if ($info) {
            return $info;
        }
        $token=Cookie::has('token')?Cookie::get('token'):(Session::has('token')?Session::get('token'):'');
        preg_match('/^([a-zA-z0-9]{0,32})(\d+)$/', $token, $match);
        if (count($match)>0 && $last=(new Query('SELECT `uid`,`lastip`,`uname` as `name`,`signup`,`email`,`email_verify` FROM `#{users}` WHERE uid=:uid AND token=:token LIMIT 1;'))
            ->values([
                    'token'=>$match[1],
                    'uid'=>$match[2]
                ])->fetch()) {
            $info=$last;
            return $last;
        }
        return false;
    }
    
    public static function signOut()
    {
        $uid=Session::get('user_id');
        (new Query('UPDATE `#{users}` SET `token` = \'\' WHERE `#{users}`.`uid` = :uid ;'))->values(['uid'=>$uid])->exec();
        // 设置登陆状态
        Session::set('signin', false);
        Cookie::unset('token');
        Session::destroy();
    }

    public static function count():int
    {
        $q='SELECT `TABLE_ROWS` as `size` FROM `information_schema`.`TABLES` WHERE  `TABLE_SCHEMA`="'.conf('Database.dbname').'" AND `TABLE_NAME` ="#{users}" LIMIT 1;';
        if ($a=($d=new Query($q))->fetch()) {
            return $a['size'];
        }
        return 0;
    }
    
    public static function setAvatar(int $uid, int $avatar)
    {
        $q='UPDATE `#{user_info}` SET `avatar` = :avatar WHERE `#{user_info}`.`uid` = :uid;';
        return (new Query($q, ['uid'=>$uid, 'avatar'=>$avatar]))->exec();
    }
    public static function getPublicInfo(int $uid)
    {
        static $info=null;
        if (isset($info[$uid])) {
            return $info[$uid];
        } elseif ($info[$uid]=(new Query('SELECT `#{users}`.`uid`,`uname` as `name`,`avatar`,`signup`,`discription` FROM `#{users}`  LEFT JOIN `#{user_info}` ON  `#{user_info}`.`uid` = `#{users}`.`uid` WHERE `#{users}`.`uid`=:uid  LIMIT 1;', ['uid'=>$uid]))->fetch()) {
            return $info[$uid];
        }
        return $info[$uid];
    }

    public static function getPublicInfoByName(string $name)
    {
        static $info=null;
        if (isset($info[$name])) {
            return $info[$name];
        } elseif ($info[$name]=(new Query('SELECT `#{users}`.`uid`,`uname` as `name`,`avatar`,`signup`,`discription` FROM `#{users}`  JOIN `#{user_info}` ON  `#{user_info}`.`uid` = `#{users}`.`uid` WHERE `#{users}`.`uname`=:name  LIMIT 1;', ['name'=>$name]))->fetch()) {
            return $info[$name];
        }
        return $info;
    }
    public static function getInfo(int $uid)
    {
        $q='SELECT * FROM `#{user_info}` WHERE `uid` = :uid LIMIT 1;';
        return (new Query($q, ['uid'=>$uid]))->fetch();
    }
    public static function getBaseInfo(int $uid)
    {
        $q='SELECT * FROM `#{users}` WHERE `uid` = :uid LIMIT 1;';
        return (new Query($q, ['uid'=>$uid]))->fetch();
    }
    public static function setDefaulInfo(int $uid, int $avatar, string $discription):bool
    {
        $q='INSERT INTO `#{user_info}` (`uid`, `avatar`,`discription`) VALUES (:uid,:avatar,:discription);';
        return (new Query($q, ['uid'=>$uid, 'avatar'=>$avatar, 'discription'=>$discription]))->exec();
    }

    public static function emailVerified(int $uid)
    {
        $q='SELECT `email_verify` FROM `#{users}` WHERE `uid` =:uid LIMIT 1;';
        if ($get=(new Query($q, ['uid'=>$uid]))->fetch()) {
            return $get['email_verify'] == 'Y';
        }
        return false;
    }
    public static function id2group($uid)
    {
        $q='SELECT `gid` FROM `#{users}` WHERE `uid` = :uid LIMIT 1;';
        if ($sets=(new Query($q, ['uid'=>$uid]))->fetch()) {
            return $sets['gid'];
        }
        return 0;
    }

    public static function gid2name(int $gid)
    {
        $q='SELECT `gname` FROM `#{permission}` WHERE `gid` = :gid LIMIT 1;';
        if ($sets=(new Query($q, ['gid'=>$gid]))->fetch()) {
            return $sets['gname'];
        }
        return null;
    }
    
    public static function getGroups()
    {
         $q='SELECT `gid`,`gname` FROM `#{permission}` WHERE `uid` = 0 LIMIT 1;';
        if ($sets=(new Query($q))->fetchAll()) {
            return $sets;
        }
        return null;
    }

    public static function modify(int $uid,string $name,int $gid,string $email,string $verify,int $status)
    {
        $sql='UPDATE `#{users}` SET `uname`=:name,`gid`=:gid,`email`=:email,`email_verify`=:verify,`status`=:status WHERE `uid` = :uid';
        return (new Query($sql,['uid'=>$uid,'name'=>$name,'email'=>$email,'verify'=>$verify,'status'=>$status,'gid'=>$gid]))->exec();
    }
    public static function delete(int $uid)
    {
        $sql='DELETE FROM `#{users}` WHERE `#{users}`.`uid` = :uid LIMIT 1;';
        return (new Query($sql,['uid'=>$uid]))->exec();
    }
    public static function changePasswd(int $uid,string $passwd)
    {
        $sql='UPDATE `#{users}` SET `upass`=:passwd  AND token=\'\' WHERE uid=:uid LIMIT 1;';
        return (new Query($sql,['uid'=>$uid,'passwd'=>password_hash($passwd, PASSWORD_DEFAULT)]))->exec();
    }
    public static function setStatu(int $uid,int $statu=1)
    {
        $sql='UPDATE `#{users}` SET `status` = :statu WHERE `#{users}`.`uid` = :uid;';
        return (new Query($sql,['statu'=>$statu,'uid'=>$uid]))->exec();
    }
    public static function listUser(int $page,int $count=20)
    {
        $sql='SELECT `uid`,`uname` as `name`,`gid`,`signup`,`status`,`email`,`email_verify`, `lastip` FROM `#{users}` LIMIT :offset,:count;';
        return (new Query($sql,['offset'=>$page*$count,'count'=>$count]))->fetchAll();
    }
}
