<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Index extends \think\Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function getHaveTas() {
        $locale='en_US.UTF-8';  // 或  $locale='zh_CN.UTF-8';
        setlocale(LC_ALL,$locale);
        putenv('LC_ALL='.$locale);
        $cmd="schtasks /query";
        dd(system($cmd,$re));
    }

    public function openTask() {
//        增加管理员账号密码
//        $cmd="schtasks /create /tn anrainie11-03-23 /tr rec.php /sc MINUTE /mo 10 /ru system /rp password";
        $opath='D:\wamp64\www\amzcount\atuotask\starttask.bat';
        $res=exec($opath);
        echo 1;
    }

    public function closeTask() {
        $cpath='D:\wamp64\www\amzcount\atuotask\disabletask.bat';
        $res=exec($cpath);
        echo 1;
    }

    public function getListData()
    {
        $LogCount=Db::table('log')->count();
        $LogData=Db::table('log')->select();
        $res= array(
            'code'=>0,
            'count'=>$LogCount,
            'data'=>$LogData
        );
        return $res;

    }
}
