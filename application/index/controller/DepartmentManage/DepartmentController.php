<?php

namespace app\index\controller\DepartmentManage;

use app\index\model\ChickSection;
use think\Controller;
use think\Db;
use think\facade\Request;
use think\facade\Route;

class DepartmentController extends Controller
{

    public function QueryGroup($indx)
    {
        $user = new ChickSection();
        $section = $user->where('TypeID', $indx)
            ->field('SectionName as name')
            ->field('TypeID')
            ->field('id as value')->select();
        return $section;
    }

    public function GetTeamGroupTreeList()
    {
        /*$data = Db::connect('db_config1')->table('Department')->field("cDepName")->field("cDepCode")->select();
        $val = [
            'name' => '总经理',
            'value' => 1,
            'children' => []
        ];
        foreach ($data as $key => $datum) {
            $val['children'][$key]['name'] = $data[$key]['cDepName'];
            $val['children'][$key]['value'] = $data[$key]['ROW_NUMBER'] + 1;
        }
        return $val;*/


        $user = new ChickSection();
        $section = $user->where('TypeID', 1)
            ->field('SectionName as name')
            ->field('TypeID')
            ->field('id as value')->select();


        $val = [
            'name' => '总经理',
            'value' => 1,
            'TypeID' => 0,
            'children' => $section
        ];
        return $val;
    }


    public function GetRolePeople()
    {
        $page = Request::param();
        /*$data = Db::connect('db_config1')
            ->table('Person')
            ->alias('a')
            ->field('cPersonName')
            ->field('cPersonCode')
            ->field("cPersonPhone")
            ->join(['Department' => 'b'], 'a.cDepCode=b.cDepCode')
            ->field('cDepName')
            ->limit($page['page'] * $page['limit'], $page['limit'])
            ->select();

        $count = Db::connect('db_config1')
            ->table('Person')
            ->count('cPersonCode');
        return array('items' => $data, 'total' => $count);*/

        if (empty($page)) {
            $data = Db::table('chick_PeopleList')
                ->join(['chick_section' => 'w'], 'a.SectionId=w.id')
                ->alias('a')
                ->field('a.id')
                ->field('a.Jobnumber')
                ->field('a.Name')
                ->field('a.Phone')
                ->field('a.SectionId')
                ->field('w.SectionName')
                ->select();
            $data2 = Db::table('chick_PeopleList')
                ->join(['chick_section' => 'w'], 'a.SectionId=w.id')
                ->alias('a')
                ->field('a.id')
                ->field('a.Jobnumber')
                ->field('a.Name')
                ->field('a.Phone')
                ->field('a.SectionId')
                ->field('w.SectionName')
                ->select();
            $count = count($data2);
            return array('items' => $data, 'total' => $count);
        } else {
            $id = $page['value'];
            $data = Db::table('chick_PeopleList')->alias('as')
                ->join(['chick_section' => 'w'], 'a.SectionId=w.id')
                ->where('a.SectionId', $id)
                ->alias('a')
                ->field('a.id')
                ->field('a.Jobnumber')
                ->field('a.Name')
                ->field('a.Phone')
                ->field('a.SectionId')
                ->field('w.SectionName')
                ->select();
            $data2 = Db::table('chick_PeopleList')->alias('as')
                ->join(['chick_section' => 'w'], 'a.SectionId=w.id')
                ->where('a.SectionId', $id)
                ->alias('a')
                ->field('a.id')
                ->field('a.Jobnumber')
                ->field('a.Name')
                ->field('a.Phone')
                ->field('a.SectionId')
                ->field('w.SectionName')
                ->select();
            $count = count($data2);
            return array('items' => $data, 'total' => $count);
        }

    }

    public function GetDepartData()
    {
        $query = Request::param();
        $TypeID = $query['sedata']['TypeID'] + 1;
        $code = Db::table('chick_section')->
        insert(['SectionName' => $query['section']['duty'], 'TypeID' => $TypeID, 'ParentSecId' => $query['sedata']['value']]);
        return array('code' => $code);
    }

    public function GetSectionChildren()
    {
        $query = Request::param();
        $data = Db::table('chick_section')->where('ParentSecId', $query['value'])
            ->field('SectionName as name')
            ->field('TypeID')
            ->field('id as value')
            ->select();
        return $data;
    }

    public function DeleteSectionData()
    {
        $query = Request::param();
        $id = $query['value'];
        $status = Db::table('chick_section')->where('id', $id)->delete();
        return array('code' => $status);
    }

    public function addNewPeople()
    {
        $query = Request::param();
        $arr = [
            'Jobnumber' => $query['other']['Jobnumber'],
            'Name' => $query['other']['Name'],
            'Phone' => $query['other']['Phone'],
            'SectionId' => $query['section']['value']
        ];
        $status = Db::table('chick_PeopleList')->insert($arr);
        return array('code' => $status);
    }

    public function updateGroupData()
    {
        $data = Request::param();
        $status = Db::table('chick_PeopleList')->where('id', $data['id'])
            ->update(['Jobnumber' => $data['Jobnumber'], 'Name' => $data['Name'], 'Phone' => $data['Phone']
                , 'SectionId' => $data['SectionId']]);
        return array('code' => $status);
    }

    public function DeleteGroupList()
    {
        $query = Request::param();
        $id = $query['id'];
        $status = Db::table('chick_TeamGrupManage')->where('id', $id)->delete();
        return $query;
    }

}