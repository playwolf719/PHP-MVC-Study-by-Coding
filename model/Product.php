<?php
/**
* Product
*/
class Product extends Model
{
    
    function __construct()
    {
    }

    /**
     * 获取数据表名
     * @return [type]
     */
    public static function tableName(){
        return "product";
    }

    public static function propName(){
        return array(
            "id","name","price"
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

}