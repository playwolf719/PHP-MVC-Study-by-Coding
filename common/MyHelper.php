<?php
/**
* 常用工具类
*/
class MyHelper
{
	/**
     * if all elems of an array all exist in another array
     * @param $data
     * @param $anotherArr
     *
     * @return bool
     */
    public static function arrKeyExistInArr($data,$anotherArr){
        if(count(array_intersect_key(array_flip($anotherArr), $data)) === count($anotherArr)) {
            return true;
        }
        return false;
    }

    /**
     * return as json
     * @param  array   $data [description]
     * @param  string  $msg  [description]
     * @param  integer $code [description]
     * @return [type]        [description]
     */
    public static function retInJson($data="",$msg="操作成功！",$code=0){
        if(empty($data)){
            $data="";
        }
        if(empty($msg)){
            $msg="操作成功！";
        }
        echo json_encode(array('ret' => $code, 'msg' => $msg,'data'=>$data) );
        return ;
    }

    public static function validateRequire($data,$requireFieldArray){
    	foreach ($requireFieldArray as $key => $value) {
    		if(!array_key_exists($value, $data)){
    			return false;
    		}
			if(!$data[$value]){
    			return false;
			}
    	}
		return true;
    }
}