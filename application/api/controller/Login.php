<?php


namespace app\api\controller;


use app\api\model\AdminModel;
use Firebase\JWT\JWT;
use think\Controller;
use think\Request;

class Login extends Base
{
    public function login(Request $request)
    {
        $username = $request->param('username');
        $password = $request->param('password');
        $admin = new AdminModel();
        $info = $admin->where('username',$username)->find();
        if(!$info){
            return json(['code'=>0, 'msg'=>'账号不存在']);
        }
        if($info['password'] != md5($password)){
            return json(['code'=>0, 'msg'=>'账号或密码错误']);
        }


        $jwt = new JWT();
        $key = 'api123456';
        $payload = [
            'iss'   =>  'http://tpvue.ico:8033',
            'aud'   =>  'http://tpvue.ico:8033',
            'iat'   =>  time(),
            'nbf'   =>  time(),
            'aid'   =>  $info['id']
        ];

        $token = $jwt::encode($payload,$key);

        return json(['code'=>1, 'msg'=>'登录成功','token'=>$token]);

    }
}