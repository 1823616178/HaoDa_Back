<?php

namespace app\index\controller\Production;

use think\Controller;
use think\Db;
use think\facade\Request;

class LiYanProductBillsController extends Controller
{
    public function GetProductList()
    {
        $page = Request::param('page');
        $Productdata = Db::table('chick_LiuYanDisPath')
            ->field('id')
            ->field('sCoCOde')
            ->field('spec')
            ->field('style')
            ->field('dispathid')
            ->field('productName')
            ->field('width')
            ->field('hangval')
            ->field('CompleteCase')
            ->field('gavegoodsdate')
            ->field('dispathDate')
            ->field('remark')
            ->field('productclaim')
            ->field('Repic')
            ->field('FenQie')
            ->field('groupCode')
            ->limit(($page - 1) * 10, 10)
            ->order('CompleteCase')
            ->select();

        foreach ($Productdata as $ke => $productdatum) {
            $Productdata[$ke]['style'] = json_decode($Productdata[$ke]['style']);
        }
        foreach ($Productdata as $key => $DivStyle) {
            foreach ($DivStyle['style'] as $ass => $bbs) {
                foreach ($bbs as $value => $item) {
                    $arr = [
                        'style' => [
                            'width' => $item . 'px',
                        ]
                    ];
                    $Productdata[$key]['style'][$ass][$value] = $arr;
                }
            }
        }
        $Productdata2 = Db::table('chick_LiuYanDisPath')->field('id')->select();
        $count = count($Productdata2);
        return array('items' => $Productdata, 'total' => $count);
    }


    public function CompeleterSync()
    {
        $query = Request::param();
        $status = $query['status'];
        if ($status == "draft") {
            $style = 1;
        } else {
            $style = 0;
        }
        $id = $query['id'];
        $isSave = Db::table('chick_LiuYanDisPath')->where('id', $id)->update(['CompleteCase' => $style]);
//        if ($style == 1) {
//            $AddTwoTable = Db::table('chick_LiuYanDisPath')->where('id', $id)->find();
//            $AddTwoTableStatus = Db::table('chick_storageTable')->insert(['sCoCOde' => $AddTwoTable['sCoCOde'], 'spec' => $AddTwoTable['spec'], 'style' => $AddTwoTable['style'],
//                'dispathid' => $AddTwoTable['dispathid'], 'productName' => $AddTwoTable['productName'], 'width' => $AddTwoTable['width'],
//                'hangval' => $AddTwoTable['hangval'], 'productclaim' => $AddTwoTable['productclaim'], 'remark' => $AddTwoTable['remark'],
//                'gavegoodsdate' => $AddTwoTable['gavegoodsdate'], 'dispathDate' => $AddTwoTable['dispathDate'], 'CompleteCase' => $AddTwoTable['id']]);
//            return $AddTwoTable;
//        }else{
//            $AddTwoTable = Db::table('chick_LiuYanDisPath')->where('id', $id)->find();
//            $AddTwoTableStatus = Db::table('chick_storageTable')->where('CompleteCase',$AddTwoTable['id'])->delete();
//            return $AddTwoTableStatus;
//        }
        return $isSave;

    }

    public function UpdataComplexTables()
    {
        $query = Request::param();
        $id = $query['id'];
        $arrContest = $query['qu'];
        $status = Db::table('chick_LiuYanDisPath')->where('id', $id)->update(['CompleteCase' => $arrContest['CompleteCase'],
            'dispathDate' => $arrContest['dispathDate'], 'gavegoodsdate' => $arrContest['gavegoodsdate'], 'hangval' => $arrContest['hangval'],
            'productclaim' => $arrContest['productclaim'], 'remark' => $arrContest['remark'], 'Repic' => $arrContest['Repic']]);

        return array('code' => $status);
    }

    public function SearchIdComplexData()
    {
        $query = Request::param();
        $SO_Code = $query['id'];
        $Productdata = Db::table('chick_LiuYanDisPath')->where('sCoCOde', $SO_Code)->select();
        $total = count($Productdata);
        if ($Productdata) {
            foreach ($Productdata as $ke => $productdatum) {
                $Productdata[$ke]['style'] = json_decode($Productdata[$ke]['style']);
            }
            foreach ($Productdata as $key => $DivStyle) {
                foreach ($DivStyle['style'] as $ass => $bbs) {
                    foreach ($bbs as $value => $item) {
                        $arr = [
                            'style' => [
                                'width' => $item . 'px',
                            ]
                        ];
                        $Productdata[$key]['style'][$ass][$value] = $arr;
                    }
                }
            }
            return array('data' => $Productdata, 'total' => $total);
        } else {
            return array('code' => 1002);
        }

    }

    public function DiagSelectSwitchDataList()
    {
        $reques = Request::param();
        $page = intval($reques['page']) - 1;
        $data = Db::table('chick_recipe')->limit($page * 10, 10)->select();
        $data2 = Db::table('chick_recipe')->select();
        $total = count($data2);
        foreach ($data as $key => $datum) {
            if ($datum['isDelete'] == 0) {
                $child = Db::table('chick_recipe_detail')->where('number', $datum['number'])
                    ->select();
                $data[$key]['children'] = $child;
            }
        }
        return array('data' => $data, 'total' => $total);
    }


    public function LiuYanCodeStyle()
    {
        $reques = Request::param();
        $id = $reques['id'];
        $meng = Db::table('chick_LiuYanDisPath')->where('id', $id)->find();
        $data = Db::connect('db_config1')
            ->table('dbo.Inventory')->where('cInvCode', $reques['sCoCOde'])
            ->alias('a')
            ->join(['dbo.ComputationUnit' => 'b'], 'a.cComUnitCode=b.cComUnitCode')
            ->field('a.cInvCode')
            ->field('a.cInvName')
            ->field('a.cInvStd')
            ->field('a.iMassDate ')
            ->field('a.cComUnitCode')
            ->field('b.cComUnitName')
            ->find();
        $data['HangCode'] = $meng['HangCode'];
        $data['width'] = $reques['width'];
        $data['user'] = $reques['user'];
        $data['FenQie'] = $reques['FenQie'];
        $data['groupCode'] = $reques['groupCode'];
        return $data;
    }

    public function innerVisibleClickFunc()
    {
        $data = Db::table('chick_Core')->select();
        return $data;
    }
}