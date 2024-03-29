<?php 


 
class Database { 
    private $serverName = 'localhost';
    private $username = 'root';
   private  $password = 'root';
  private $dbName = ' student';
  protected $connection;
     
  // database connection 
	public function __construct()
	{
		if (!isset($this->connection)) {
			
			$this->connection = new mysqli($this->serverName, $this->username, $this->password, $this->dbName);
			
			if (!$this->connection) {
				echo 'Cannot connect to database server';
				exit;
			}
             
   
		}	
	
	}

		// insert data function
    public function insert($fname, $lname, $email, $password, $date, $gender, $number, $image){
		
		$sql = "INSERT INTO users(first_name, last_name, email, password, dob, gender, contact_number, profile_picture)
		VALUES('$fname', '$lname', '$email','$password', '$date', '$gender', '$number', '$image')";
        $result = $this->connection->query($sql);
		if($result)
		{
			 return true;
		}
		else
	   {
			return false;
	   }
    }

	// display data function
    public function displayData(){

        $sql = "SELECT * FROM users";

		$result = $this->connection->query($sql);
	 
		return $result;
	}

	// fetch data to input field

	public function update($id){

		 $update_sql = "SELECT * FROM users WHERE id = $id";
		 $result = $this->connection->query($update_sql); 
		 return $result;
	}
	
	// update data function
	public function update_data($fname, $lname,		$date, $gender, $number, $image, $id){

		$update_data = "UPDATE users SET first_name = '$fname', last_name = '$lname', dob = '$date', gender = '$gender', contact_number = '$number', profile_picture = '$image' WHERE id = $id ";

		$result = $this->connection->query($update_data); 
		if($result)
		{
			 return true;
		}
		else
	   {
			return false;
	   }
    
	}

	// delete data function
	public function delete($id){

		$delete_sql = "DELETE FROM users WHERE id = $id";

		$result = $this->connection->query($delete_sql);

		if ($result) {
			  return true;
		}else{

			return false;
		}

	}

	//  login 


	public function login($email){

        $sql = "SELECT * FROM users WHERE email = '$email'";

		$result = $this->connection->query($sql);
	 
		return $result;
	}
	 
	
	// email existing function
	public function checkEmailExists($email) {
		$sql = "SELECT * FROM users WHERE email = '$email'";
		$result = $this->connection->query($sql);
		return $result;
	}
}


 
 

    
 

 



 

 