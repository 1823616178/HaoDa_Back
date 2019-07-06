<?php

namespace app\index\controller;

use Firebase\JWT\ExpiredException;
use think\Controller;
use Firebase\JWT\JWT;
use think\Exception;
use think\facade\Request;

class BaseController extends Controller
{
    public function _initialize(){
        parent::initialize();
        $this->checkToken();
    }

    public function checkToken(){
        $header = Request::header();
        if($header['x-token']=="null"){
            return [
                'status'=>1002,
                "msg"=>"Token不存在，拒绝访问"
            ];
            exit;
        }
        else{
            $checkToken = $this->verifyJwt($header['x-token']);
            if($checkToken['status']==1001){
                return true;
            }
        }
    }

    protected function verifyJwt($jwt){
        $key = md5("nobita");
        try{
            $jwtAuth = json_encode(JWT::decode($jwt,$key,array("HS256")));
            $authInfo = json_decode($jwtAuth,true);
            $msg = [];
            if(!empty($authInfo['user_id'])){
                $msg = [
                    "status"=>1001,
                    "msg"=>"Token验证通过"
                ];
            }else{
                $msg=[
                    'status'=>1002,
                    "msg"=>'Token验证不通过'
                ];
            }
            return $msg;
        }catch (\Firebase\JWT\SignatureInvalidException $e){
            return [
                "status"=>1002,
                "msg"=>"Token无效"
            ];
            exit;
        }catch (ExpiredException $e){
            return [
                "status"=>1003,
                "msg"=>"Token过期"
            ];
            exit;
        }catch (Exception $e){
            return $e;
        }
    }


}
