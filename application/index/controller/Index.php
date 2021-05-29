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
    	$date=new \DateTime();//时间对象
		$date->modify('this week');
		$wek =$date->format('Y-m-d');
		$date->modify('this week +6 days');
		$sund= $date->format('Y-m-d');
		



    	$pages=($param['page'] - 1) * $param['limit'];
    	$where=[];
    	$whereRaw='1=1';
    	if(!empty($param['search']['key_words'])){
    		$where[]=["key_words",'like','%'.$param['search']['key_words'].'%'];
    	}
    	if(!empty($param['search']['sdate'])){
    		$where[]=["update_time",'>=',$param['search']['sdate'][0]];
    		$where[]=["update_time",'<=',$param['search']['sdate'][1]];
    	}else{
    		$where[]=["update_time",'>=',$wek];
    		$where[]=["update_time",'<=',$sund];
    	}
    	if(!empty($param['search']['val_change'])){
    		$where[]=["chang",'>=',$param['search']['val_change']];
    		
    	}
    	if(!empty($param['search']['percentage_change'])){
    		$whereRaw="(chang/l_rank) >=".$param['search']['percentage_change']/100;
    	}
    	if(!empty($param['search']['sdate'])){
    		$where[]=["update_time",'>=',$param['search']['sdate'][0]];
    		$where[]=["update_time",'<=',$param['search']['sdate'][1]];
    	}
        $List=Db::table('ca_list')->where($where)->whereRaw($whereRaw)->limit($pages,$param['limit'])->order("id","asc")->select();
        // dd(Db::table('ca_list')->where($where)->whereRaw($whereRaw)->limit($pages,$param['limit'])->order("id","asc")->getLastSql());
        $countlist=Db::table('ca_list')->where($where)->count();
        $res=array(
        	'data'=>$List,
        	'totle'=>$countlist,
        );
        return json_encode($res,true);

    }


}
