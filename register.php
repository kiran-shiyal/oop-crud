<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("crud.php");

$student = new Database();

$emailErr = "";
$fname = $lname = $email= $number = $password = $dob = $gender = $img = $img_temp = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {


        $fname = $_POST["firstName"];
        $lname =  $_POST["lastName"];
        $email =  $_POST["email"];
        $password =  $_POST["password"];
        $gender = $_POST["gender"];
        $img = $_FILES['image']['name'];
        $img_temp = $_FILES['image']['tmp_name'];
        $number = $_POST['number'];
        $folder = "images/" . $img;
        $dob = $_POST["dob"];
        $myDate = new DateTime($dob);

        $birth_date = $myDate->format('Y-m-d');
        move_uploaded_file($img_temp, $folder);

        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
         

      
        
      
        $result = $student->insert($fname, $lname, $email, $hashPassword, $birth_date, $gender, $number, $folder);

        if ($result) {
            echo "<script>alert('Data inserted successfully'); </script>";
            header("Refresh: 0; url = login.php");
        } else {
            echo "<script>alert('Data not inserted');</script>";
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
                    <div class="grid-item">
                        <div class="center">
                            <label for="" class="gender">Gender : </label>
                            <input type="radio" name="gender"  id="male" value="male">
                            <label for="male"> Male</label>
                            <input type="radio" name="gender" id="female" value="female">
                            <label for="female"> Female</label>
                            <input type="radio" name="gender" id="other" value="other">
                            <label for="other">Other</label>
                         </div>
                        <span class="error" id="genderErr"> </span>
                    </div>
                    <div class="grid-item"> <label for="contactNumber">Contact Number :</label>
                        <input type="number" id="contactNumber" name="number" value="<?php echo $number ?>" >
                        <span class="error" id="numberErr"> </span>
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
        function validateForm() {
      

    let fname = document.getElementById("firstName").value.trim();
    let lname = document.getElementById("lastName").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();
    let birth_date = document.getElementById("dob").value;
    let gender = document.querySelector('input[name="gender"]:checked');
    let number = document.getElementById("contactNumber").value.trim();

    // errors variables
    let fnameErr = document.getElementById("fnameErr");
    let lnameErr = document.getElementById("lnameErr");
    let emailErr = document.getElementById("emailErr");
    let passwordErr = document.getElementById("passwordErr");
    let dateErr = document.getElementById("dateErr");
    let genderErr = document.getElementById("genderErr");
    let numberErr = document.getElementById("numberErr");

            
    let nameRegex = /^[a-zA-Z ]+$/;
    let emailRegex = /^\s*([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})\s*$/;
    let passRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[!@#$%^&*()\-+.]).{6,14}$/;
    let numberRegex = /^\d{10}$/;

    // firstName validation  
    if (fname === "") {
        fnameErr.innerHTML = "First name is required";
        return false;
    } else if (!nameRegex.test(fname)) {
        fnameErr.innerHTML = "Only contain letters";
        return false;
    } else {
        fnameErr.innerHTML = "";
    }

    // lastName validation
    if (lname === "") {
        lnameErr.innerHTML = "Last name is required";
        return false;
    } else if (!nameRegex.test(lname)) {
        lnameErr.innerHTML = "Only contain letters";
        return false;
    } else {
        lnameErr.innerHTML = "";
    }

    // email validation 
    if (email === "") {
        emailErr.innerHTML = "Please enter email address";
        return false;
    } else if (!emailRegex.test(email)) {
        emailErr.innerHTML = "Invalid email address. Enter a valid email address";
        return false;
    } else {
        emailErr.innerHTML = "";
    }

    // password validation
    if (password === "") {
        passwordErr.innerHTML = "Fill the password please!";
        return false;
    } else if (!passRegex.test(password)) {
        passwordErr.innerHTML = "Invalid password.";
        return false;
    } else {
        passwordErr.innerHTML = "";
    }

    // birth date validation
    if (birth_date === "") {
        dateErr.innerHTML = "Please select birth date";
        return false;
    } else {
        dateErr.innerHTML = "";
    }

    // gender validation 
    if (!gender) {
        genderErr.innerHTML = "Please select gender";
        return false;
    } else {
        genderErr.innerHTML = "";
    }

    // number validation
    if (number === "") {
        numberErr.innerHTML = "Please enter phone number.";
        return false;
    } else if (!numberRegex.test(number)) {
        numberErr.innerHTML = "Please enter a valid 10-digit phone number.";
        return false;
    } else {
        numberErr.innerHTML = "";
    }

    return true;
}

 
    </script>

</body>

</html>