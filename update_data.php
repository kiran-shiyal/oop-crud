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
$fnameErr = $lnameErr = $dobErr = $numberErr = $fileErr = "";
$fname = $lname = $number = $dob = $gender = $img = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update']))
{
    $fname = trim($_POST["firstName"]);
    if (empty($fname))
    {
        $fnameErr = "firstName is required";
    } else
    {
         // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z]*$/", $fname))
        {
            $fnameErr = "Only alphabets and white space are allowed";
        }else{
            $fnameErr ="";

        }
    }

    $lname = trim($_POST["lastName"]);
    if (empty($lname))
    {
        $lnameErr = "lastName is required";
    } else
    {
      
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $lname))
        {
            $lnameErr = "Only alphabets and white space are allowed";
        }
    }

    if (empty($_POST["dob"]))
    {
        $dobErr = "Birth date is required";
    } else
    {
        $dob = $_POST["dob"];
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
     if (!empty($img)) {
          
        $fileSize = $_FILES['image']['size'];
        $maxFileSize = $fileSize / 1024;
        $allowedExtensions = array('jpg', 'jpeg', 'png');
        $fileExtension = strtolower(pathinfo($img, PATHINFO_EXTENSION));
        
        if($maxFileSize  > (2 * 1024)){
            $fileErr = "File must be smaller than 2MB";
        }else {
            if (!in_array($fileExtension, $allowedExtensions)) {
                $fileErr = "Only JPG, JPEG and PNG files are allowed.";
            }
        }
    
    }
    if (empty($fnameErr) && empty($lnameErr) && empty($fileErr) && empty($dobErr) &&  empty($numberErr))
    {

        $gender = $_POST['gender'];
        $number = $_POST['number'];

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
       
        $result = $student->update_data($fname, $lname,$dob, $gender, $number, $folder, $id);


        if ($result)
        {

            echo "<script>alert('Data  updated successfully'); </script>";
            header("Refresh: 0; url = userlist_datatable.php");
        } else
        {
            echo "<script>alert('Data not updated');</script>";
        }
    }
}
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update data</title>
    <link rel="stylesheet" href="css/update_page.css">
    <style>
    
    </style>
</head>
<body>
<div class="container">
         <div class="apply-box">
             <h2>Update Form</h2>
             <form method="POST" action="#" enctype="multipart/form-data" onsubmit="return validateForm()">
                 <div class="grid-container">
                     <div class="grid-item">
                         <label for="firstName">First Name :</label>
                         <input type="text" id="firstName" name="firstName" value="<?php echo $res['first_name']; ?>">
                         <div class="error" id="fnameErr"><?php echo $fnameErr; ?> </div>

                     </div>
                     <div class="grid-item">
                         <label for="lastName">Last Name :</label>
                         <input type="text" id="lastName" name="lastName" value="<?php echo $res['last_name']; ?>">
                         <div class="error" id="lnameErr"><?php echo $lnameErr; ?></div>

                     </div>

                     <div class="grid-item">
                         <label for="dob">Date of Birth :</label>
                         <input type="date" id="dob" name="dob" value="<?php echo $res['dob']; ?>">
                         <div class="error" id="dateErr"><?php echo $dobErr; ?> </div>
                     </div>
                     <div class="grid-item center">

                         <span class="gender">Gender : </span>
                         <input type="radio" name="gender" id="male" value="male" <?php if ($res['gender'] == "male") {
                                                                                        echo "checked";
                                                                                    } ?>>
                         <label for="male"> Male</label>
                         <input type="radio" name="gender" id="female" value="female" <?php if ($res['gender'] == "female") {
                                                                                            echo "checked";
                                                                                        } ?>>
                         <label for="female"> Female</label>
                         <input type="radio" name="gender" id="other" value="other" <?php if ($res['gender'] == "other") {
                                                                                        echo "checked";
                                                                                    } ?>>
                         <label for="other">Other</label>
                        </div>
                        <div class="error" ></div>
                     
                     <div class="grid-item">
                         <label for="contactNumber">Contact Number :</label>
                         <input type="number" id="contactNumber" name="number" value="<?php echo $res['contact_number']; ?>">
                         <div class="error" id="numberErr"><?php echo $numberErr; ?></div>
                     </div>
                     <div class="grid-item">
                         <label for="profilePicture">Profile Picture:</label>
                         <input type="file" id="file" name="image" class="file">
                         <div class="error" id="imgErr"><?php echo $fileErr; ?> </div>
                     </div>
                     <div class="grid-item">
                         <button type="submit" name="update">Update</button>
                     </div>

                 </div>
             </form>
         </div>
     </div>
</body>
</html>