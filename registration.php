<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("crud.php");

$student = new Database();

$fnameErr = $lnameErr = $emailErr = $passwordErr = $dobErr = $genderErr = $numberErr = "";
$fname = $lname = $email= $number = $password = $dob = $gender = $img = $img_temp = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {

    if (empty($_POST["firstName"])) {
        $fnameErr = "firstName is required";
    } else {
        $fname = input_data($_POST["firstName"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $fname)) {
            $fnameErr = "Only alphabets and white space are allowed";
        }
    }

    if (empty($_POST["lastName"])) {
        $lnameErr = "lastName is required";
    } else {
        $lname = input_data($_POST["lastName"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $lname)) {
            $lnameErr = "Only alphabets and white space are allowed";
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = input_data($_POST["email"]);
        // check that the e-mail address is well-formed  
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }


    if (empty($_POST["password"])) {
        $passwordErr = "password is required";
    } else {

        $password = input_data($_POST["password"]);
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
    if (empty($_POST["number"])) {
       $numberErr = "please enter number";
    }
    else {
        $number = input_data($_POST["number"]);
        if (!preg_match("/^\d{10}$/ ", $number)) {
            
            $numberErr = "Please enter 10-digit phone number.";
        }
    }

    if (empty($fnameErr) && empty($lnameErr) && empty($emailErr) && empty($numberErr) && empty($passwordErr) && empty($dobErr) && empty($genderErr)) {

        $gender = $_POST['gender'];
       
        $img = $_FILES['image']['name'];
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



function input_data($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration form</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
<div class="container">
        <div class="apply-box">
            <h2>Registration Form</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="grid-container">
                    <div class="grid-item">
                        <label for="firstName">First Name :</label>
                        <input type="text" id="firstName" name="firstName" value="<?php echo $fname; ?>" placeholder="enter a firstName" />
                        <span class="error" id="fnameErr"><?php echo $fnameErr; ?> </span>
                          
                    </div>
                    <div class="grid-item">
                        <label for="lastName">Last Name :</label>
                        <input type="text" id="lastName" name="lastName" value="<?php echo $lname; ?>" placeholder="enter a lastName" />
                        <span class="error" id="lnameErr"><?php echo $lnameErr; ?></span>
                    </div>
                    <div class="grid-item">
                        <label for="email">Email :</label>
                        <input type="text" id="email" name="email"  value="<?php echo $email; ?>" autocomplete="on" placeholder="enter a email" onblur="checkEmail()">
                        <span class="error" id="emailErr">  <?php echo $emailErr; ?></span>
                    </div>
                    <div class="grid-item"> <label for="password">Password :</label>
                        <input type="password" id="password" name="password" value="<?php echo $password; ?>" placeholder="enter a password">
                        <span class="error" id="passwordErr"><?php echo $passwordErr; ?></span>
                    </div>
                        <div class="grid-item">
                            <label for="dob">Date of Birth :</label>
                            <input type="date" id="dob" name="dob" value="<?php echo $dob; ?>">
                            <span class="error" id="dateErr"><?php echo $dobErr; ?> </span>
                    </div>
                    <div class="grid-item center">
                        <span class="gender">Gender : </span>
                         
                            <input type="radio" name="gender"  id="male" value="male">
                            <label for="male">Male</label>
                            <input type="radio" name="gender" id="female" value="female">
                            <label for="female"> Female</label>
                            <input type="radio" name="gender" id="other" value="other">
                            <label for="other">Other</label>
                         
                        <span class="error" id="genderErr"> <?php echo $genderErr; ?></span>
                    </div>
                    <div class="grid-item"> <label for="contactNumber">Contact Number :</label>
                        <input type="number" id="contactNumber" name="number" value="<?php echo $number ?>" >
                        <span class="error" id="numberErr"><?php echo $numberErr; ?> </span>
                    </div>
                    <div class="grid-item">
                        <label for="profilePicture">Profile Picture :</label>
                        <input type="file" id="profilePicture" name="image">
                        <span class="error" id="imgErr"> </span>
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