<?php

use \Firebase\JWT\JWT;

function signToken($uid){
    $key = '!@#$%*&';
    $token = array(
        "iss"=>$key,
        "aud"=>'',
        "iat"=>time(),
        "nbf"=>time()+3,
        "exp"=>time()+200,
        "data"=>[
            'uid'=>$uid,
        ]
    );

    $jwt = JWT::encode($token,$key,"HS256");
    return $jwt;
}

function checkToken($token){
    $key = '!@#$%*&';
    $status=array("code"=>2);
    try{
//        JWT::$leeway=60;
        $decoded = JWT::decode($token,$key,array(['HS256']));
        dump($decoded);
        $arr = (array)$decoded;
        $res['code']=1;
        $res['data']=$arr['data'];
        return $res;
    }catch (\Firebase\JWT\SignatureInvalidException $e){
        $status['msg']="签名不正确";
        return $status;
    }catch (\Firebase\JWT\BeforeValidException $e){
        $status['msg']="token失效";
        return $status;
    }catch (\Firebase\JWT\ExpiredException $e){
        $status['msg']="token失效";
        return $status;
    }catch (\Firebase\JWT\Exception $e){
        $status['msg']="未知错误";
        return $status;
    }
}