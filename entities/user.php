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
  // CREATE
  public function registerUser()
  {
    $sqlQuery = "INSERT INTO " . $this->db_table . " 
                        SET 
                            first_name = :first_name,
                            last_name = :last_name,
                            email = :email,
                            password = :password,
                            age = :age,
                            gender = :gender";

    $stmt = $this->conn->prepare($sqlQuery);

    // sanitize
    $this->first_name = htmlspecialchars(strip_tags($this->first_name));
    $this->last_name = htmlspecialchars(strip_tags($this->last_name));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->password = htmlspecialchars(strip_tags($this->password));
    $this->age = htmlspecialchars(strip_tags($this->age));
    $this->gender = htmlspecialchars(strip_tags($this->gender));

    // bind data
    $stmt->bindParam(":first_name", $this->first_name);
    $stmt->bindParam(":last_name", $this->last_name);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":password", $this->password);
    $stmt->bindParam(":age", $this->age);
    $stmt->bindParam(":gender", $this->gender);

    try{
      $stmt->execute();
      print_r("success already executed");
      return true;
   } catch(PDOException $error){
      $errorcode = json_encode($error->errorInfo[0]);
      if($errorcode == 1062){
        http_response_code(400);
        return print_r(['status' => 'false','message'=>'email is already in use']);
      } else{
        return print_r(['status' => 'false','message'=>'Error']);
      }
    }

  }
  public function login($email, $password){

    $findUserQuery =
    "SELECT * FROM ". $this->db_table ." WHERE email = ? LIMIT 0,1";

    $stmt = $this->conn->prepare($findUserQuery);
    $stmt->bindParam(1, $email);
    $stmt->execute();

    $rowNumbers = $stmt->rowCount();

    if($rowNumbers > 0){

      $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

      $this->id = $dataRow('id');
      $this->first_name = $dataRow('first_name');
      $this->last_name = $dataRow('last_name');
      $this->email = $dataRow('email');
      $this->password = $dataRow('password');
      $this->age = $dataRow('age');
      $this->gender = $dataRow('gender');

      if($this->password == $password){
        $e = array(
          "id"=>$this->id,
          "first_name" => $this->first_name,
          "last_name" => $this->last_name,
          "email" => $this->email,
          "age" => $this->age,
          "gender" => $this->gender,
        );

        return print_r(['payload' => $e]);
      }else {
        http_response_code(401);
        return print_r(['status' => 'false', 'message'=> 'Wrong Credentials!']);
      }
    }else {
        http_response_code(401);
        return print_r(['status' => 'false', 'message'=> 'Wrong Credentials!']);
  }

 }
}

?>