<?php
include("crud.php");
session_start();

if (!isset($_SESSION['userName']) && empty($_SESSION['userName'])) {
     
    header("Refresh: 0; url = login.php"); 
    exit;
}
$student = new Database();
$id = $_GET['id'];

// fetch data 
$result = $student->update($id);
$res = mysqli_fetch_assoc($result);
 
// update data 
$fnameErr = $lnameErr = $emailErr = $passwordErr = $dobErr = "";
$fname = $lname = $email = $password = $dob = $gender = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update']))
{


    if (empty($_POST["firstName"]))
    {
        $fnameErr = "firstName is required";
    } else
    {


        $fname = input_data($_POST["firstName"]);

        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $fname))
        {
            $fnameErr = "Only alphabets and white space are allowed";
        }
    }

    if (empty($_POST["lastName"]))
    {
        $lnameErr = "lastName is required";
    } else
    {
        $lname = input_data($_POST["lastName"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $lname))
        {
            $lnameErr = "Only alphabets and white space are allowed";
        }
    }

    if (empty($_POST["email"]))
    {
        $emailErr = "Email is required";
    } else
    {
        $email = input_data($_POST["email"]);
        // check that the e-mail address is well-formed  
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $emailErr = "Invalid email format";
        }
    }


    if (empty($_POST["password"]))
    {
        $passwordErr = "password is required";
    } else
    {

        $password = input_data($_POST["password"]);
        if (strlen($password) < 8)
        {
            $passwordErr = "Password too short! ";
        }
    }

    if (empty($_POST["dob"]))
    {
        $dobErr = "Birth date is required";
    } else
    {
        $dob = $_POST["dob"];
    }


    if (empty($fnameErr) && empty($lnameErr) && empty($emailErr) && empty($passwordErr) && empty($dobErr))
    {

        $gender = $_POST['gender'];
        $number = input_data($_POST['number']);

        $img = $_FILES['image']['name'];
        $img_temp = $_FILES['image']['tmp_name'];
        if ($img == "")
        {

            $folder = $res['profile_picture'];
        } else
        {
            // No new file uploaded, retain the existing file information
            // Fetch existing file path from the database or wherever it is stored
            // Replace with actual retrieval of existing file path
            $folder = "images/" . $img;
        }


        move_uploaded_file($img_temp, $folder);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $result = $student->update_data($fname, $lname, $email, $hashedPassword, $dob, $gender, $number, $folder, $id);


        if ($result)
        {

            echo "<script>alert('Data  updated successfully'); </script>";
            header("Refresh: 0; url = index.php");
        } else
        {
            echo "<script>alert('Data not updated');</script>";
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
    <link rel="stylesheet" href="css/update_page.css">
    <title> update data</title>

</head>

<body>

    <form method="POST" action="#" enctype="multipart/form-data">
        <label for="firstName">First Name :</label>
        <input type="text" id="firstName" name="firstName" value="<?php echo $res['first_name']; ?>">
        <span class="error"> <?php echo $fnameErr; ?></span>

        <label for="lastName">Last Name :</label>
        <input type="text" id="lastName" name="lastName" value="<?php echo $res['last_name']; ?>">
        <span class="error"> <?php echo $lnameErr; ?></span>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?php echo $res['email']; ?>">
        <span class="error"> <?php echo $emailErr; ?></span>

        <label for="password">Password :</label>
        <input type="text" id="password" name="password" value="<?php echo  $res['password']; ?>">
        <span class="error"> <?php echo $passwordErr; ?></span>

        <label for="dob">Date of Birth :</label>
        <input type="date" id="dob" name="dob" value="<?php echo $res['dob']; ?>">
        <span class="error"> <?php echo $dobErr; ?></span>


        <label>Gender :</label>
        <div class="gender-container">
            <label><input type="radio" name="gender" value="male" required <?php if ($res['gender'] == "male")
            {
                echo "checked";
            } ?>> Male</label>

            <label><input type="radio" name="gender" value="female" <?php if ($res['gender'] == "female")
            {
                echo "checked";
            } ?>> Female</label>

            <label><input type="radio" name="gender" value="other" <?php if ($res['gender'] == "other")
            {
                echo "checked";
            } ?>> Other</label>
        </div>

        <label for="contactNumber">Contact Number :</label>
        <input type="number" id="contactNumber" name="number" value="<?php echo $res['contact_number']; ?>">
        <label for="profilePicture">Profile Picture:</label>
        <div class="image-container">


            <img src='<?php echo $res['profile_picture'] ?>' width='100' height='70'>
            <input type="File" id="profilePicture" name="image" class="file">
        </div>
        <button type="submit" name="update">Update</button>
    </form>
</body>

</html>
<?php
