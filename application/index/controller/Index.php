<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
use PHPExcel;
use PHPExcel_IOFactory;
class Index extends Controller
{
	private $tablename='usa_list';
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
//    	if(!empty($param['search']['val_change'])){
//    		$where[]=["chang",'>=',$param['search']['val_change']];
//
//    	}
//    	if(!empty($param['search']['percentage_change'])){
//    		$whereRaw="(chang/l_rank) >=".$param['search']['percentage_change']/100;
//    	}
//    	if(!empty($param['search']['satisfy_p'])){
//    		$where[]=["update_time",'>=',$param['search']['sdate'][0]];
//    		$where[]=["update_time",'<=',$param['search']['sdate'][1]];
//    	}
        $List=Db::table($this->tablename)->where($where)->whereRaw($whereRaw)->limit($pages,$param['limit'])->order("id","asc")->select();
         dd(Db::table('ca_list')->where($where)->whereRaw($whereRaw)->limit($pages,$param['limit'])->order("id","asc")->getLastSql());
        $countlist=Db::table($this->tablename)->where($where)->count();
        if(!empty($List)){
            foreach($List as $key=>$val){
                $where=["key_words",$val['key_words']];
                if(!empty($param['search']['sdate'])){
                    $where[]=["update_time",'>=',$param['search']['sdate'][0]];
                    $where[]=["update_time",'<=',$param['search']['sdate'][1]];
                }else{
                    $where[]=["update_time",'>=',$wek];
                    $where[]=["update_time",'<=',$sund];
                }
                $PicList=Db::table($this->tablename)->where($where)->select();
                foreach ($PicList as $pkey => $pvalue) {
                    if(!empty($param['search']['val_change']) || !empty($param['search']['percentage_change'])){
                        if(!empty($param['search']['satisfy_p'])){

                        }
                    }
                    $List[$key][$val['id']]['update_time'][]=$pvalue['update_time'];
                    $List[$key][$val['id']]['chang'][]=$pvalue['chang'];
                }

            }
        }

        $res=array(
        	'data'=>$List,
        	'totle'=>$countlist,
        );
        return json_encode($res,true);

    }

    public function expExcel(Request $request) {
        $param = $request->param();
        if(!empty($param['ids'])){
            $idArr = explode(',',$param['ids']);
        }else{
            echo '请选择需要导出的数据';exit;
        }
//        $idArr=[590202,590203,590204];
//        foreach ($param['ids'] as $k=> $v){
//            $idArr[]=$v['id'];
//        }
        unset($idArr[count($idArr)-1]);
        $data=Db::table($this->tablename)->whereIn("id",$idArr)->select();
        $path = dirname(__FILE__);//找到当前脚本所在路径
        $PHPExcel = new \PHPExcel();//实例化phpexcel
        $PHPSheet = $PHPExcel->getActiveSheet();
        $PHPSheet->setTitle("demo");//设置表内部名称
        $PHPSheet->setCellValue("A1", "ID")
            ->setCellValue("B1", "关键词")
            ->setCellValue("C1", "本周排名")
            ->setCellValue("D1", "上周排名")
            ->setCellValue("E1", "较上周排名变化")
            ->setCellValue("F1", "更新时间");
        $num=2;
        //数据
        foreach ($data as $k => $v) {
            $PHPSheet->setCellValue("A".$num, (string)$v['id']);
            $PHPSheet->setCellValue("B".$num, (string)$v['key_words']);
            $PHPSheet->setCellValue("C".$num, (string)$v['c_rank']);
            $PHPSheet->setCellValue("D".$num, (string)$v['l_rank']);
            $PHPSheet->setCellValue("E".$num, (string)$v['chang']);
            $PHPSheet->setCellValue("F".$num, (string)$v['update_time']);
            $num++;
        }
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, "Excel2007");//创建生成的格式
        header('Content-Disposition: attachment;filename="amzcount.xlsx"');//下载下来的表格名
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $PHPWriter->save("php://output");//表示在$path路径下面生成demo.xlsx文件
    }


}
