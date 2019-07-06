<?php

namespace app\index\controller\Login;

use app\index\Controller\BaseController;
use app\index\model\LoginModel;
use Firebase\JWT\JWT;
use function Sodium\add;
use think\Controller;
use think\Db;
use think\facade\Request;
use think\facade\Route;

class LoginController extends BaseController
{
    public function index()
    {
        $data = Request::param();
        $user = $data['username'];
        $passwd = $data['password'];
        $vaild = LoginModel::where('username', $user)->find();
        if ($user == $vaild['username'] && $passwd == $vaild['password']) {
            $msg = [
                'status' => 1001,
                'jwt' => $this->createJwt($vaild['id'])
            ];
            return $msg;
        }
        return $data;
    }

    public function getEditor()
    {
        $key = md5("nobita");
        $user = Request::header();
        $token = json_decode($user['x-token'], true);
        $jwtAuth = json_encode(JWT::decode($token['jwt'], $key, array("HS256")));
        $jwtAuth = json_decode($jwtAuth, true);
        $userid = $jwtAuth['user_id'];
        $data = Db::table('chick_test_role')->where('Userid', $userid)->field('path')->find();
        $route = json_decode($data['path'], true);
        return $route;
    }


    public function createJwt($userId)
    {
        $key = md5('nobita'); //jwt的签发密钥，验证token的时候需要用到
        $time = time(); //签发时间
        $expire = $time + 14400; //过期时间
        $token = array(
            "user_id" => $userId,
            "iss" => "http://127.0.0.1",//签发组织
            "aud" => "http://127.0.0.1", //签发作者
            "iat" => $time,
            "nbf" => $time,
            "exp" => $expire
        );
        $jwt = JWT::encode($token, $key);
        return $jwt;
    }

    public function userInfo()
    {
        $data = Request::header('x-token');
        $data = json_decode($data, true);
        $data = $data['jwt'];
        $key = md5("nobita");
//        $data = Request::param();
        $jwtAuth = json_encode(JWT::decode($data, $key, array("HS256")));
        $authInfo = json_decode($jwtAuth, true);
        $userinfo = LoginModel::where('id', $authInfo['user_id'])->find();
        return $userinfo;
    }

    public function getPosMiss()
    {
        $data = Db::table('chick_test_role')
            ->alias('a')
            ->select();
        return $data;
    }

//        public function GetPageRule()
//        {
//            $id = Request::post();
//            $arr = [];
//            $arr2 = [];
//            $data = Db::table('RoleModulePermission')->alias('a')->where('RoleId', 2)->select();
//            foreach ($data as $key => $datum) {
//                $data2 = Db::table('chick_rule')->where('id', $datum['PermissionId'])->select();
//                if (count($data2) != 0) {
//                    $arr[$key] = $data2;
//                    foreach ($data2 as $keys => $item) {
//                        $arr2[$key]['path'] = $item['path'];
//                        $child = Db::table('chick_rule_two')->where('ruleId', $item['id'])->select();
//                        foreach ($child as $k => $ittem) {
//                            $arr2[$key]['children'][$k]['path'] = $child[$k]['path'];
//                        }
//                    }
//                }
//            }
//
//            return array('routes' => $arr2);
//        }

//    public function GetPageRule()
//    {
//        $id = Request::post();
//        $arr = [];
//        $arr2 = [];
//        $val = 0;
//        $vval = 0;
//        $data = Db::table('RoleModulePermission')->alias('a')->where(['RoleId' => $id["data"], 'IsDeleted' => 0])->select();
//        foreach ($data as $key => $datum) {
//            $data2 = Db::table('chick_rule')->where('id', $datum['PermissionId'])->select();
//            foreach ($data2 as $kes => $value) {
//                if (empty($value['two_id'])) {
//                    $arr[$val]['path'] = $value['path'];
//                    $arr[$val]['id'] = $value['id'];
//                    $val += 1;
//                } else {
//                    $arr2[$key] = $value;
//                }
//            }
//
//            foreach ($arr2 as $key => $value) {
//                foreach ($arr as $kk => $item) {
//                    if ($arr[$kk]['id'] == $arr2[$key]['two_id']) {
//                        $arr[$kk]['children'][$key] = ['path' => $arr2[$key]['path']];
//                    }
//                }
//            }
//            $arr3 = [];
//            $v = 0;
//            foreach ($arr as $ke => $value) {
//                $arr3[$ke]['path'] = $value['path'];
//                if (!empty($value['children'])) {
//                    $v = 0;
//                    foreach ($value['children'] as $key => $item) {
//                        $arr3[$ke]['children'][$v] = $item;
//                        $v += 1;
//                    }
//                }
//            }
//
//        }
//
//        return array('routes' => $arr3);
//    }

//    public function AcceptRoleData()
//    {
//        $data = Request::param();
//        $id = $data['id'];
//        $role = $data['role']['routes'];
//        $arr = [];
//        foreach ($role as $key => $value) {
////            $data2 = Db::table('chick_rule')->where('path', $value['path'])->field('id')->find();
////            $arr[$key] = $data2;
//            foreach ( $role[$key]['children'] as $cc =>$role2 ) {
//                return $role2;
//                $data2 = Db::table('chick_rule')->where('path', $value['children']['path'])->field('id')->find();
//                $arr[$key] = $data2;
//            }
//        }
//        $val = 0;
//        $kka = [];
//        foreach ($arr as $k => $items) {
//            $val = $items['id'];
////            Db::table('RoleModulePermission')->where(['RoleId' => $id])
////                ->update(['IsDeleted' => 1]);
//            $st = Db::table('RoleModulePermission')->where(['RoleId' => $id, 'PermissionId' => $val])->find();
//            if (empty($st)) {
//                $help = [
//                    'IsDeleted' => 0,
//                    'RoleId' => $id,
//                    'ModuleId' => 1,
//                    'PermissionId' => $val,
//                ];
//                $a = Db::table('RoleModulePermission')->insert($help);
//            }
//            $kka[$k] = $st;
//        }
//        return $kka;
//
//    }

    public function GetPageRule()
    {
        $id = Request::post();
        $data = Db::table('chick_test_role')->where('id', $id['data'])
            ->find();
        return array('routes' => json_decode($data['path']));
    }

    public function AcceptRoleData()
    {
        $data = Request::param();
        $id = $data['id'];
        if (empty($id)) {
            return $data;
        } else {
            $da = Db::table('chick_test_role')->where('Userid', $id)->find();
            $arr = [
                'Userid' => $data['id'],
                'path' => json_encode($data['role']['routes'])
            ];
            if (!empty($da)) {
                Db::table('chick_test_role')->where('Userid', $id)->update($arr);
            } else {
                Db::table('chick_test_role')->insert($arr);
            }
        }
    }

    public function newRole()
    {
        $data = Request::param();
        $arr = [
            'describe' => $data['describe'],
            'role' => $data['role'],
            'path' => json_encode($data['routes'])
        ];
        Db::table('chick_test_role')->insert($arr);
    }

    public function LoginOut()
    {
        return array('success');
    }

    public function getUserPeopleInfo()
    {
        $data = Request::param();
        $user = Db::table('ckick_user')->where('id', $data['data'])->find();
        $role = Db::table('chick_test_role')->field('id')->field('describe')->select();
        return array('user' => $user, 'role' => $role);
    }

    public function UpUserEditorRole()
    {
        $data = Request::param();
        $roleid = $data['role'];
        $userid = $data['id'];
        $info = $data['info'];
        $user = Db::table('ckick_user')->where('id', $userid)->update(['roleid' => $roleid, 'introduction' =>
            $info['describe']]);
        return $user;
    }

    public function GetgroupInfo()
    {
        $data = Db::table('ckick_user')->alias('a')->join(['chick_test_role' => 'w'], 'a.roleid=w.id')
            ->field('a.id')
            ->field('a.username')
            ->field('a.password')
            ->field('w.role')
            ->field('a.introduction')
            ->select();

        $role = Db::table('chick_test_role')->field('describe')
            ->field('id')
            ->select();
        return array('data' => $data, 'role' => $role);
    }

    public function addRole()
    {
        $data = Request::param();
        return $data;
    }

    public function newUserPasswdRole()
    {
        $query = Request::param();
        $username = $query['data1']['username'];
        $password = $query['data1']['password'];
        $isHave = Db::table('ckick_user')->where('username', $username)->find();
        if ($isHave) {
            return array('code' => 1001);
        } else {
            $roles = Db::table('chick_test_role')->where('id', $query['role'])->field('role')->find();
            $stataus = Db::table('ckick_user')->insert(['username' => $username, 'password' => $password,
                'avatar' => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
                'name' => 'Henkaku', 'roles' => $roles['role'], 'roleid' => $query['role']]);
            return array('code' => $stataus);
        }
    }

    public function AddRoleLocalTrue()
    {
        $query = Request::param();
        $data1 = Request::param('data1');
        $roles = Db::table('chick_test_role')->where('id', $query['data2'])->field('role')->find();
        $status = Db::table('ckick_user')->insert(['username' => $data1['username'], 'password' => $data1['password'],
            'introduction' => $data1['describe'], 'roleid' => $query['data2'], 'avatar' => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
            'roles' => $roles['role'], 'name' => 'Henkaku']);
        return $roles;
    }

    public function DelectRoleList()
    {
        $reqery = Request::param();
        $id = $reqery['id'];
        $status = Db::table('chick_test_role')->where('id', $id)->delete();
        return array('code' => $status);
    }
}