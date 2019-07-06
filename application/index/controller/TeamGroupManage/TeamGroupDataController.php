<?php


namespace app\index\controller\TeamGroupManage;


use think\Controller;
use think\Db;
use think\facade\Request;

class TeamGroupDataController extends Controller
{
    public function AddNewTeamGroup()
    {
        $query = Request::param();
        $group_id = strval(time()) . strval($query['point']) . strval(rand(9, 99));
        $status = Db::table('chick_TeamGrupManage')->insert(['group_id' => $group_id, 'group_name' => $query['name'],
            'group_Maxscope' => $query['scopeMax'], 'group_Minscope' => $query['scopeMin'], 'group_point' => $query['point']]);
        return array('code' => $status);
    }

    public function GetGroupManage()
    {
        $query = Request::param();
        $page = $query['page'];
        $arr = Db::table('chick_TeamGrupManage')->select();
        $total = count($arr);
        $arr2 = Db::table('chick_TeamGrupManage')->limit(($page - 1) * 10, 10)->select();
        $SelectGroup = Db::table('chick_section')->field('id as value')->field('SectionName as label')->select();
        return array('data' => $arr2, 'total' => $total, 'select' => $SelectGroup);
    }
}