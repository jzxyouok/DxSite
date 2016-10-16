<?php
class UserView
{
    public function main($uid)
    {
       Page::use('/user/user');
       $info=DB_User::getPublicInfo((int)$uid);
       $info['avatar_url']=PageUrl::Avatar($info['uid']);
       $user=new Core\Value($info);
       Page::set('user',$user);
       Page::set('title',$user->name.' - 的主页');
    }
}
