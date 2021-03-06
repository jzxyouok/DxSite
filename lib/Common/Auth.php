<?php

class Common_Auth
{
    public $uid=0;
    public function __construct(int $uid=0)
    {
        self::setUid($uid);
    }
    public function setUid(int $uid)
    {
        $this->uid=$uid;
        return $this;
    }
    /**
    * 使用别人的名义
    */
    public function su2Other(int $uid=-1) :bool
    {
        if ($uid===-1) {
            $uid=$this->uid;
        }
        $cmd='SELECT `U_su` FROM `atd_users` JOIN`atd_groups` ON `atd_groups`.`gid`=`atd_users`.`gid` WHERE `atd_users`.`uid`= :uid LIMIT 1;';
        if ($q=(new Query($cmd, ['uid'=>$uid]))->fetch()) {
            return $q['U_su']==='Y';
        }
        return false;
    }
    public function editCategory(int $uid=-1) :bool
    {
        if ($uid===-1) {
            $uid=$this->uid;
        }
        $cmd='SELECT `E_category` FROM `atd_users` JOIN`atd_groups` ON `atd_groups`.`gid`=`atd_users`.`gid` WHERE `atd_users`.`uid`= :uid LIMIT 1;';
        if ($q=(new Query($cmd, ['uid'=>$uid]))->fetch()) {
            return $q['E_category']==='Y';
        }
        return false;
    }

    
    public function groupInfo(int $gid) :bool
    {
        $cmd='SELECT * FROM `atd_groups`.`gid`= :gid  LIMIT 1;';
        if ($set=(new Query($cmd, ['git'=>$uid]))->fetch()) {
            return $set;
        }
        return false;
    }
    
}
