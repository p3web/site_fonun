<?php
class data
{
	private $conn;
	private static function connection_string(){
		$servername = "localhost";
		$username = "fonoonta_db";
		$password = "123123$#@!";
		$database="fonoonta_db";
		try {
			$con =  new PDO("mysql:host=$servername;dbname=$database", $username, $password);
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$con->exec("set names utf8");
			return $con;
		}
		catch(PDOException $e)
		{
			echo "Connection failed: " . $e->getMessage();
		}
	}

	private  static function connection_open(){
		$conn=self::connection_string();
		return $conn;
	}

	private static function connection_close(){
		$conn = null;
	}

	public static function selects($table,$where)
	{
		$con=self::connection_open();
		if($where=='' || $where==null) {
			$cmd = "SELECT * FROM $table";
		}
		else {
			$cmd = "SELECT * FROM $table WHERE $where";
		}
		try {
			$stmt = $con->prepare($cmd);
			$stmt->execute();
			$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			$r=$stmt->fetchAll();
			self::connection_close();
			return $r;
        }

		catch(PDOException $e) {
			return $row= "Error: " . $e->getMessage();
		}
	}
	public static function selects_col($table,$cols,$where)
	{
		$con=self::connection_open();
		if($where=='' || $where==null) {
			$cmd = "SELECT $cols FROM $table";
		}
		else {
			$cmd = "SELECT $cols FROM $table WHERE $where";
		}
		try {

			$stmt = $con->prepare($cmd);
			$stmt->execute();
			$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			$r=$stmt->fetchAll();

			self::connection_close();
			return $r;

		}
		catch(PDOException $e) {
			return $row= "Error: " . $e->getMessage();
		}
	}
	public static function selects_join($table1, $table2 , $cols, $join_type = false, $on, $where = false, $order_by_filed = false, $order_type = false, $fetchAll = false){
		$con=self::connection_open();
		$sql = "SELECT $cols FROM $table1".($join_type!=false?" $join_type JOIN $table2 ":" INNER JOIN $table2")." ON ".$on.($where!=false?" WHERE $where ":"").($order_by_filed!=false?" ORDER BY $order_by_filed ":"").($order_type!=false?" $order_type ":"");
		try{
			$stmt = $con->prepare($sql);
			$stmt->execute();
			$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			self::connection_close();
			return $fetchAll!=false?$stmt->fetchAll():$result;
		}
		catch(PDOException $e) {
			return $row= "Error: " . $e->getMessage();
		}
	}

	public static function insertinto($table,$col,$param){
		$result="";
		$con=self::connection_open();
		try {
			$sql="INSERT INTO $table ($col)VALUES ($param)";
			//echo $sql ; exit;
			$con->exec($sql);
			$result = $con->lastInsertId();
		}
		catch(PDOException $e) {
			$result= $sql . "<br>" . $e->getMessage();
		}
		self::connection_close();
		return $result;
	}

	public static function update($table,$value,$where){
		$result="";
		$con=self::connection_open();
		try {
			$sql="UPDATE $table SET $value WHERE $where";
			$stmt = $con->prepare($sql);
			$res  = $stmt->execute();
            if($res)
                $result = "1";
		}
		catch(PDOException $e) {
			$result= $sql . "<br>" . $e->getMessage();
		}
		self::connection_close();
		return $result;
	}

	public static function delete($table,$where){
		$result="";
		$con=self::connection_open();
		try{

			$sql="DELETE FROM $table WHERE $where";
			$res = $con->exec($sql);
			if($res)
                $result = "1";
		}
		catch(PDOException $e) {
			$result= $sql . "<br>" . $e->getMessage();
		}
		self::connection_close();
		return $result;
	}


	public static function makeWhere($array){
        if(is_array($array)){
            $where = "";
            $last_key = end(array_keys($array));
            foreach ($array as $key => $value){
                $where .= "`$key` = '$value'";
                if($key != $last_key){$where .= " and ";}
            }
            return $where;
        }
        return false;
    }

    public static function execute_non_qury(){}
    public static function execute_reader(){}

}