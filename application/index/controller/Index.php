<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function getList() {
        $UsaList=Db::table('usa_list')->limit(11)->select();
        return json_encode($UsaList,true);

    }


}
