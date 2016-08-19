<?php
require_once("base/Controller.php");
require_once("common/MyHelper.php");
/**
 * UserController
 */
class UserController extends Controller{
    public $layout=false;

    public function index() {
      	$this->layout=false;
        return "views/user/user.php";
    }

    /**
     * 注册
     * @return [type] [description]
     */
    public function register() {
        $param=array(
            "email","username","password"
        );
        if(MyHelper::arrKeyExistInArr($_POST,$param ) && MyHelper::validateRequire($_POST,$param)){
            MyHelper::retInJson("","注册成功",0);
        }else{
            MyHelper::retInJson("","缺少参数",400);
        }
    }
}
?>