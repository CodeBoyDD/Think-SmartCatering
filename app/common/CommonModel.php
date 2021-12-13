<?php


namespace app\common;


use think\Model;

class CommonModel extends Model
{

    //打包数据的格式：学校-》食堂/班级-》信息
    public function machiningData($data,$key1,$key2)
    {
        $item=array();
        foreach($data as $k=>$v){
            if(!isset($item[$v[$key1]])){
                if (!isset($item[$v[$key2]])){
                    $item[$v[$key1]][$v[$key2]][]=$v;
                }else{
                    $item[$v[$key1]][$v[$key2]][]=$v;
                }

            }else{
                if (!isset($item[$v[$key2]])){
                    $item[$v[$key1]][$v[$key2]][]=$v;
                }else{
                    $item[$v[$key1]][$v[$key2]][]=$v;
                }
            }
        }
        return $item;
    }
}