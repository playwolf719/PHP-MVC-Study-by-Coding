<?php
require_once("model/Product.php");
require_once("common/PayPalCommon.php");
/**
* ProductController
*/
class ProductController extends Controller
{
    public $layout=false;

    public function beforeAction($action){
        $loginArray=array(
            "buy","addtocart","removefromcart","removecart"
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
     * 获取购物车
     * @return [type] [description]
     */
    public function getcart(){
        try{
            //购物车不存在
            if(!isset($_SESSION["cart"]) || count(json_decode($_SESSION["cart"],true) )==0 ) {
                throw new Exception("购物车不存在", 400);
            }
            MyHelper::retInJson(json_decode($_SESSION["cart"],true));
        }catch (\Exception $e){
            MyHelper::retInJson("",$e->getMessage(),$e->getCode());
        }
    }
    /**
     * 添加到购物车
     * @return [type] [description]
     */
    public function addtocart(){
        try{
            if(!MyHelper::arrKeyExistInArr($_POST,array("id"))){
                throw new \Exception("参数错误",400);
            }
            $obj=Product::find($_POST["id"]);
            $cart=[];
            //购物车不存在
            if(!isset($_SESSION["cart"]) ){
                $cart["productiId_".$obj["id"]]=$obj;
                $cart["productiId_".$obj["id"]]["number"]=1;
            //购物车已存在
            }else{
                $cart=json_decode($_SESSION["cart"],true);
                if(array_key_exists("productiId_".$obj["id"], $cart) ){
                    $cart["productiId_".$obj["id"]]["obj"]+=1;
                }else{
                    $cart["productiId_".$obj["id"]]=$obj;
                    $cart["productiId_".$obj["id"]]["number"]=1;
                }
            }
            $_SESSION["cart"]=json_encode($cart);
            MyHelper::retInJson($cart);
        }catch (\Exception $e){
            MyHelper::retInJson("",$e->getMessage(),$e->getCode());
        }
    }
    /**
     * 从购物车中删除商品
     * @return [type] [description]
     */
    public function removefromcart(){
        try{
            if(!MyHelper::arrKeyExistInArr($_POST,array("id"))){
                throw new \Exception("参数错误",400);
            }
            $obj=Product::find($_POST["id"]);
            $cart=[];
            //购物车存在
            if(isset($_SESSION["cart"]) ){
                $cart=json_decode($_SESSION["cart"],true);
                if(array_key_exists("productiId_".$obj["id"], $cart) ){
                    $cart["productiId_".$obj["id"] ]["number"]-=1;
                    if($cart["productiId_".$obj["id"]]["number"]<=0){
                        unset($cart["productiId_".$obj["id"] ] );
                    }
                }else{
                    throw new \Exception("商品不在购物车中",400);
                }
                //判断购物车中是否存在商品
                if(count($cart)==0){
                    unset($_SESSION["cart"]);
                }else{
                    $_SESSION["cart"]=json_encode($cart);
                }
            }else{
                throw new \Exception("购物车不存在",400);
            }
            MyHelper::retInJson($cart);
        }catch (\Exception $e){
            MyHelper::retInJson("",$e->getMessage(),$e->getCode());
        }
    }

    /**
     * 删除购物车
     * @return [type] [description]
     */
    public function removecart(){
        try{
            //购物车存在
            if(isset($_SESSION["cart"]) ){
                unset($_SESSION["cart"]);
            }
            MyHelper::retInJson();
        }catch (\Exception $e){
            MyHelper::retInJson("",$e->getMessage(),$e->getCode());
        }
    }

    /**
     * 购买购物车中的商品
     * @return [type] [description]
     */
    public function buy(){
        try{
            //购物车存在
            if(!isset($_SESSION["cart"]) || count(json_decode($_SESSION["cart"],true) )==0 ) {
                throw new Exception("购物车商品不存在", 400);
            }
            $param=$_POST;
            $param["cart"]=json_decode($_SESSION["cart"],true);
            if(!MyHelper::arrKeyExistInArr($param,array("shipping","payinfo"))){
                throw new \Exception("缺少参数",400);
            }
            if(!is_numeric($param["shipping"])||floatval($param["shipping"])<0){
                throw new \Exception("shipping wrong",400);
            }
            if(!is_array($param["payinfo"])||!array_key_exists("payMethod",$param["payinfo"])){
                throw new \Exception("payinfo wrong",400);
            }
            $orderInfo=PayPalCommon::getPayRes($param);
            $order=new Order();
            foreach ($orderInfo as $key => $value) {
                $order->$key=$value;
            }
            $order->create();
            MyHelper::retInJson($orderInfo);
        }catch (\Exception $e){
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
            return MyHelper::retInJson(Product::find($_GET["id"]) );
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
            return MyHelper::retInJson(Product::all() );
        } catch (Exception $e) {
            MyHelper::retInJson("",$e->getMessage(),$e->getCode());
        }
    }
}