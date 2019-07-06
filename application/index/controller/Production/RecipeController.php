<?php

namespace app\index\controller\Production;

use think\Controller;
use think\Db;
use think\facade\Request;

class RecipeController extends Controller
{
    public function productClass()
    {
        $sql = "SELECT cInvCCode,cInvCName,iInvCGrade FROM InventoryClass WHERE LEFT(cInvCCode,2)='02'";
        $data = Db::connect('db_config1')->query($sql);
        $arr = [
            'name' => '产品分类',
            'children' => [],
            'value' => 1
        ];
        foreach ($data as $key => $datum) {
            $arr['children'][$key]['name'] = $datum['cInvCName'];
            $arr['children'][$key]['value'] = $datum['cInvCCode'];
        }
        return $arr;
    }

    public function QueyProduct()
    {
        $qu = Request::param();
        $data = Db::connect('db_config1')->table('dbo.Inventory')->where('cInvCCode', $qu['id'])
            ->field('cInvCode')
            ->field('cInvAddCode')
            ->field('cInvName')
            ->field("cInvCCode")
            ->field("cInvStd")
            ->field('cCreatePerson')
            ->field('cModifyPerson')
            ->field('cValueType')
            ->field('cInvMnemCode')
            ->order('cInvStd')
            ->select();
        return $data;
    }


    public function QueryRepicList()
    {
        $query = Request::param();
        $id = $query['cInvCode'];
        $data = Db::table('chick_recipe')->where(["cinv" => $id, 'Ddelete' => 0])->select();
        if (!empty($data)) {
            foreach ($data as $Key => $datum) {
                if ($datum['isDelete'] == 0) {
                    $data[$Key]['default'] = 0;
                } else {
                    $data[$Key]['default'] = 1;
                }
                return $data;
            }
        } else {
            return null;
        }

    }


    public function upRepicList()
    {
        $query = Request::param();
        $time = $query['data']['a'];
        $cInvCode = $query['product']['cInvCode'];
        $id = $query['data']['id'];
        $a = str_replace(':', '-', $time);
        $ti = strtotime($a);
        $status = Db::table('chick_recipe')->where(['cinv' => $cInvCode, 'id' => $id])->find();
        if (!empty($status)) {
            Db::table('chick_recipe')->where(['cinv' => $cInvCode, 'id' => $id])->update(['id' => $id, 'Name' => $query['data']['Name']
                , 'CreatDate' => $ti, 'number' => $query['data']['number'], 'isDelete' => !$query['isDelete']]);
        }
        return $status;
    }


    public function newDiageAdd()
    {
        $data = Request::param();
        $OneLetter = Db::connect("db_config1")->table('InventoryClass')->where('cInvCCode', $data['formulaList'])->field('cInvCName')->find();
        $OneLetter = substr($OneLetter['cInvCName'], 0, 1);
        $productData = $data['productData']['cInvStd'];
        $year = date('ymd', time());
        $number = $OneLetter . $productData . $year . mt_rand(10, 999);
        $isrepe = Db::table('chick_recipe')->where('number', $number)->field('id')->find();
        if (!empty($isrepe)) {
            $number = $OneLetter . $productData . $year . mt_rand(10, 999);
        }
        $arr = [
            'CreatDate' => strtotime($data['data']['CreatDate']),
            'StopDate' => strtotime($data['data']['StopDate']),
            'Name' => $data['data']['Name'],
            'cinv' => $data['productData']['cInvCode'],
            'number' => $number,
            'Ddelete'=>0
        ];
        if ($data['isDelete'] == "false") {
            $arr['isDelete'] = 0;
        } else {
            $arr['isDelete'] = 1;
        }
        Db::table('chick_recipe')->insert($arr);
        return $number;
    }

    public function getRecipeDetail()
    {
        $data = Request::param();
        $number = $data['number'];
        $st = Db::table('chick_recipe_detail')->where(['number' => $number, 'isDelete' => 0])->select();
        return $st;
    }

    public function delateClick()
    {
        $data = Request::param();
        $number = $data['number'];
        $cinv = $data['cinv'];
        Db::table('chick_recipe')->where(['number' => $number, 'cinv' => $cinv])->update(['Ddelete' => 1]);
        return $data;
    }

    public function newProductDiageDetil()
    {
        $reques = Request::param();
        $addArr = [
            'number' => $reques['DataList']['number'],
            'Name' => $reques['newDiageAdd']['Name'],
            'cInvCode' => $reques['formList']['cInvCode'],
            'ratio' => $reques['newDiageAdd']['ratio'],
            'explain' => $reques['newDiageAdd']['remark'],
            'cInvStd' => $reques['newDiageAdd']['cInvStd'],
            'level' => $reques['newDiageAdd']['level'],
            'isDelete' => 0,
        ];
        $status = Db::table('chick_recipe_detail')->insert($addArr);
        return array('code' => $status);
    }

    public function DeleteDetails(){
        $reques = Request::param();
        $id = $reques['id'];
        $data = Db::table('chick_recipe_detail')->where('id',$id)->update(['isDelete'=>1]);
        return array('code'=>$data);
    }


    public function UpdataNewReciptData(){
        $data = Request::param();
        $id = $data['id'];
        $status = Db::table('chick_recipe_detail')->where('id',$id)->update((array)$data);
        return array('code'=>$status);
    }
}