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

    public function getHaveTas()
    {
        // $locale='GBK';  // 或  $locale='zh_CN.UTF-8';
        // setlocale(LC_ALL,$locale);
        // putenv('LC_ALL='.$locale);
        $cmd = "schtasks /query";
        $aaa = system($cmd, $re);
        dd(gbk_decode(aaa));
        dd(system($cmd, $re));
    }

    public function openTask()
    {
        $prog = Db::table('prog')->where("id", 1)->update(['is_auto' => 1]);

//        增加管理员账号密码
//        $cmd="schtasks /create /tn anrainie11-03-23 /tr rec.php /sc MINUTE /mo 10 /ru system /rp password";
//        $opath='D:\wamp64\www\amzcount\atuotask\starttask.bat';
        $opath = 'D:\wamp64\www\amzcount\atuotask\startsyncdate.bat';
        $res = exec($opath);
        echo 1;
    }

    public function closeTask()
    {
        $prog = Db::table('prog')->where("id", 1)->update(['is_auto' => 0]);
//        $cpath='D:\wamp64\www\amzcount\atuotask\disabletask.bat';
        $cpath = 'D:\wamp64\www\amzcount\atuotask\disablesyncdate.bat';
        $res = exec($cpath);

        echo 1;
    }

    public function hsync()
    {

//        $LogCount=Db::table('log')->where('')->count();
//
//        for($i=0;$i<9;$i++){
//            $cpath='D:\wamp64\www\amzcount\atuotask\usa_run.bat.bat';
//            $res=exec($cpath);
//            $LogCount=Db::table('log')->where('')->count();
//
//        }
    }

    public function getListData()
    {
        $pages = ($_GET['page'] - 1) * $_GET['limit'];
        $where = [];
        if (!empty($_GET['errortime'])) {
            $where[] = ['error_time', '>=', $_GET['errortime']];
        }
        $LogCount = Db::table('log')->where($where)->count();
        $LogData = Db::table('log')->where($where)->limit($pages, $_GET['limit'])->select();
        $res = array(
            'code' => 0,
            'count' => $LogCount,
            'data' => $LogData
        );
        return $res;

    }
}
