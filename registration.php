<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("crud.php");

$student = new Database();

$fnameErr = $lnameErr = $emailErr = $passwordErr = $dobErr = $genderErr = $numberErr = $fileErr =  "";
$fname = $lname = $email= $number = $password = $dob = $gender = $img = $img_temp = $fileSize = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $fname = trim($_POST["firstName"]);
    if (empty($fname)) {
        $fnameErr = "firstName is required";
    } else {
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $fname)) {
            $fnameErr = "Only alphabets and white space are allowed";
        }
    }
    $lname = trim($_POST["lastName"]);
    if (empty($lname)) {
        $lnameErr = "lastName is required";
    } else {
        $lname = trim($_POST["lastName"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $lname)) {
            $lnameErr = "Only alphabets and white space are allowed";
        }
    }
    $email = trim($_POST["email"]);
    if (empty($email)) {
        $emailErr = "Email is required";
    } else {
        $email = trim($_POST["email"]);
        // check that the e-mail address is well-formed  
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    $password = trim($_POST["password"]);
    if (empty($password)) {
        $passwordErr = "password is required";
    } else {

       
        if (strlen($password) < 8) {
            $passwordErr = "Password too short! ";
        }
    }

    if (empty($_POST["dob"])) {
        $dobErr = "Birth date is required";
    } else {
        $dob = $_POST["dob"];
    }

    if (empty($_POST["gender"])) {
        $genderErr = "please select gender";
    } else {
        $gender = $_POST["gender"];
       
    }
    $number = trim($_POST["number"]);
    if (empty($number)) {
       $numberErr = "please enter number";
    }
    else {
      
        if (!preg_match("/^\d{10}$/ ", $number)) {
            
            $numberErr = "Please enter 10-digit phone number.";
        }
    }

    $img = $_FILES['image']['name'];
    $fileSize = $_FILES['image']['size'];
    $maxFileSize = $fileSize / 1024;
    $allowedExtensions = array('jpg', 'jpeg', 'png');
    $fileExtension = strtolower(pathinfo($img, PATHINFO_EXTENSION));

 
     if(empty($img)){
        $fileErr = "please select image";

     }else if($maxFileSize  > (2 * 1024)){
           $fileErr = "File must be smaller than 2MB";
     }else {
         if (!in_array($fileExtension, $allowedExtensions)) {
             $fileErr = "Only JPG, JPEG and PNG files are allowed.";
         }
     }


    

    if (empty($fnameErr) && empty($lnameErr) && empty($emailErr) && empty($numberErr) && empty($passwordErr) && empty($dobErr) && empty($genderErr) && empty($fileErr)) {

        $gender = $_POST['gender'];
      
        $img_temp = $_FILES['image']['tmp_name'];
        $folder = "images/" . $img;

        move_uploaded_file($img_temp, $folder);

        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        $result = $student->insert($fname, $lname, $email, $hashPassword, $dob, $gender, $number, $folder);

        if ($result) {
            echo "<script>alert('Data inserted successfully'); </script>";
            header("Refresh: 0; url = login.php");
        } else {
            echo "<script>alert('Data not inserted');</script>";
        }
    }
}



 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration form</title>
    <link rel="stylesheet" href="css/register.css">
    <style>
        *{
           box-sizing: border-box; 
        }
.error {
    color: #ff0000; 
    font-size:16px; 
    
    
}
div.error{
    height: 17px;
}
    </style>
</head>
<body>  
<div class="container">
        <div class="apply-box">
            <h2>Registration Form</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" >
                <div class="grid-container">
                    <div class="grid-item">
                        <label for="firstName">First Name :</label>
                        <input type="text" id="firstName" name="firstName" value="<?php echo $fname; ?>" placeholder="enter a firstName" />
                        <div class="error"  ><?php echo $fnameErr; ?> </div>
                          
                    </div>
                    <div class="grid-item">
                        <label for="lastName">Last Name :</label>
                        <input type="text" id="lastName" name="lastName" value="<?php echo $lname; ?>" placeholder="enter a lastName" />
                        
                        <div class="error"  ><?php echo $lnameErr; ?> </div>
                    </div>
                    <div class="grid-item">
                        <label for="email">Email :</label>
                        <input type="text" id="email" name="email"  value="<?php echo $email; ?>" autocomplete="on" placeholder="enter a email" onblur="checkEmail()">
                         
                        <div class="error"  ><?php echo $emailErr; ?> </div>
                    </div>
                    <div class="grid-item"> <label for="password">Password :</label>
                        <input type="password" id="password" name="password" value="<?php echo $password; ?>" placeholder="enter a password">
                        <div class="error"  ><?php echo $passwordErr; ?> </div>
                    </div>
                        <div class="grid-item">
                            <label for="dob">Date of Birth :</label>
                            <input type="date" id="dob" name="dob" value="<?php echo $dob; ?>">
                            <div class="error" ><?php echo $dobErr; ?> </div>
                    </div>
                    <div class="grid-item center">
                        <div class="gender">Gender : </div>
                         
                            <input type="radio" name="gender"  id="male" value="male">
                            <label for="male">Male</label>
                            <input type="radio" name="gender" id="female" value="female">
                            <label for="female"> Female</label>
                            <input type="radio" name="gender" id="other" value="other">
                            <label for="other">Other</label>
                         
                        </div>
                        <div class="error"  ><?php echo $genderErr; ?> </div>
                    <div class="grid-item"> <label for="contactNumber">Contact Number :</label>
                        <input type="number" id="contactNumber" name="number" value="<?php echo $number ?>" >
                        <div class="error"  ><?php echo $numberErr; ?> </div>
                    </div>
                    <div class="grid-item">
                        <label for="profilePicture">Profile Picture :</label>
                        <input type="file" id="profilePicture" name="image">
                        <div class="error"><?php echo $fileErr; ?> </div>                    
                    </div>
                    <div class="grid-item">
                        <button type="submit" name="submit">submit</button>
                    </div>
                    <a href="login.php">Click here to Login.</a>
                </div>
            </form>
        </div>
    </div>
    <script>
               function checkEmail() {
        let email = document.getElementById("email").value;
                
        let xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
           
                if (this.responseText == "exist" ) {
                    document.getElementById("emailErr").innerHTML = "Email already exists";
                } else {
                    document.getElementById("emailErr").innerHTML = "";
                }
        };
        xhttp.open("GET", "checkEmail.php?email="+email);
         
        xhttp.send();
    }   
    </script>
</body>
</html>