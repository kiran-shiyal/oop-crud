<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("crud.php");

$student = new Database();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

 
require 'vendor/autoload.php';

function sendmail_verify($fname, $lname, $email){
    
    $mail = new PHPMailer(true);
    try{  
          
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'patoliya.ph@gmail.com';                                    //SMTP username
    $mail->Password   = 'wyptgelknchzusrm';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
    //Recipients
    $mail->setFrom('patoliya.ph@gmail.com', 'patoliya infotech');
    $mail->addAddress($email);     
     
    //Content
    $mail->isHTML(true);                               
    $mail->Subject = 'Email verification from patoliya infotech';
    $mail->Body    = "You have Registered with Patoliya infotech
    Thanks for registration! " .$fname . $lname;
    
    $mail->send();
  echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}
$emailErr = "";
$fname = $lname = $email= $number = $password = $dob = $gender = $img = $img_temp = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {


        $fname = $_POST["firstName"];
        $lname =  $_POST["lastName"];
        $email =  $_POST["email"];
        $password =  $_POST["password"];

        $gender = $_POST["gender"] ?? ''    ;
        $img = $_FILES['image']['name'];
        $img_temp = $_FILES['image']['tmp_name'];
        $number = $_POST['number'];
        $folder = "images/" . $img;
       
        $dob = $_POST["dob"];
        $myDate = new DateTime($dob);
        
        $birth_date = $myDate->format('Y-m-d');
        move_uploaded_file($img_temp, $folder);

        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
         
        $result = $student->login($email);


        if (mysqli_num_rows($result) > 0) {
            
            $emailErr = "Email already exists";

        }else {
            
        sendmail_verify("$fname","$lname","$email");
        $result = $student->insert($fname, $lname, $email, $hashPassword, $birth_date, $gender, $number, $folder);

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
    <link rel="stylesheet" href="css/register.css">
    <title>User Registration</title>
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
                        <div class="error" id="fnameErr"> </div>

                    </div>
                    <div class="grid-item">
                        <label for="lastName">Last Name :</label>
                        <input type="text" id="lastName" name="lastName" value="<?php echo $lname; ?>" placeholder="enter a lastName" />
                        <span class="error" id="lnameErr"></span>
                    </div>
                    <div class="grid-item">
                        <label for="email">Email :</label>
                        <input type="text" id="email" name="email"  value="<?php echo $email; ?>" autocomplete="on" placeholder="enter a email" onblur="checkEmail()">
                        <span class="error" id="emailErr">  <?php echo $emailErr; ?></span>
                    </div>
                    <div class="grid-item"> <label for="password">Password :</label>
                        <input type="password" id="password" name="password" value="<?php echo $password; ?>" placeholder="enter a password">
                        <span class="error" id="passwordErr"> </span>
                    </div>
                        <div class="grid-item">
                            <label for="dob">Date of Birth :</label>
                            <input type="date" id="dob" name="dob" value="<?php echo $dob; ?>">
                            <span class="error" id="dateErr"> </span>
                    </div>
                    <div class="grid-item center">
                        <span class="gender">Gender : </span>
                         
                            <input type="radio" name="gender"  id="male" value="male">
                            <label for="male">Male</label>
                            <input type="radio" name="gender" id="female" value="female">
                            <label for="female"> Female</label>
                            <input type="radio" name="gender" id="other" value="other">
                            <label for="other">Other</label>
                         
                        </div>
                        <span class="error" id="genderErr"> </span>
                    <div class="grid-item"> <label for="contactNumber">Contact Number :</label>
                        <input type="number" id="contactNumber" name="number" value="<?php echo $number ?>" >
                        <span class="error" id="numberErr"> </span>
                    </div>
                    <div class="grid-item">
                        <label for="profilePicture">Profile Picture :</label>
                        <input type="file" id="file" name="image">
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
        let email = document.getElementById("email").value.trim();
        let emailErr = document.getElementById("emailErr");      
                
        let xhttp = new XMLHttpRequest();
        xhttp.onload = function() { 
           
                if (this.responseText == "exist" ) {
                    emailErr.innerHTML = "Email already exists";
                    emailErr.style.padding ="0px";
                    return false;
                } else {
                    emailErr.innerHTML = "";
                    emailErr.style.padding ="7px 0px";
                }
        };
        xhttp.open("GET", "checkEmail.php?email="+email);
         
        xhttp.send();
    }

        function validateForm() {
      

    let fname = document.getElementById("firstName").value.trim();
    let lname = document.getElementById("lastName").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();
    let birth_date = document.getElementById("dob").value;
    let gender = document.querySelector('input[name="gender"]:checked');
    let number = document.getElementById("contactNumber").value.trim();
    let img =document.getElementById("file").value;

    // errors variables
    let fnameErr = document.getElementById("fnameErr");
    let lnameErr = document.getElementById("lnameErr");
    let emailErr = document.getElementById("emailErr");
    let passwordErr = document.getElementById("passwordErr");
    let dateErr = document.getElementById("dateErr");
    let genderErr = document.getElementById("genderErr");
    let numberErr = document.getElementById("numberErr");
    let fileErr = document.getElementById("imgErr");

            
    let nameRegex = /^[a-zA-Z ]+$/;
    let emailRegex = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/;
    let passRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[!@#$%^&*()\-+.]).{6,14}$/;
    let numberRegex = /^\d{10}$/;

    let flag = 1;

    // firstName validation  
        if (fname === "") {
            fnameErr.innerHTML = "First name is required";
            fnameErr.style.padding ="0px";
           
            flag = 0;
        } else if (!nameRegex.test(fname)) {
            fnameErr.innerHTML = "Only contain letters";
            flag = 0;
        } else {
            fnameErr.innerHTML = "";
            fnameErr.style.padding ="7px 0px";
             
        }


        // lastName validation
        if (lname === "") {
            lnameErr.innerHTML = "Last name is required";
            lnameErr.style.padding ="0px";
            flag = 0;
        } else if (!nameRegex.test(lname)) {
            lnameErr.innerHTML = "Only contain letters";
            flag = 0;
        } else {
            lnameErr.innerHTML = "";
            lnameErr.style.padding ="7px 0px";
        }

    // email validation 
    if (email === "") {
        emailErr.innerHTML = "Please enter email address";
        emailErr.style.padding ="0px";
        flag = 0;
    } else if (!emailRegex.test(email)) {
        emailErr.innerHTML = "Invalid email address. Enter a valid email address";
        emailErr.style.padding ="0px";
        flag = 0;
    } else {
        emailErr.innerHTML = "";
        emailErr.style.padding ="7px 0px";
    }

    // password validation
    if (password === "") {
        passwordErr.innerHTML = "Fill the password please!";
        passwordErr.style.padding ="0px";
        flag = 0;
    } else if (!passRegex.test(password)) {
        passwordErr.innerHTML = "Invalid password.";
        passwordErr.style.padding ="0px";
        flag = 0;
    } else {
        passwordErr.innerHTML = "";
        passwordErr.style.padding ="7px 0px";
    }

    // birth date validation
    if (birth_date === "") {
        dateErr.innerHTML = "Please select birth date";
        dateErr.style.padding ="0px";
        flag = 0;
    } else {
        dateErr.innerHTML = "";
        dateErr.style.padding ="7px 0px";
    }

    // gender validation 
    if (!gender) {
        genderErr.innerHTML = "Please select gender";
        genderErr.style.padding ="0px";
        flag = 0;
    } else {
        genderErr.innerHTML = "";
        genderErr.style.padding ="7px 0px";
    }

    // number validation
        if (number === "") {
            numberErr.innerHTML = "Please enter phone number.";
            numberErr.style.padding ="0px";
            flag = 0;
        } else if (!numberRegex.test(number)) {
            numberErr.innerHTML = "Please enter a valid 10-digit phone number.";
            flag = 0;
        } else {
            numberErr.innerHTML = "";
            numberErr.style.padding ="7px 0px";
        }

        if (!img) {   
           fileErr.innerHTML = "Please select an image file."; 
           fileErr.style.padding ="0px";
           flag = 0;
        }else if(img.size < ( 1024 * 1024 * 2))  {
            fileErr.innerHTML = "File must be smaller than 2MB";
            fileErr.style.padding ="7px 0px";
            flag = 0;
          } 

    if (flag == 0) {
      return false;  
    }else{

        return true;
    }
}

 
    </script>

</body>

</html>