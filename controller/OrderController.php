<?php
require_once("model/Order.php");
/**
* OrderController
*/
class OrderController extends Controller
{
    public $layout=false;

    public function beforeAction($action){
    	$loginArray=array(
    		"updatestatus","get","getList"
		);
        if(!isset($_SESSION)){
            session_start();
        }
    	if(in_array($action,$loginArray )){
    		if(!isset($_SESSION["user.username"]) ){
    			throw new Exception("请先登陆", 400);
    		}
    	}
    }
    /**
     * 修改
     * @return [type] [description]
     */
    public function updatestatus(){
    	try {
	        $param=array(
	            "id","status"
	        );
	        if(MyHelper::validateRequire($_POST,$param)){

	        	$user=new Order();
	        	$user->id=$_POST["id"];
	        	$user->updateStatus($_POST["status"] );
	            MyHelper::retInJson("","修改成功",0);
	        }else{
	        	throw new Exception("缺少参数", 400);
	        }
    	} catch (Exception $e) {
    		MyHelper::retInJson("",$e->getMessage(),$e->getCode());
    	}
    }

    /**
     * 获取一个对象
     * @return [type]
     */
    public function get(){
    	try {
	        $param=array(
	            "id"
            );
            if(!MyHelper::validateRequire($_GET,$param)){
                throw new Exception("缺少参数", 400);
            }
    		return MyHelper::retInJson(Order::find($_GET["id"]) );
    		
    	} catch (Exception $e) {
    		MyHelper::retInJson("",$e->getMessage(),$e->getCode());
    	}
    }

    /**
     * 获取多个对象
     * @return [type]
     */
    public function getlist(){
    	try {
    		return MyHelper::retInJson(Order::all() );
    		
    	} catch (Exception $e) {
    		MyHelper::retInJson("",$e->getMessage(),$e->getCode());
    	}
    }
}