<?php
require_once("model/User.php");
/**
* UserController
*/
class UserController extends Controller
{
    public $layout=false;

    public function beforeAction($action){
    	$loginArray=array(
    		"update","get","getList"
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
     * 注册
     * @return [type] [description]
     */
    public function register() {
    	try {
	        $param=array(
	            "username","email","password"
	        );
	        if(MyHelper::validateRequire($_POST,$param)){
	        	$user=new User();
	        	$user->username=$_POST["username"];
	        	$user->email=$_POST["email"];
	        	$user->password=$_POST["password"];
	        	$user->create();
	            MyHelper::retInJson("","注册成功",0);
	        }else{
	        	throw new Exception("缺少参数", 400);
	        }
    	} catch (Exception $e) {
    		MyHelper::retInJson("",$e->getMessage(),$e->getCode());
    	}
    }

    /**
     * 登陆
     * @return [type] [description]
     */
    public function login(){
    	try {
	        $param=array(
	            "username","password"
	        );
	        if(MyHelper::validateRequire($_POST,$param)){
	        	$user=new User();
	        	$obj=$user->login($_POST["username"],$_POST["password"]);
                $_SESSION["user.username"]=$obj["username"];
                $_SESSION["user.id"]=$obj["id"];
	            MyHelper::retInJson("","登陆成功",0);
	        }else{
	        	throw new Exception("缺少参数", 400);
	        }
    	} catch (Exception $e) {
    		MyHelper::retInJson("",$e->getMessage(),$e->getCode());
    	}
    }
    /**
     * 修改
     * @return [type] [description]
     */
    public function update(){
    	try {
	        $param=array(
	            "id","username","password"
	        );
	        if(MyHelper::arrKeyExistInArr($_POST,$param)){
	        	$user=new User();
	        	$user->id=$_POST["id"];
	        	$user->username=$_POST["username"];
	        	$user->password=$_POST["password"];
	        	$user->update();
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
    		return MyHelper::retInJson(User::find($_GET["id"]) );
    		
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
    		return MyHelper::retInJson(User::all() );
    		
    	} catch (Exception $e) {
    		MyHelper::retInJson("",$e->getMessage(),$e->getCode());
    	}
    }
}