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
       // $locale='GBK';  // 或  $locale='zh_CN.UTF-8';
       // setlocale(LC_ALL,$locale);
       // putenv('LC_ALL='.$locale);
        $cmd="schtasks /query";
        $aaa=system($cmd,$re);
        dd(gbk_decode(aaa));
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
        $pages=($_GET['page'] - 1) * $_GET['limit'];
        $where=[];
        if(!empty($_GET['errortime'])){
            $where[]=['error_time','>=',$_GET['errortime']];
        }
        $LogCount=Db::table('log')->where($where)->count();
        $LogData=Db::table('log')->where($where)->limit($pages,$_GET['limit'])->select();
        $res= array(
            'code'=>0,
            'count'=>$LogCount,
            'data'=>$LogData
        );
        return $res;

    }
}
