<?php

namespace app\index\controller\OrderInfomation;

use app\index\model\OrderInfoModel;
use think\Db;
use think\facade\Request;

class OrderInfoController
{
    public function GetOrderInfo()
    {

        $query = Request::param();
        $page = intval($query['pag']) - 1;
        if (!empty($query)) {
            $data2 = Db::connect('db_config1')->table('dbo.SO_SOMain')->alias('a')
                ->field('a.ID')
                ->select();
            $total = count($data2);
            $data = Db::connect('db_config1')->table('dbo.SO_SOMain')->alias('a')
                ->field('a.cSOCode')
                ->field('a.ID')
                ->field('a.cCusCode')
                ->field('a.dDate')
                ->field('a.cPersonCode')
                ->field('a.iTaxRate')
                ->field('a.cDefine1')
                ->field('a.cCusName')
                ->field('a.ccusperson')
                ->field('a.dmoddate')
                ->field('a.dmodifysystime')
                ->field('a.cmodifier')
                ->field('a.dclosesystime')
                ->field('a.dclosedate')
                ->field('a.cMemo')
                ->field('a.cCloser')
                ->limit($page * 10, 20)
                ->select();

            foreach ($data as $key => $datum) {
                $arr = Db::connect('db_config1')->table('dbo.SO_SODetails')
                    ->alias('a')
                    ->join(["dbo.Inventory" => 'b'], 'a.cInvCode=b.cInvCode')
                    ->leftJoin(['dbo.SO_SODetails_extradefine' => 'c'], 'a.iSOsID=c.iSOsID')
                    ->field('a.cInvCode')
                    ->field('a.cInvName')
                    ->field('b.cInvCCode')
                    ->field('c.cbdefine3')
                    ->field('b.cInvStd')
                    ->field('c.cbdefine4')
                    ->field('a.iQuantity')
                    ->field('a.cDefine34')
                    ->field('a.dPreDate')
                    ->field('a.dbclosedate')
                    ->field('a.cMemo')
                    ->where('a.cSOCode', $datum['cSOCode'])
                    ->select();
                $data[$key]['children'] = $arr;
            }
            return array('data' => $data, 'total' => $total, 'query' => $page);
        }

//                $data2 = OrderInfoModel::field('cSOCode')
//                    ->field('ID')
//                    ->select();
//                $total = count($data2);
//                foreach ($data as $key => $datum) {
//                    $func = Db::connect('db_config1')
//                        ->table('SO_SODetails')
//                        ->where('cSOCode', $datum['cSOCode'])
//                        ->field('iQuantity')
//                        ->field("dPreDate")
//                        ->field("cDefine34")
//                        ->field("cInvCode")
//                        ->field("dreleasedate")
//                        ->field("cSCloser")
//                        ->field("dbclosesystime")
//                        ->field("dbclosedate")
//                        ->select();
//                    $data[$key]['children'] = $func;
//                }
//                return array('data' => $data, 'total' => $total, 'query' => $query);
//            }
//            $data = OrderInfoModel::field('cSOCode')
//                ->field('ID')
//                ->field('cCusCode')
//                ->field('dDate')
//                ->field('cPersonCode')
//                ->field('iTaxRate')
//                ->field('cDefine1')
//                ->field('cCusName')
//                ->field('ccusperson')
//                ->field('dmoddate')
//                ->field('dmodifysystime')
//                ->field('cmodifier')
//                ->field('dclosesystime')
//                ->field('dclosedate')
//                ->field('cCloser')
//                ->limit($query['pag'] * 10, 10)
//                ->select();
//            $data2 = OrderInfoModel::field('cSOCode')
//                ->field('ID')
//                ->select();
//            $total = count($data2);
//            foreach ($data as $key => $datum) {
//                $func = Db::connect('db_config1')
//                    ->table('SO_SODetails')
//                    ->where('cSOCode', $datum['cSOCode'])
//                    ->field('iQuantity')
//                    ->field("dPreDate")
//                    ->field("cDefine34")
//                    ->field("cInvCode")
//                    ->field("dreleasedate")
//                    ->field("cSCloser")
//                    ->field("dbclosesystime")
//                    ->field("dbclosedate")
//                    ->select();
//                $data[$key]['children'] = $func;
//            }
//            return array('data' => $data, 'total' => $total, 'query' => $query);
//        } else {
//            $data = OrderInfoModel::field('cSOCode')
//                ->field('ID')
//                ->field('cCusCode')
//                ->field('dDate')
//                ->field('cPersonCode')
//                ->field('iTaxRate')
//                ->field('cDefine1')
//                ->field('cCusName')
//                ->field('ccusperson')
//                ->field('dmoddate')
//                ->field('dmodifysystime')
//                ->field('cmodifier')
//                ->field('dclosesystime')
//                ->field('dclosedate')
//                ->field('cCloser')
//                ->limit(10)
//                ->select();
//            $data2 = OrderInfoModel::field('cSOCode')
//                ->field('ID')
//                ->select();
//            $total = count($data2);
//            foreach ($data as $key => $datum) {
//                $func = Db::connect('db_config1')
//                    ->table('SO_SODetails')
//                    ->where('cSOCode', $datum['cSOCode'])
//                    ->field('iQuantity')
//                    ->field("dPreDate")
//                    ->field("cDefine34")
//                    ->field("cInvCode")
//                    ->field("dreleasedate")
//                    ->field("cSCloser")
//                    ->field("dbclosesystime")
//                    ->field("dbclosedate")
//                    ->select();
//                $data[$key]['children'] = $func;
//            }
//            return array('data' => $data, 'total' => $total);
//        }
    }

    public function SearchGetOrderInfo()
    {
        $query = Request::param();
        $page = $query['cSOCode'];
        $data = Db::connect('db_config1')->table('dbo.SO_SOMain')->alias('a')
            ->field('a.cSOCode')
            ->field('a.ID')
            ->field('a.cCusCode')
            ->field('a.dDate')
            ->field('a.cPersonCode')
            ->field('a.iTaxRate')
            ->field('a.cDefine1')
            ->field('a.cCusName')
            ->field('a.ccusperson')
            ->field('a.dmoddate')
            ->field('a.dmodifysystime')
            ->field('a.cmodifier')
            ->field('a.dclosesystime')
            ->field('a.dclosedate')
            ->field('a.cMemo')
            ->field('a.cCloser')
            ->where('cSOCode',$page)
            ->select();

        foreach ($data as $key => $datum) {
            $arr = Db::connect('db_config1')->table('dbo.SO_SODetails')
                ->alias('a')
                ->join(["dbo.Inventory" => 'b'], 'a.cInvCode=b.cInvCode')
                ->leftJoin(['dbo.SO_SODetails_extradefine' => 'c'], 'a.iSOsID=c.iSOsID')
                ->field('a.cInvCode')
                ->field('a.cInvName')
                ->field('b.cInvCCode')
                ->field('c.cbdefine3')
                ->field('b.cInvStd')
                ->field('c.cbdefine4')
                ->field('a.iQuantity')
                ->field('a.cDefine34')
                ->field('a.dPreDate')
                ->field('a.dbclosedate')
                ->field('a.cMemo')
                ->where('a.cSOCode', $datum['cSOCode'])
                ->select();
            $data[$key]['children'] = $arr;
        }
        return array('data' => $data, 'query' => $page);
    }

    public function GetIdPageInfo()
    {
        $id = Request::param();
        $data = Db::connect('db_config1')
            ->table('quertDetailInfo')
            ->where('cSOCode', $id['orderId'])
            ->field('cSOCode')
            ->field('ID')
            ->field('cInvCode')
            ->field('dPreDate')
            ->field('iQuantity')
            ->field('iSOsID')
            ->field('AutoID')
            ->field('cInvName')
            ->field('iTaxRate')
            ->field('cDefine34')
            ->field('dPreMoDate')
            ->field('dreleasedate')
            ->field('cSCloser')
            ->field('dbclosesystime')
            ->field('dbclosedate')
            ->find();
        $pageTable = $this->GetPageTableListData($data['iSOsID']);
        return array('data' => $data, 'table' => $pageTable);
    }

    public function GetPageTableListData($id)
    {
        $data = Db::connect('db_config1')
            ->table('SO_SODetails_extradefine')
            ->where('iSOsID', $id)
            ->field('iSOsID')
            ->field('cbdefine3')
            ->field("cbdefine4")
            ->find();
        return $data;
    }
}