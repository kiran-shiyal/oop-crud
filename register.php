<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("crud.php");

$student = new Database();

$fnameErr = $lnameErr = $emailErr = $passwordErr = $dobErr = "";
$fname = $lname = $email = $password = $dob = $gender = "";

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


    if (empty($fnameErr) && empty($lnameErr) && empty($emailErr) && empty($passwordErr) && empty($dobErr)) {

        $gender = $_POST['gender'];
        $number = input_data($_POST['number']);

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
    <link rel="stylesheet" href="css/register.css">
    <title>User Registration</title>
</head>

<body>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">


        <label for="firstName">First Name :</label>
        <input type="text" id="firstName" name="firstName" value="<?php echo $fname; ?>" placeholder="enter a firstName">
        <span class="error"> <?php echo $fnameErr; ?></span>

        <label for="lastName">Last Name :</label>
        <input type="text" id="lastName" name="lastName" value="<?php echo $lname; ?>" placeholder="enter a lastName">
        <span class="error"> <?php echo $lnameErr; ?></span>


        <label for="email">Email :</label>
        <input type="text" id="email" name="email" value="<?php echo $email; ?>" autocomplete="off" placeholder="enter a email">
        <span class="error"> <?php echo $emailErr; ?></span>
        <label for="password">Password :</label>

        <input type="password" id="password" name="password" value="<?php echo $password; ?>" placeholder="enter a password">


        <span class="error"> <?php echo $passwordErr; ?></span>



        <label for="dob">Date of Birth :</label>
        <input type="date" id="dob" name="dob" value="<?php echo $dob; ?>">
        <span class="error"> <?php echo $dobErr; ?></span>
        <label>Gender :</label>
        <div class="gender-container">
            <label><input type="radio" name="gender" value="male"> Male</label>
            <label><input type="radio" name="gender" value="female"> Female</label>
            <label><input type="radio" name="gender" value="other"> Other</label>
        </div>

        <label for="contactNumber">Contact Number :</label>
        <input type="number" id="contactNumber" name="number" placeholder="enter a number">

        <label for="profilePicture">Profile Picture :</label>
        <input type="file" id="profilePicture" name="image">
        <button type="submit" name="submit">submit</button>


        <a href="login.php">Click here to Login.</a>
    </form>



</body>

</html>