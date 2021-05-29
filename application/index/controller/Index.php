<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function getList(Request $request) {
    	$param = $request->param();
    	if(!isset($param['page']) || !isset($param['limit'])){
    		exit;
    	}



    	$pages=($param['page'] - 1) * $param['limit'];
    	$where=[];
    	if(!empty($param['search']['key_words'])){
    		$where[]=["key_words",'like','%'.$param['search']['key_words'].'%'];
    	}
    	if(!empty($param['search']['sdate'])){
    		$where[]=["update_time",'>=',$param['search']['sdate'][0]];
    		$where[]=["update_time",'<=',$param['search']['sdate'][1]];
    	}
        $List=Db::table('usa_list')->where($where)->limit($pages,$param['limit'])->order("id","asc")->select();
        $countlist=Db::table('usa_list')->where($where)->count();
        $res=array(
        	'data'=>$List,
        	'totle'=>$countlist,
        );
        return json_encode($res,true);

    }


}
