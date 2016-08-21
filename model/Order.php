<?php
/**
* Order
*/
class Order extends Model
{
    const PAYING=1;
    const SERVING=2;
    const DONE=3;
    const CANCEL=9;
    
    function __construct()
    {
    }

    /**
     * 获取数据表名
     * @return [type]
     */
    public static function tableName(){
        return "theorder";
    }

    public static function propName(){
        return array(
            "id","orderNum","cart","payUrl","cost","status","shipping","totalCost","time","userId"
        );
    }


    /**
     * 获取所有符合要求的结果集
     * @return [type]
     */
    public static function all() {
        $list = [];
        $db = Db::getInstance();
        $tableName=self::tableName();
        $req = $db->query("SELECT * FROM $tableName");

        foreach($req->fetchAll() as $obj) {
            $temp=array();
            foreach (self::propName() as $key => $value) {
                if(!array_key_exists($value, $obj) ){
                    throw new Exception("属性不存在", 500);
                }
                $temp[$value]=$obj[$value];
            }
            $list[] = $temp;
        }

        return $list;
    }


    /**
     * 获取该id对应的结果
     * @return [type]
     */
    public static function find($id) {
        $db = Db::getInstance();
        // we make sure $id is an integer
        $id = intval($id);
        $tableName=self::tableName();
        $req = $db->prepare("SELECT * FROM $tableName WHERE id = :id");
        // the query was prepared, now we replace :id with our actual $id value
        $req->execute(array('id' => $id));
        $obj=$req->fetch();
        if(!$obj){
            throw new Exception("未发现", 400);
        }
        foreach (self::propName() as $key => $value) {
            if(!array_key_exists($value, $obj) ){
                throw new Exception("属性不存在", 500);
            }
            $temp[$value]=$obj[$value];
        }
        return $temp;
    }



    /**
     * 获取该paymentId对应的结果
     * @return [type]
     */
    public static function findByPaymentId($paymentId) {
        $db = Db::getInstance();
        // we make sure $id is an integer
        $tableName=self::tableName();
        $req = $db->prepare("SELECT * FROM $tableName WHERE orderNum = :orderNum");
        // the query was prepared, now we replace :id with our actual $id value
        $req->execute(array('orderNum' => $paymentId));
        $obj=$req->fetch();
        if(!$obj){
            throw new Exception("未发现", 400);
        }
        foreach (self::propName() as $key => $value) {
            if(!array_key_exists($value, $obj) ){
                throw new Exception("属性不存在", 500);
            }
            $temp[$value]=$obj[$value];
        }
        return $temp;
    }

    
    /**
     * 创建新对象
     * @return [type]
     */
    public function create(){
        $db = Db::getInstance();
        $tableName=self::tableName();
        $req = $db->prepare("insert into {$tableName} (orderNum,payUrl,cart,cost,status,shipping,time,totalCost,userId) values (:orderNum,:payUrl,:cart,:cost,:status,:shipping,now(),:totalCost,:userId )");
        // var_dump($this);
        // die();
        $req=$req->execute(array('orderNum' => $this->orderNum,"payUrl"=>$this->payUrl,"cart"=>$this->cart,"cost"=>$this->cost,"status"=>$this->status,"shipping"=>$this->shipping,"totalCost"=>$this->totalCost,"userId"=>$this->userId ) );
        if(!$req){
            throw new Exception("操作失败", 500);
        }
    }

    /**
     * 更新已有对象
     * @return [type]
     */
    public function updateStatus($status){
        $db = Db::getInstance();
        if(empty($status) ){
            throw new Exception("状态值不能为空", 400);
        }
        $tableName=self::tableName();
        $req = $db->prepare("UPDATE {$tableName} SET status=:status where id=:id");
        $req= $req->execute(array('status' => $status,"id"=>$this->id));
        if(!$req){
            throw new Exception("操作失败", 500);
        }
    }

}