<?php

namespace app\admin\controller;

use think\Db;
use QL\QueryList;
use GuzzleHttp\Exception\RequestException;

use GuzzleHttp\Client;

class Log
{
    private $is_auto = 1;
    private $opset=0;
    private $zcount=0;

    public function index()
    {
        return 1;
    }

    public function getlong()
    {
        $prog = Db::table('prog')->select();
        echo json_encode($prog, true);
    }

    public function stopt()
    {
        $prog = Db::table('prog')->where("id", 1)->update(['is_stop' => 1]);
    }

    public function hsync()
    {
        ini_set('memory_limit','256M');
        set_time_limit(0);

        if (!empty($_GET['type'])) {
            $this->is_auto = 0;
        }
        $is_exc = Db::table('prog')->select();
        if ($is_exc[0]['is_exc'] == 1) {
            echo 2;
            exit;
        }else{
            Db::table('prog')->where("id", 1)->update(['is_exc' => 1]);
        }
        Db::table('prog')->where("id", 1)->update(['progress' => 0,'is_stop' => 0]);
        // ob_end_clean();
        // ob_implicit_flush(1);
        $prog = Db::table('prog')->select();
        if ($prog[0]['is_stop'] == 1) {
            $prog = Db::table('prog')->where("id", 1)->update(['is_exc' => 0]);
            return 1;
        }
         Db::table('prog')->where("id", 1)->update(['tablename_sync' => '美国']);
        $this->usa();
//         Db::table('prog')->where("id", 1)->update(['progress' => 10]);

        //echo 10;

        $prog = Db::table('prog')->select();
        if ($prog[0]['is_stop'] == 1) {
            Db::table('prog')->where("id", 1)->update(['is_exc' => 0]);
            return 1;
        }
         Db::table('prog')->where("id", 1)->update(['tablename_sync' => '英国']);
        $this->uk();
//         Db::table('prog')->where("id", 1)->update(['progress' => 22]);

        //echo 22;

        $prog = Db::table('prog')->select();
        if ($prog[0]['is_stop'] == 1) {
             Db::table('prog')->where("id", 1)->update(['is_exc' => 0]);
            return 1;
        }
         Db::table('prog')->where("id", 1)->update(['tablename_sync' => '德国']);
        $this->de();

//         Db::table('prog')->where("id", 1)->update(['progress' => 33]);
        //echo 33;


        $prog = Db::table('prog')->select();
        if ($prog[0]['is_stop'] == 1) {
            Db::table('prog')->where("id", 1)->update(['is_exc' => 0]);
            return 1;
        }
         Db::table('prog')->where("id", 1)->update(['tablename_sync' => '日本']);
        $this->jp();

//         Db::table('prog')->where("id", 1)->update(['progress' => 44]);
        //echo 44;


        $prog = Db::table('prog')->select();
        if ($prog[0]['is_stop'] == 1) {
             Db::table('prog')->where("id", 1)->update(['is_exc' => 0]);
            return 1;
        }
         Db::table('prog')->where("id", 1)->update(['tablename_sync' => '西班牙']);
        $this->esp();

//         Db::table('prog')->where("id", 1)->update(['progress' => 55]);
        //echo 55;


        $prog = Db::table('prog')->select();
        if ($prog[0]['is_stop'] == 1) {
            Db::table('prog')->where("id", 1)->update(['is_exc' => 0]);
            return 1;
        }
        Db::table('prog')->where("id", 1)->update(['tablename_sync' => '意大利']);

        $this->it();

//        Db::table('prog')->where("id", 1)->update(['progress' => 66]);
        //echo 66;


        $prog = Db::table('prog')->select();
        if ($prog[0]['is_stop'] == 1) {
             Db::table('prog')->where("id", 1)->update(['is_exc' => 0]);
            return 1;
        }
         Db::table('prog')->where("id", 1)->update(['tablename_sync' => '墨西哥']);
        $this->mx();

//         Db::table('prog')->where("id", 1)->update(['progress' => 77]);
        //echo 77;

        $prog = Db::table('prog')->select();
        if ($prog[0]['is_stop'] == 1) {
             Db::table('prog')->where("id", 1)->update(['is_exc' => 0]);
            return 1;
        }
        Db::table('prog')->where("id", 1)->update(['tablename_sync' => '加拿大']);
        $this->ca();

//         Db::table('prog')->where("id", 1)->update(['progress' => 88]);
        //echo 88;


        $prog = Db::table('prog')->select();
        if ($prog[0]['is_stop'] == 1) {
            Db::table('prog')->where("id", 1)->update(['is_exc' => 0]);
            return 1;
        }
         Db::table('prog')->where("id", 1)->update(['tablename_sync' => '法国']);

        $this->fr();

//         Db::table('prog')->where("id", 1)->update(['progress' => 100]);
        //echo 100;
         Db::table('prog')->where("id", 1)->update(['is_exc' => 0]);


    }

    //    美国
    public function usa()
    {
        $area = 'usa';
        $this->getAmzList($area);
    }

    //    英国
    public function uk()
    {
        $area = 'uk';
        $this->getAmzList($area);
    }

    //    德国
    public function de()
    {
        $area = 'de';
        $this->getAmzList($area);
    }

    //    日本
    public function jp()
    {
        $area = 'jp';
        $this->getAmzList($area);
    }

    //    西班牙
    public function esp()
    {
        $area = 'esp';
        $this->getAmzList($area);
    }

    //    意大利
    public function it()
    {
        $area = 'it';
        $this->getAmzList($area);
    }

    //    法国
    public function fr()
    {
        $area = 'fr';
        $this->getAmzList($area);
    }

    //    墨西哥
    public function mx()
    {
        $area = 'mx';
        $this->getAmzList($area);
    }

    //    加拿大
    public function ca()
    {
        $area = 'ca';
        $this->getAmzList($area);
    }

    public function getAmzList($area)
    {
        $datacount = 0;
        set_time_limit(0);
        $table_update_time = '';
        $error_data = array();
        $error_e = '';
        $is_error = '';
        $page = ['1_1000', '1001_10000', '10001_50000', '50000'];
        Db::startTrans();
        foreach ($page as $dokey => $doval) {
            if ($doval == '1_1000') {
                $forcount = 2;
            } else {
                $forcount = 17;
            }
            for ($i = 1; $i <= $forcount; $i++) {
                $prog = Db::table('prog')->select();
                if ($prog[0]['is_stop'] == 1) {
                    return false;
                }
                try {
                    $ql = QueryList::get('https://www.amz123.com/' . $area . 'topkeywords-' . $i . '-1-.htm?rank=' . $doval . '&uprank=');
                } catch (RequestException $e) {
                    $error_e = $e;
                    $is_error = "get_error";
                    break 2;
                }
                $TempData = array();
                $kw = array();
                $rg = array();
                $kw = $ql->find('.listdata>.keywords')->texts()->all();
                $rg = $ql->find('.listdata>.rank')->texts()->all();
//                $count = count($kw);//总条数
                foreach ($kw as $kk => $vk) {
                    $TempData[$kk][] = $vk;
                    $c = $kk + 1;
                    $start = ($c - 1) * 3;
                    $rgarr = array_slice($rg, $start, 3);
                    foreach ($rgarr as $rk => $rv) {
                        $TempData[$kk][] = $rv;
                    }
                }
                $UpdateHtml = $ql->find('.banner-form>div')->texts();
                $UpdateText = $UpdateHtml->take(-1)->all();
                $update_time = substr($UpdateText[2], -16);
                $update_time = substr($update_time, 0, strlen($update_time) - 6);
                $is_existence = 0;
                if ($table_update_time == '') {
                    $is_existence = Db::table($area . '_list')->where('update_time', $update_time)->count();
                    $table_update_time = $update_time;
                }
                if ($is_existence) {
                    $is_error = 'isex';
                    break 2;
                }
                foreach ($TempData as $key => $val) {
                    $data = array_merge($val);
                    foreach ($data as $k1 => $v1) {
                        if ($k1 == 0) {
                            $data['key_words'] = ' '.trim($v1).' ';
                        }
                        if ($k1 == 1) {
                            $data['c_rank'] = $v1;
                        }
                        if ($k1 == 2) {
                            $data['l_rank'] = $v1;
                        }
                        $data['chang'] = null;
                        $data['update_time'] = $update_time;
                        $data['is_auto'] = $this->is_auto;
                        unset($data[$k1]);
                    }
                    $TempData[$key] = $data;

                }
                foreach ($TempData as $key => $val) {
                   // $TempData[$key]['key_words'] = trim($val['key_words']);
                    foreach ($val as $k1 => $v1) {
                        if ($k1 == 'chang') {
                            $TempData[$key][$k1] = ((int)$val['l_rank']) - ((int)$val['c_rank']);
                        }

                    }
                }

                try {
                    $IsSuccess = Db::table($area . '_list')->insertAll($TempData);
                    $datacount += $IsSuccess;
                    $this->zcount += $IsSuccess;
                    $prd = Db::table('prog')->select();
                    $dprd=(float)$prd[0]['progress'] + 0.21;
                    $prog = Db::table('prog')->where("id", 1)->update(['count' => $this->zcount,"progress" => $dprd]);
                    //echo $datacount.',';
                } catch (\Exception $e) {
                    $error_e = $e;
                    $is_error = "insert_error";
                    $error_data = $TempData;
                    break 2;
                }
                sleep(1);
            }
        }
        if ($is_error == '') {
            Db::commit();
            $logData = ['error_code' => 200, 'error_time' => date("Y-m-d H:i:s"), 'o_type' => $this->is_auto, 'remark' => '任务执行成功', 'is_success' => 1, 'data_count' => $datacount, 'area' => $area];
            $IsSuccess = Db::table('log')->insert($logData);
        } else {
            if ($is_error == 'insert_error') {
                Db::rollback();
                $prd1 = Db::table('prog')->select();
                $this->zcount += 0;
                $prog = Db::table('prog')->where("id", 1)->update(['count' => $this->zcount,'progress'=>(float)$prd1[0]['progress']+11.2]);
                $logData = ['error_code' => 10000, 'error_time' => date("Y-m-d H:i:s"), 'o_type' => $this->is_auto, 'remark' => '任务执行失败' . $error_e->getMessage(), 'is_success' => 0, 'data_count' => $datacount, 'area' => $area];
                $IsSuccess = Db::table('log')->insert($logData);

            }else if ($is_error == 'get_error') {
                Db::rollback();
                $prd1 = Db::table('prog')->select();
                $this->zcount += 0;
                $prog = Db::table('prog')->where("id", 1)->update(['count' => $this->zcount,'progress'=>(float)$prd1[0]['progress']+11.2]);
                    $error_code = $e->getCode();
                    $error_remark = $e->getMessage();
                    $ErrorData = ['error_code' => $error_code, 'error_time' => date("Y-m-d H:i:s"), 'o_type' => $this->is_auto, 'remark' => $error_remark, 'is_success' => 0, 'data_count' => $datacount, 'area' => $area];
                    $IsSuccess = Db::table('log')->insert($ErrorData);


            }else if($is_error == 'isex'){
                Db::rollback();
                $prd1 = Db::table('prog')->select();
                $this->zcount += 0;
                $prog = Db::table('prog')->where("id", 1)->update(['count' => $this->zcount,'progress'=>(float)$prd1[0]['progress']+11.2]);
            }else{
                Db::rollback();
                $prd1 = Db::table('prog')->select();
                $this->zcount += 0;
                $prog = Db::table('prog')->where("id", 1)->update(['count' => $this->zcount,'progress'=>(float)$prd1[0]['progress']+11.2]);
                $logData = ['error_code' => 10000, 'error_time' => date("Y-m-d H:i:s"), 'o_type' => $this->is_auto, 'remark' => '任务执行失败,原因未知', 'is_success' => 0, 'data_count' => $datacount, 'area' => $area];
                $IsSuccess = Db::table('log')->insert($logData);
            }
        }

    }

}
