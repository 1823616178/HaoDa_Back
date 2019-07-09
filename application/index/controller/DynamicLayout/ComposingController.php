<?php

namespace app\index\controller\DynamicLayout;

use app\index\model\SOSODetailsModel;
use function PHPSTORM_META\type;
use think\Controller;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\facade\Request;

class ComposingController extends Controller
{
    public function SellOrder()
    {
        $page = Request::param('page');
        if (empty($page)) {
            $sql = "SELECT Inventory.cInvCode ,Inventory.cInvName,SO_SODetails.cSOCode,cInvStd As cInvStdtd  ,SUM(iQuantity) AS Allup
FROM SO_SODetails 
LEFT JOIN SO_SODetails_extradefine ON SO_SODetails_extradefine.iSOsID = SO_SODetails.iSOsID
LEFT JOIN Inventory ON Inventory.cInvCode = SO_SODetails.cInvCode
LEFT JOIN SO_SOMain ON SO_SOMain.ID = SO_SODetails.ID
LEFT JOIN Customer ON Customer.cCusCode = SO_SOMain.cCusCode
WHERE left(SO_SODetails.cInvCode,2)='01'
GROUP BY Inventory.cInvCode,Inventory.cInvName,cInvStd,SO_SODetails.cSOCode";

            $data = Db::connect('db_config1')->query($sql);
            $arr = [];
            $arr2 = [];
            for ($n = 0; $n < 5; $n++) {
                $arr[$n] = $data[$n];
            }
            foreach ($arr as $key => $value) {
                $id = $value['cSOCode'];
                $sqlDetail = "SELECT ceiling(iQuantity/(CAST(cInvStd AS INTEGER)*0.000001*CAST(cDefine34 AS INTEGER)*0.001*900*CAST(fLength AS INTEGER))) AS 'pice',
CEILING(iQuantity/(CAST(cInvStd AS INTEGER)*0.000001*CAST(cDefine34 AS INTEGER)*0.001*900)) AS 'rice',
cbdefine3 ,
Inventory.cInvCode AS cInvCodeSS,SO_SODetails.cInvName as  cInvNameName,cInvStd,fLength ,
iQuantity,cDefine34 ,dDate ,dPreDate ,
Customer.cCusCode,cCusAbbName ,SO_SODetails.cSOCode
FROM SO_SODetails 
LEFT JOIN SO_SODetails_extradefine ON SO_SODetails_extradefine.iSOsID = SO_SODetails.iSOsID
LEFT JOIN Inventory ON Inventory.cInvCode = SO_SODetails.cInvCode
LEFT JOIN SO_SOMain ON SO_SOMain.ID = SO_SODetails.ID
LEFT JOIN Customer ON Customer.cCusCode = SO_SOMain.cCusCode
WHERE SO_SODetails.cSOCode=" . "'" . $id . "'" . "AND left(SO_SODetails.cInvCode,2)='01'
ORDER BY dPreDate";

                $data2 = Db::connect('db_config1')->query($sqlDetail);
                $arr[$key]['children'] = $data2;
            }
            return array('data' => $arr, 'total' => count($data));

        } else {
            $sql = "SELECT Inventory.cInvCode ,Inventory.cInvName,SO_SODetails.cSOCode,cInvStd As cInvStdtd  ,SUM(iQuantity) AS Allup
FROM SO_SODetails 
LEFT JOIN SO_SODetails_extradefine ON SO_SODetails_extradefine.iSOsID = SO_SODetails.iSOsID
LEFT JOIN Inventory ON Inventory.cInvCode = SO_SODetails.cInvCode
LEFT JOIN SO_SOMain ON SO_SOMain.ID = SO_SODetails.ID
LEFT JOIN Customer ON Customer.cCusCode = SO_SOMain.cCusCode
WHERE left(SO_SODetails.cInvCode,2)='01'
GROUP BY Inventory.cInvCode,Inventory.cInvName,cInvStd,SO_SODetails.cSOCode";

            $data = Db::connect('db_config1')->query($sql);
            $arr = [];
            $arr2 = [];
            for ($n = ($page - 1) * 5; $n < ($page - 1) * 5 + 5; $n++) {
                $arr[$n] = $data[$n];
            }
            foreach ($arr as $key => $value) {
                $id = $value['cSOCode'];
                $sqlDetail = "SELECT ceiling(iQuantity/(CAST(cInvStd AS INTEGER)*0.000001*CAST(cDefine34 AS INTEGER)*0.001*900*CAST(fLength AS INTEGER))) AS 'pice',
CEILING(iQuantity/(CAST(cInvStd AS INTEGER)*0.000001*CAST(cDefine34 AS INTEGER)*0.001*900)) AS 'rice',
cbdefine3 ,
Inventory.cInvCode AS cInvCodeSS,SO_SODetails.cInvName as  cInvNameName,cInvStd,fLength ,
iQuantity,cDefine34 ,dDate ,dPreDate ,
Customer.cCusCode,cCusAbbName ,SO_SODetails.cSOCode
FROM SO_SODetails 
LEFT JOIN SO_SODetails_extradefine ON SO_SODetails_extradefine.iSOsID = SO_SODetails.iSOsID
LEFT JOIN Inventory ON Inventory.cInvCode = SO_SODetails.cInvCode
LEFT JOIN SO_SOMain ON SO_SOMain.ID = SO_SODetails.ID
LEFT JOIN Customer ON Customer.cCusCode = SO_SOMain.cCusCode
WHERE SO_SODetails.cSOCode=" . "'" . $id . "'" . "AND left(SO_SODetails.cInvCode,2)='01'
ORDER BY dPreDate";

                $data2 = Db::connect('db_config1')->query($sqlDetail);
                $arr[$key]['children'] = $data2;
            }
            return array('res' => json_encode($arr), 'total' => count($data));
        }

    }

    public function HangData()
    {
        $query = Request::param();
        $page = $query['page'] - 1;
        $countData = Db::table('chick_LiuYanDisPath')->field('id')->select();
        $total = count($countData);
        $Hang = Db::table('chick_LiuYanDisPath')->limit($page * 10, 10)
            ->order('spec desc')
            ->select();
        foreach ($Hang as $key => $item) {
            $Hang[$key]['style'] = json_decode($item['style']);
        }
        return array('data' => $Hang, 'total' => $total);
    }

    public function LimitPageSellOrderData()
    {
        $data = Request::param();
        $ArrData = $data['arr'];
        $spec = $data['spec'];
        $cut = $data['cut'];
        $SoCode = $data['SoCode'];
        $name = $data['Name'];
        $width = $data['Width'];
        $time = time();
        $arr2 = [];
        $FenQie = $data['FenQie'];
        $groupCode = $data['group'];
        $MakeGroupval = $data['MakeGroupval'];
        $HangCode = strval($groupCode) . substr(date('Y'), 2, 2) .
            date('m') . date('d') . strval($MakeGroupval);
        foreach ($ArrData as $va => $Ease) {
            if ($va % $cut == 0) {
                $accult = [];
                $val = 0;
                for ($a = 0; $a < $cut; $a++) {
                    if (($va + $a) < count($ArrData)) {
                        $accult[$val] = $ArrData[$va + $a];
                        $val += 1;
                    }
                }
                $status = Db::table('chick_LiuYanDisPath')
                    ->insert(['style' => json_encode($accult), 'spec' => $spec, 'dispathid' => $time, 'productName' => $name, 'sCoCOde' => $SoCode, 'width' => $width,
                        'CompleteCase' => 0, 'hangval' => 1, 'FenQie' => $FenQie, 'groupCode' => $groupCode, 'HangCode' => $HangCode]);
            }
        }
        return $status;
    }

    public function LimitPageSlitData()
    {
        $query = Request::param();
        $Name = $query['Name'];
        $cut = $query['cut'];
        $spec = $query['spec'];
        $SoCode = $query['SoCode'];
        $Arr = $query['arr'];
        $width = $query['Width'];
        $time = time();
        $FenQie = $query['FenQie'];
        $groupCode = $query['group'];
        $MakeGroupval = $query['MakeGroupval'];
        $HangCode = strval($groupCode) . substr(date('Y'), 2, 2) .
            date('m') . date('d') . strval($MakeGroupval);
        foreach ($Arr as $va => $Ease) {
            if ($va % $cut == 0) {
                $accult = [];
                $val = 0;
                for ($a = 0; $a < $cut; $a++) {
                    if (($va + $a) < count($Arr)) {
                        $accult[$val] = $Arr[$va + $a];
                        $val += 1;
                    }
                }
                $status = Db::table('chick_fenqieDisPath')
                    ->insert(['style' => json_encode($accult), 'spec' => $spec, 'dispathid' => $time, 'productName' => $Name, 'sCoCOde' => $SoCode, 'width' => $width,
                        'CompleteCase' => 0, 'hangval' => 1, 'FenQie' => $FenQie, 'groupCode' => $groupCode, 'HangCode' => $HangCode]);
            }
        }
        return $status;
    }

    public function GroupTeamSelectData()
    {
        $arr = Db::table('chick_TeamGrupManage')->select();
        return $arr;
    }
}
