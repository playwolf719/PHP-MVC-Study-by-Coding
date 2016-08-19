<?php
/**
* 
*/
class User
{
	public $id;
	public $username;
	public $password;
	public $email;
	
	function __construct($id,$username,$password,$email)
	{
  		$this->id      = $id;
      	$this->username  = $username;
      	$this->password = $password;
      	$this->email = $email;
	}


    public static function all() {
      	$list = [];
      	$db = Db::getInstance();
      	$req = $db->query('SELECT * FROM user ');

	      // we create a list of Post objects from the database results
      	foreach($req->fetchAll() as $post) {
        	$list[] = new Post($post['id'], $post['username'], $post['content']);
      	}

      	return $list;
    }
}