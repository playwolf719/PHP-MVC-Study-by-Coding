<?php
/**
* User
*/
class User extends Model
{
	
	function __construct()
  	{
  	}

  	/**
  	 * 获取数据表名
  	 * @return [type]
  	 */
 	public static function tableName(){
 		return "user";
 	}

 	public static function propName(){
 		return array(
 			"id","username","password","email"
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
      		unset($temp["password"]);
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
     * 创建新对象
     * @return [type]
     */
    public function create(){
      	$db = Db::getInstance();
      	$tableName=self::tableName();
		$req = $db->prepare("SELECT * from {$tableName} where email=:email");
      	$req->execute(array("email"=>$this->email ));
      	if($req->fetchAll()){
      		throw new Exception("用户已存在", 400);
      	}
      	$req = $db->prepare("insert into {$tableName} (username,email,password) values (:username,:email,:password)");
      	$req=$req->execute(array('username' => $this->username,"email"=>$this->email,"password"=>$this->password));
      	if(!$req){
      		throw new Exception("操作失败", 500);
      	}
    }

    /**
     * 更新已有对象
     * @return [type]
     */
    public function update(){
      	$db = Db::getInstance();
      	if(!$obj=self::find($this->id)){
      		throw new Exception("用户不存在", 400);
      	}
      	if(!empty($this->username) ){
      		$obj["username"]=$this->username;
      	}
      	if(!empty($this->password) ){
      		$obj["password"]=$this->password;
      	}
      	$tableName=self::tableName();
      	$req = $db->prepare("UPDATE {$tableName} SET username=:username, password=:password where id=:id");
      	$req=$req->execute(array('username' => $obj["username"],"password"=>$obj["password"],"id"=>$this->id));
      	if(!$req){
      		throw new Exception("操作失败", 500);
      	}
    }

    /**
     * 登陆
     * @return [type]
     */
    public function login($username,$password) {
      	$db = Db::getInstance();
      	$tableName=self::tableName();
      	$req = $db->prepare("SELECT * FROM {$tableName} WHERE username = :username and password=:password");
      	$req->execute(array('username' => $username,"password"=>$password) );
      	if(!$obj=$req->fetch()){
      		throw new Exception("登陆失败", 400);
      	}
      	return $obj;
    }
}