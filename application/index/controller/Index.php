<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use PHPExcel;
use PHPExcel_IOFactory;
use think\facade\App;
use think\facade\Env;

class Index extends Controller
{
    private $tablename = 'usa_list';

    public function index()
    {
        return $this->fetch();
    }

    public function tt()
    {
        return $this->fetch();
    }

    public function getList(Request $request)
    {
        $param = $request->param();
        $this->tablename = $param['cu'] . '_list';
        if (!isset($param['page']) || !isset($param['limit'])) {
            exit;
        }
        if (!empty($param['search']['val_change']) && !empty($param['search']['val_change'])) {
            if (!is_numeric($param['search']['val_change'])) {
                $res = array(
                    'data' => [],
                    'totle' => 0,
                    'code' => 0,
                    'message' => '每周增长量必须输入数字'
                );
                return json_encode($res, true);
                exit;

            } else {
                if ($param['search']['val_change'] < 0) {
                    $res = array(
                        'data' => [],
                        'totle' => 0,
                        'code' => 0,
                        'message' => '每周增长量必须是正数'
                    );
                    return json_encode($res, true);
                    exit;
                }
            }
        }
        if (!empty($param['search']['percentage_change']) && !empty($param['search']['percentage_change'])) {
            if (!is_numeric($param['search']['percentage_change'])) {
                $res = array(
                    'data' => [],
                    'totle' => 0,
                    'code' => 0,
                    'message' => '每周增长率必须输入数字'
                );
                return json_encode($res, true);
                exit;

            } else {
                if ($param['search']['percentage_change'] < 0) {
                    $res = array(
                        'data' => [],
                        'totle' => 0,
                        'code' => 0,
                        'message' => '每周增长率必须是正数'
                    );
                    return json_encode($res, true);
                    exit;
                }
            }
        }
        if (!empty($param['search']['satisfy_p'])) {
            if (!is_numeric($param['search']['satisfy_p'])) {
                $res = array(
                    'data' => [],
                    'totle' => 0,
                    'code' => 0,
                    'message' => '达标比例必须输入数字'
                );
                return json_encode($res, true);
                exit;

            } else {
                if ($param['search']['satisfy_p'] > 100) {
                    $res = array(
                        'data' => [],
                        'totle' => 0,
                        'code' => 0,
                        'message' => '达标比例必须小于100%'
                    );
                    return json_encode($res, true);
                    exit;
                }

                if ($param['search']['satisfy_p'] < 0) {
                    $res = array(
                        'data' => [],
                        'totle' => 0,
                        'code' => 0,
                        'message' => '达标比例必须正数'
                    );
                    return json_encode($res, true);
                    exit;
                }
            }
        }

        $date = new \DateTime();//时间对象
        $date->modify('this week');
        $wek = $date->format('Y-m-d');
        $date->modify('this week +6 days');
        $sund = $date->format('Y-m-d');
        $pages = ($param['page'] - 1) * $param['limit'];

        $datadate = Db::table($this->tablename)->order("update_time", "desc")->limit(1)->select();
        $where[] = ["update_time", '>=', $datadate[0]['update_time']];
        $keywhere = '';
        $fsdate = '';
        $fedate = '';
        $zsdate = '';
        $zedate = '';
        $val_change = -10000000000;
        $percentage_change = -1000000000;
        $satisfy_p = -111111100;
        if (!empty($param['search']['val_change'])) {
            $val_change = $param['search']['val_change'];
            $satisfy_p = 100;
        }
        if (!empty($param['search']['percentage_change'])) {
            $percentage_change = $param['search']['percentage_change'];
            $satisfy_p = 100;
        }
        if (!empty($param['search']['key_words'])) {
            $satisfy_p = 100;
             $orderbys=" REPLACE(t1.key_words,'".$param['search']['key_words']."','') ";
            $kwords = explode(' ', $param['search']['key_words']);
            if(count($kwords)==1){
                $keywhere = " and key_words like '%" . $param['search']['key_words'] . "%'";
            }else{
                // $keywhere = " and (key_words like '%" . $param['search']['key_words'] . "%'";
                $keywhere = "and ( 1=1";
                foreach ($kwords as $kwkey=>$kwval){
                    $keywhere .= " and key_words like '% " . $kwval . " %'";
                }
                $keywhere.=') ';
            }

        }else{
             $orderbys=' update_time desc,c_rank asc ';
        }
        $botime = date("Y-m-d", strtotime("-6 month"));
        if (!empty($param['search']['sdate'])) {
            $satisfy_p = 100;
            $fsdate = " update_time >='" . $param['search']['sdate'][0] . "' ";
            $fedate = " and update_time <='" . $param['search']['sdate'][1] . "' ";
            $mini_c_rank = Db::query("select * from ".$this->tablename." where ".$fsdate.$fedate." group by key_words  order by c_rank limit 1");

            $jishu = Db::query("select * from ".$this->tablename." where key_words='".$mini_c_rank[0]['key_words']."' and ".$fsdate.$fedate);
            $jishu= count($jishu)-1;
            $mindata = Db::table($this->tablename)->where('update_time', '>=', $param['search']['sdate'][0])->order("update_time")->limit(1)->select();
            if(empty($mindata)){                
                $res = array(
                    'data' => 0,
                    'totle' => [],
                    'code' => 1
                );
            return json_encode($res, true);
            }
            $nextwe = date('Y-m-d', strtotime("next monday", strtotime($mindata[0]['update_time'])));
            $newfsdate = "  a.update_time >='" . $param['search']['sdate'][0] . "' ";
            $newfedate = " and a.update_time <='" . $param['search']['sdate'][1] . "' ";
            $newfsdate1 = "  update_time >='" . $param['search']['sdate'][0] . "' ";
            $newfedate1 = " and update_time <='" . $param['search']['sdate'][1] . "' ";
            $zsdate = " update_time >='" . $nextwe . "' ";
            $zedate = " and update_time <='" . $param['search']['sdate'][1] . "' ";
            $csdate = " update_time >='" . $param['search']['sdate'][0] . "'";
            $cedate = " and update_time <='" . $param['search']['sdate'][1] . "'";
        } else {
            $fsdate = " update_time >='" . $datadate[0]['update_time'] . "' ";
            $fedate = " and update_time <='" . $sund . "' ";
            $mindata = Db::table($this->tablename)->where('update_time', '>=', $botime)->order("update_time")->limit(1)->select();

            $nextwe = date('Y-m-d', strtotime("next monday", strtotime($mindata[0]['update_time'])));
            $newfsdate = "  1=1 ";
            $newfedate = ' ';
            $newfsdate1 = "  1=1 ";
            $newfedate1 = ' ';
            $zsdate = " update_time >='" . $nextwe . "'";
            $zedate = "  ";
            $csdate = " update_time >='" . $nextwe . "'";
            $cedate = " ";
            $mini_c_rank = Db::query("select * from ".$this->tablename." where c_rank > 0 and update_time>='".$botime."' order by update_time desc,c_rank asc limit 1");

            $jishu = Db::query("select * from ".$this->tablename." where key_words='".$mini_c_rank[0]['key_words']."' and update_time>=".$botime);
            $jishu= count($jishu);
        }
        if (!empty($param['search']['satisfy_p'])) {
            $satisfy_p = $param['search']['satisfy_p'];
        }
        $ssatisfy_p = $satisfy_p / 100;
        $percentage_change = $percentage_change / 100;
        //print_r($jishu);exit;
        $List = Db::query("
            select * from (
            select *
         from " . $this->tablename . " u
            where " . $fsdate . $fedate . $keywhere . " and 
            (select count(1) from (SELECT b.curr,   b.pre,  (b.pre - b.curr) AS diff,b.l_rank,b.key_words FROM  (   SELECT a.`c_rank` AS curr,      @a.DEPOSIT AS pre,      @a.DEPOSIT := a.c_rank,a.l_rank,a.key_words,a.update_time     FROM ".$this->tablename." a,(        SELECT          @a.DEPOSIT := 1         ) r where a.key_words=u.key_words and".$newfsdate.$newfedate.") b limit 1,999999999) c  where  ".$val_change."<=(c.pre - c.curr)  and (".$percentage_change."<=c.pre - c.curr)/c.l_rank) / ".$jishu." >=" . $ssatisfy_p . " and (select count(1) d from " . $this->tablename . " where " . $csdate . $cedate . "  and key_words=u.key_words) >=1  ORDER BY update_time desc limit 9999999999) T1 group by T1.key_words ORDER BY ".$orderbys." limit " . $pages . "," . $param['limit'] . "
            ")  ;
        $Listcount = Db::query("
            select * from (
            select *
         from " . $this->tablename . " u
            where " . $fsdate . $fedate . $keywhere . " and 
            (select count(1) from (SELECT b.curr,   b.pre,  (b.pre - b.curr) AS diff,b.l_rank,b.key_words FROM  (   SELECT a.`c_rank` AS curr,      @a.DEPOSIT AS pre,      @a.DEPOSIT := a.c_rank,a.l_rank,a.key_words,a.update_time     FROM ".$this->tablename." a,(        SELECT          @a.DEPOSIT := 1         ) r where a.key_words=u.key_words and".$newfsdate.$newfedate.") b limit 1,999999999) c  where  ".$val_change."<=(c.pre - c.curr)  and (".$percentage_change."<=c.pre - c.curr)/c.l_rank) / ".$jishu." >=" . $ssatisfy_p . " and (select count(1) d from " . $this->tablename . " where " . $csdate . $cedate . "  and key_words=u.key_words) >=1  ORDER BY update_time desc limit 9999999999) T1 group by T1.key_words");
        $res = array(
            'data' => $this->getPicData($List, $param),
            'totle' => count($Listcount),
            'code' => 1
        );
        return json_encode($res, true);

    }

    public function getPicData($List, $param)
    {
        $botime = date("Y-m-d", strtotime("-6 month"));

        if (!empty($List)) {
            foreach ($List as $key => $val) {
                $picwhere = [["key_words", '=', $val['key_words']]];
                if (!empty($param['search']['sdate'])) {
                    $picwhere[] = ["update_time", '>=', $param['search']['sdate'][0]];
                    $picwhere[] = ["update_time", '<=', $param['search']['sdate'][1]];
                } else {
                    $picwhere[] = ["update_time", '>=', $botime];
                    $picwhere[] = ["update_time", '<=', date("Y-m-d", time())];
                }

                $PicList = Db::table($this->tablename)->where($picwhere)->order("update_time")->select();
                $val_change = -100000;
                $percentage_change = -100000;
                $satisfy_p = 100;
                if (!empty($param['search']['val_change'])) {
                    $val_change = $param['search']['val_change'];
                }
                if (!empty($param['search']['percentage_change'])) {
                    $percentage_change = $param['search']['percentage_change'];
                }
                if (!empty($param['search']['satisfy_p'])) {
                    $satisfy_p = $param['search']['satisfy_p'];
                }
                $ArrToa = count($PicList) - 1;
                $num = 0;
                foreach ($PicList as $pkey => $pvalue) {
                    // if ($satisfy_p == 100) {
                    //if (($pvalue['chang'] >= $val_change) && (((int)$pvalue['chang'] / (int)$pvalue['l_rank']) >= ((int)$percentage_change / 100))) {
                    $List[$key][$val['id']]['update_time'][] = $pvalue['update_time'];
                    $List[$key][$val['id']]['c_rank'][] = $pvalue['c_rank'];
                    //}
                    /** else {
                     * $List[$key][$val['id']]['update_time'][] = 'null';
                     * $List[$key][$val['id']]['c_rank'][] = 'null';
                     * }**/
                    // } else {
                    // if (($pvalue['chang'] >= $val_change) && (((int)$pvalue['chang'] / (int)$pvalue['l_rank']) >= ((int)$percentage_change / 100))) {
                    //     $num++;
                    // }
                    /** if ($pkey == $ArrToa) {
                     * $CurNum = count($PicList);
                     * $CanNum = ($satisfy_p * $CurNum) / 100;
                     * if ($num >= $CanNum) {
                     * foreach ($PicList as $pokey => $povalue) {
                     * $List[$key][$val['id']]['update_time'][] = $povalue['update_time'];
                     * $List[$key][$val['id']]['c_rank'][] = $povalue['c_rank'];
                     * }
                     * } else {
                     * $List[$key][$val['id']]['update_time'][] = 'null';
                     * $List[$key][$val['id']]['c_rank'][] = 'null';
                     * }
                     *
                     * }**/
                    // }


                }

            }
        }
        //dd($List);
        // foreach ($List as $key => $value) {
        //     //dd($value[$value['id']]);
        //     foreach ($value[$value['id']] as $k => $v) {
        //         foreach ($v as $kk => $vv) {
        //             if ($vv === 'null') {
        //                 unset($List[$key]);
        //             }
        //         }

        //     }
        // }


        $List = array_merge($List);
        return $List;
    }

    public function expExcel(Request $request)
    {
        set_time_limit(0);

        $param = $request->param();
        $this->tablename = $param['cu'] . '_list';
        if ($param['all'] != 1) {
            if (!empty($param['ids'])) {
                $idArr = explode(',', $param['ids']);
                unset($idArr[count($idArr) - 1]);
            } else {
                echo '请选择需要导出的数据';
                exit;
            }
        }

//        $idArr=[590202,590203,590204];
//        foreach ($param['ids'] as $k=> $v){
//            $idArr[]=$v['id'];
//        }

        if ($param['all'] == 1) {
            $date = new \DateTime();//时间对象
            $date->modify('this week');
            $wek = $date->format('Y-m-d');
            $date->modify('this week +6 days');
            $sund = $date->format('Y-m-d');

            $datadate = Db::table($this->tablename)->order("update_time", "desc")->limit(1)->select();
            $where[] = ["update_time", '>=', $datadate[0]['update_time']];
            $keywhere = '';
            $fsdate = '';
            $fedate = '';
            $zsdate = '';
            $zedate = '';
            $val_change = -100000;
            $percentage_change = -100000;
            $satisfy_p = -111111100;
            if (!empty($param['val_change']) && $param['val_change'] != 'undefined') {
                $val_change = $param['val_change'];
                $satisfy_p =100;
            }
            if (!empty($param['percentage_change']) && $param['percentage_change'] != 'undefined') {
                $percentage_change = $param['percentage_change'];
                $satisfy_p =100;
            }
            if (!empty($param['key_words']) && $param['key_words'] != 'undefined') {
                $satisfy_p =100;
                $orderbys=" REPLACE(t1.key_words,'".$param['key_words']."','') ";
                $kwords = explode(' ', $param['key_words']);
                if(count($kwords)==1){
                    $keywhere = " and key_words like '%" . $param['key_words'] . "%'";
                }else{
                    // $keywhere = " and (key_words like '%" . $param['key_words'] . "%'";
                    $keywhere = "and ( 1=1";
                    foreach ($kwords as $kwkey=>$kwval){
                        $keywhere .= " and key_words like '% " . $kwval . " %'";
                    }
                    $keywhere.=') ';
                }
            }else{
                $orderbys=' update_time desc,c_rank asc ';
            }
            $botime = date("Y-m-d", strtotime("-6 month"));
            if (!empty($param['sdate']) && $param['sdate'] != 'undefined') {
                $satisfy_p =100;
                $fsdate = " update_time >='" . $param['sdate'] . "' ";
                $fedate = " and update_time <='" . $param['edate'] . "' ";
                $mini_c_rank = Db::query("select * from ".$this->tablename." where ".$fsdate.$fedate." group by key_words  order by c_rank limit 1");

                $jishu = Db::query("select * from ".$this->tablename." where key_words='".$mini_c_rank[0]['key_words']."' and ".$fsdate.$fedate);
                $jishu= count($jishu)-1;
                $mindata = Db::table($this->tablename)->where('update_time', '>=', $param['sdate'])->order("update_time")->limit(1)->select();
                $nextwe = date('Y-m-d', strtotime("next monday", strtotime($mindata[0]['update_time'])));
                $newfsdate = " a.update_time >='" . $param['sdate'] . "' ";
                $newfedate = " and a.update_time <='" . $param['edate'] . "' ";
                $newfsdate1 = " update_time >='" . $param['sdate'] . "' ";
                $newfedate1 = " and update_time <='" . $param['edate'] . "' ";
                $zsdate = " update_time >='" . $nextwe . "' ";
                $zedate = " and update_time <='" . $param['edate'] . "' ";
                $csdate = " update_time >='" . $param['sdate'] . "'";
                $cedate = " and update_time <='" . $param['edate'] . "'";
            } else {
                $fsdate = " update_time >='" . $datadate[0]['update_time'] . "' ";
                $fedate = " and update_time <='" . $sund . "' ";
                $mindata = Db::table($this->tablename)->where('update_time', '>=', $botime)->order("update_time")->limit(1)->select();
                $newfsdate1 = "  1=1 ";
                $newfedate1 = ' ';
                $nextwe = date('Y-m-d', strtotime("next monday", strtotime($mindata[0]['update_time'])));
                $newfsdate = ' 1=1';
                $newfedate =' ';
                $zsdate = " update_time >='" . $nextwe . "'";
                $zedate = "  ";
                $csdate = " update_time >='" . $nextwe . "'";
                $cedate = " ";
                $mini_c_rank = Db::query("select * from ".$this->tablename." where c_rank > 0 and update_time>='".$botime."' order by update_time desc,c_rank asc limit 1");

                $jishu = Db::query("select * from ".$this->tablename." where key_words='".$mini_c_rank[0]['key_words']."' and update_time>=".$botime);
                $jishu= count($jishu);
                }

            if (!empty($param['satisfy_p']) && $param['satisfy_p'] != 'undefined') {
                $satisfy_p = $param['satisfy_p'];
            }
            $ssatisfy_p = $satisfy_p / 100;
            $percentage_change = $percentage_change / 100;
            $List = Db::query("
            select * from (
            select * from " . $this->tablename . " u
            where " . $fsdate . $fedate . $keywhere . " and 
            (select count(1) from (SELECT b.curr,   b.pre,  (b.pre - b.curr) AS diff,b.l_rank,b.key_words FROM  (   SELECT a.`c_rank` AS curr,      @a.DEPOSIT AS pre,      @a.DEPOSIT := a.c_rank,a.l_rank,a.key_words     FROM ".$this->tablename." a,(        SELECT          @a.DEPOSIT := 1         ) r where a.key_words=u.key_words and".$newfsdate.$newfedate.") b limit 1,999999999) c  where  ".$val_change."<=(c.pre - c.curr)  and (".$percentage_change."<=c.pre - c.curr)/c.l_rank)
            / ".$jishu." >=" . $ssatisfy_p . " and (select count(1) d from " . $this->tablename . " where " . $csdate . $cedate . "  and key_words=u.key_words) >=1  ORDER BY update_time desc limit 9999999999) T1 group by key_words ORDER BY ".$orderbys." 
            ");
        } else {
            $List = Db::table($this->tablename)->whereIn("id", $idArr)->order("update_time asc")->select();

        }
        // $data = $this->getPicData($List, $param);
                if (!empty($List)) {
                            $datadate = Db::table($this->tablename)->order("update_time", "asc")->limit(1)->select();
        $botime = date("Y-m-d", strtotime("-6 month"));
        $onekey='';
            foreach ($List as $key => $val) {
            
                $picwhere = [["key_words", '=', $val['key_words']]];
                if ($param['sdate']!='undefined') {
                    $picwhere[] = ["update_time", '>=', $param['sdate']];
                    $picwhere[] = ["update_time", '<=', $param['edate']];
                    $datewhere[] = ["update_time", '>=', $param['sdate']];
                    $datewhere[] = ["update_time", '<=', $param['edate']];
                } else {
                    $picwhere[] = ["update_time", '>=', $botime];
                    $picwhere[] = ["update_time", '<=', date("Y-m-d", time())];
                    // $datewhere[] =["update_time", '>=', $botime];
                    // $datewhere[] =["update_time", '<=', date("Y-m-d", time())];
                }
                $PicList = Db::table($this->tablename)->where($picwhere)->order("update_time")->select();
                
                
                foreach ($PicList as $pkey => $pvalue) {
                    if($pkey==0 && $onekey ==''){
                        $onekey=$pvalue['update_time'];
                    }
                     if($pkey==0){
                            if($onekey!='' && $pvalue['update_time'] != $onekey){
                                $sec1=strtotime($pvalue['update_time'])-strtotime($onekey);
                                $ii1=intval($sec1/604800);
                                for($d=1;$d<$ii1;$d++){
                                    $List[$key][$val['id']]['update_time'][] = date("Y-m-d",strtotime($pvalue['update_time'])+(604800*($d)));
                                    $List[$key][$val['id']]['c_rank'][] = 'null';
                                }
                            }

                        }
                    $List[$key][$val['id']]['update_time'][] = $pvalue['update_time'];
                    $List[$key][$val['id']]['c_rank'][] = $pvalue['c_rank'];

                     if($pkey!=count($PicList)-1){
                        if(strtotime($PicList[$pkey+1]['update_time'])-strtotime($pvalue['update_time'])>777600){
                            $sec=strtotime($PicList[$pkey+1]['update_time'])-strtotime($pvalue['update_time']);
                                $ii=intval($sec/604800);
                                for($d=1;$d<$ii;$d++){
                                    $List[$key][$val['id']]['update_time'][] = date("Y-m-d",strtotime($pvalue['update_time'])+(604800*($d)));
                                    $List[$key][$val['id']]['c_rank'][] = 'null';
                                }
                            
                        }
                    }
                }

            }
        }

        // exit;
        // $List = array_merge($List);
        $data=$List;
        $path = dirname(__FILE__);//找到当前脚本所在路径
        $PHPExcel = new \PHPExcel();//实例化phpexcel
        $PHPSheet = $PHPExcel->getActiveSheet();
        $PHPSheet->setTitle("amzcount");//设置表内部名称
        $PHPSheet->getColumnDimension('A')->setWidth(30);#设置单元格宽度
        $PHPSheet->getColumnDimension('B')->setWidth(15);
        $PHPSheet->getColumnDimension('C')->setWidth(15);
        $PHPSheet->getColumnDimension('D')->setWidth(15);
        $PHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $num = 1;
        //dd($data);
        //数据
        $A = ['B', 'C', 'D', 'E', 'F', 'G', 'H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        foreach ($data as $k => $v) {
            $i = ($k + 1);
            $ia = $i + 1;
            $PHPSheet->setCellValue("A" . $ia, (string)$v['key_words']);
            if ($k == 0) {
                foreach ($v[$v['id']]['update_time'] as $key => $value) {
                    $PHPSheet->setCellValue($A[$key] . $i, (string)$value);
                }
            }

            foreach ($v[$v['id']]['c_rank'] as $key => $value) {
                $PHPSheet->setCellValue($A[$key] . $ia, (string)$value);
            }


        }
        if ($param['type'] == 'excel') {
            $datetimef=time();
            $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, "Excel5");//创建生成的格式
            // header('Content-Disposition: attachment;filename="' . $this->tablename . date('Y-m-d') . '.xls"');//下载下来的表格名
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // $PHPWriter->save("php://output");//表示在$path路径下面生成demo.xlsx文件
            $PHPWriter->save(Env::get('root_path')."public/exc/". $this->tablename.date('Y-m-d').$datetimef.".xls");//表示在$path路径下面生成demo.xlsx文件
            return "/exc/". $this->tablename.date('Y-m-d').$datetimef.".xls";
            exit;
        }
        if ($param['type'] == 'csv') {
            $datetimef=time();
            $objWriter = new  \PHPExcel_Writer_CSV ($PHPExcel);
            // header('Content-Disposition: attachment;filename="' . $this->tablename . date('Y-m-d') . '.csv"');
            $objWriter->save(Env::get('root_path')."public/exc/". $this->tablename.date('Y-m-d').$datetimef.".csv");
            return "/exc/". $this->tablename.date('Y-m-d').$datetimef.".csv";
            exit;
        }


    }


}
