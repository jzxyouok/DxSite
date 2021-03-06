<?php
/*
 用户接口总集
*/
class User
{
    private $base=null;
    private $permission=null;
    private $history=null;
    private $info=null;
    // 验证登陆
    public function hasSignin()
    {
        if ($info=Common_User::hasSignin()) {
            $this->base=$info;
            return true;
        } else {
            return false;
        }
    }

    // 头像URL
    public function avatar()
    {
        return PageUrl::avatar($this->name);
    }

    // 权限
    public function permission()
    {
        if (is_null($this->permission)) {
            $this->permission=new User_Permission($this->base['uid']);
        }
        return $this->permission;
    }
    // 登陆历史
    public function history()
    {
        if (is_null($this->history)) {
            $this->history=Common_User::getSigninLogs($this->base['uid']);
        }
        return $this->history;
    }

    // 用户信息
    public function info()
    {
        if (is_null($this->info)) {
            $this->info=new Core\Value(Common_User::getInfo($this->base['uid']));
        }
        return $this->info;
    }

    // 获取信息魔术方法
    public function __get(string $name)
    {
        if (isset($this->base[$name])) {
            return $this->base[$name];
        } elseif (method_exists($this, $name)) {
            return $this->{$name}();
        }
        return null;
    }
}
