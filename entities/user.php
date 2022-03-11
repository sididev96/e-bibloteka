<?php 
class User{
  //connection
  private $conn;
  // Table
  private $db_table = "users";
  // Columns
  public $id;
  public $first_name;
  public $last_name;
  public $email;
  public $age;
  public $gender;
  public $created_at;
  public $updated_at;
  // Db connection
  public function __construct($db)
  {
    // 
    $this->conn = $db;
  }
  // Get all users
  public function getUsers(){
    // create a query to fetch data from the database
    $sqlQuery = "SELECT id, first_name, last_name, email, password, age, gender, created_at, updated_at FROM " . $this->db_table . "";
    // prepare the database connection
    $stmt = $this->conn->prepare($sqlQuery);
    // execute the statement to the database
    $stmt->execute();
    return $stmt;
  }
}


?>