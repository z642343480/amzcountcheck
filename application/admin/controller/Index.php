deny from all<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Index extends \think\Controller
{
    public function index()
    {
        return $this->fetch();
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
