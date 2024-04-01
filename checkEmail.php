<?php 

$conn = mysqli_connect("localhost","root","root"," student");
 
 $email = $_GET["email"];

 
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $sql);  
 
$num_row = mysqli_num_rows($result);
  
if($num_row > 0){
    echo "exist";
} else {
    echo "not-exist";
}
 