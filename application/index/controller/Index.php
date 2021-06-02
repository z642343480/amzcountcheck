<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use PHPExcel;
use PHPExcel_IOFactory;

class Index extends Controller
{
    private $tablename = 'usa_list';

    public function index()
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
        if (isset($param['search']['val_change']) && !empty($param['search']['val_change'])) {
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
        if (isset($param['search']['percentage_change']) && !empty($param['search']['percentage_change'])) {
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
        if (isset($param['search']['satisfy_p'])) {
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
        $where = [];
        $whereRaw = '1=1';      

        $datadate = Db::table($this->tablename)->order("update_time", "desc")->limit(1)->select();
        $where[] = ["update_time", '>=', $datadate[0]['update_time']];

        //$List = Db::table($this->tablename)->where($where)->whereRaw($whereRaw)->limit($pages, $param['limit'])->orderRaw("update_time desc,c_rank asc")->select();
//         dd(Db::table('ca_list')->where($where)->whereRaw($whereRaw)->limit($pages,$param['limit'])->order("id","asc")->getLastSql());
        //$countlist = Db::table($this->tablename)->where($where)->count();
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
         if (!empty($param['search']['key_words'])) {
            $where[] = ["key_words", 'like', '%' . $param['search']['key_words'] . '%'];
        }
        if (!empty($param['search']['sdate'])) {
                    $picwhere[] = ["update_time", '>=', $param['search']['sdate'][0]];
                    $picwhere[] = ["update_time", '<=', $param['search']['sdate'][1]];
                } else {
                     $wherestr=" and update_time>='".$datadate[0]['update_time'];
                }
        $ssatisfy_p=$satisfy_p/100;
        $List=Db::query("
            select * from (
            select * from usa_list u
            where update_time>='".$datadate[0]['update_time']."' and 
            (select count(1) z from usa_list 
            where update_time>='".$datadate[0]['update_time']."' and chang >= ".$val_change." and ".$percentage_change." <= (chang/l_rank) and key_words=u.key_words)
            / 
            (select count(1) from usa_list 
            where update_time>='".$datadate[0]['update_time']."'  and key_words=u.key_words) >=".$ssatisfy_p." ORDER BY update_time desc limit 9999999999) T1 group by key_words ORDER BY update_time desc,c_rank asc limit ".$pages.",".$param['limit']."
            ");
         $Listcount=Db::query("
            select count(1) as num from (
            select * from usa_list u
            where  update_time>='".$datadate[0]['update_time']."' and 
            (select count(1) z from usa_list 
            where update_time>='".$datadate[0]['update_time']."' and chang >= ".$val_change." and ".$percentage_change." <= (chang/l_rank) and key_words=u.key_words)
            / 
            (select count(1) from usa_list 
            where update_time>='".$datadate[0]['update_time']."'  and key_words=u.key_words) >=".$ssatisfy_p." ORDER BY update_time desc limit 9999999999) T1
            ");
    

        $res = array(
            'data' => $this->getPicData($List, $param),
            'totle' => $Listcount[0]['num'],
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

                $PicList = Db::table($this->tablename)->where($picwhere)->select();
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
                    if ($satisfy_p == 100) {
                        if (($pvalue['chang'] >= $val_change) && (((int)$pvalue['chang'] / (int)$pvalue['l_rank']) >= ((int)$percentage_change / 100))) {
                            $List[$key][$val['id']]['update_time'][] = $pvalue['update_time'];
                            $List[$key][$val['id']]['c_rank'][] = $pvalue['c_rank'];
                        } else {
                            $List[$key][$val['id']]['update_time'][] = 'null';
                            $List[$key][$val['id']]['c_rank'][] = 'null';
                        }
                    } else {
                        if (($pvalue['chang'] >= $val_change) && (((int)$pvalue['chang'] / (int)$pvalue['l_rank']) >= ((int)$percentage_change / 100))) {
                            $num++;
                        }
                        if ($pkey == $ArrToa) {
                            $CurNum = count($PicList);
                            $CanNum = ($satisfy_p * $CurNum) / 100;
                            if ($num >= $CanNum) {
                                foreach ($PicList as $pokey => $povalue) {
                                    $List[$key][$val['id']]['update_time'][] = $povalue['update_time'];
                                    $List[$key][$val['id']]['c_rank'][] = $povalue['c_rank'];
                                }
                            } else {
                                $List[$key][$val['id']]['update_time'][] = 'null';
                                $List[$key][$val['id']]['c_rank'][] = 'null';
                            }

                        }
                    }


                }

            }
        }
        //dd($List);
        foreach ($List as $key => $value) {
            //dd($value[$value['id']]);
            foreach ($value[$value['id']] as $k => $v) {
                foreach ($v as $kk => $vv) {
                    if ($vv === 'null') {
                        unset($List[$key]);
                    }
                }

            }
        }
        $List = array_merge($List);
        return $List;
    }

    public function expExcel(Request $request)
    {
        $param = $request->param();
        $this->tablename = $param['cu'] . '_list';
        if (!empty($param['ids'])) {
            $idArr = explode(',', $param['ids']);
        } else {
            echo '请选择需要导出的数据';
            exit;
        }
//        $idArr=[590202,590203,590204];
//        foreach ($param['ids'] as $k=> $v){
//            $idArr[]=$v['id'];
//        }
        unset($idArr[count($idArr) - 1]);
        $List = Db::table($this->tablename)->whereIn("id", $idArr)->order("update_time asc")->select();
        $data = $this->getPicData($List, $param);
        $path = dirname(__FILE__);//找到当前脚本所在路径
        $PHPExcel = new \PHPExcel();//实例化phpexcel
        $PHPSheet = $PHPExcel->getActiveSheet();
        $PHPSheet->setTitle("demo");//设置表内部名称


        $num = 1;
        //dd($data);
        //数据
        $A = ['B', 'C', 'D', 'E', 'F', 'G', 'H'];
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

        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, "Excel2007");//创建生成的格式
        header('Content-Disposition: attachment;filename="' . $this->tablename . '.csv"');//下载下来的表格名
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $PHPWriter->save("php://output");//表示在$path路径下面生成demo.xlsx文件
    }


}
