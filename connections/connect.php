<?php 


Class myLibs {
	private $dsn = "mysql:host=localhost;dbname=clothing_db";
	private $username = "root";
	private $password = "";
	private $option = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
	protected $con;

	public function openConnection(){
		try{
			$this->con = new PDO($this->dsn, $this->username, $this->password, $this->option);
			return $this->con;
		}catch(PDOException $e){
			echo "Connection Failed! ".$e->getMessage();
		}

	}

	public function closeConnection(){
		$this->con = null;
	}



}

$lib = new myLibs();


?>