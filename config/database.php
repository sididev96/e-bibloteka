<?php 
class Database {
  private $host = "localhost";
  private $database_name = "e_bibloteka";
  private $username = "root";
  private $password = "";
  public $conn;

  public function getConnection(){
    $this->conn = null;
    try{
      $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);

      $this->conn->exec("set names utf8");
    }catch(PD0Exception $exception) {
      echo "Database could not be coonnected: " . $exception->getMessage();
    }return $this->conn;
  }
}










?>