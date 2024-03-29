 <?php
include ("crud.php");
session_start();

// if (!isset($_SESSION['userName']) && empty($_SESSION['userName'])) {

//     header("Refresh: 0; url = login.php"); 
//     exit;
// }
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
        $fname =  $_POST["firstName"];
         $lname = $_POST["lastName"];
        $dob = $_POST["dob"];
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
            header("Refresh: 0; url = index.php");
        } else
        {
            echo "<script>alert('Data not updated');</script>";
        }
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

    <div class="container">
        <div class="apply-box">
            <h2>Update Form</h2>
            <form method="POST" action="#" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="grid-container">
                    <div class="grid-item">
                        <label for="firstName">First Name :</label>
                        <input type="text" id="firstName" name="firstName" value="<?php echo $res['first_name']; ?>">
                        <span class="error" id="fnameErr"> </span>

                    </div>
                    <div class="grid-item">
                        <label for="lastName">Last Name :</label>
                        <input type="text" id="lastName" name="lastName" value="<?php echo $res['last_name']; ?>">
                        <span class="error" id="lnameErr"></span>

                    </div>
                     
                     
                    <div class="grid-item">
                        <label for="dob">Date of Birth :</label>
                        <input type="date" id="dob" name="dob" value="<?php echo $res['dob']; ?>">
                        <span class="error" id="dateErr">  </span>
                    </div>
                <div class="grid-item">
                    <div class="center">
                    <label for="" class="gender">Gender : </label>
                        <input type="radio" name="gender" id="male" value="male" <?php if ($res['gender'] == "male")
                        {
                            echo "checked";
                        } ?>>
                        <label for="male"> Male</label>
                        <input type="radio" name="gender" id="female" value="female" <?php if ($res['gender'] == "female")
                        {
                            echo "checked";
                        } ?>>
                        <label for="female"> Female</label>
                        <input type="radio" name="gender" id="other" value="other" <?php if ($res['gender'] == "other")
                        {
                            echo "checked";
                        } ?>>
                        <label for="other">Other</label>
                    </div>
                    <span class="error" id="genderErr"></span>
                </div>    
                    <div class="grid-item">
                        <label for="contactNumber">Contact Number :</label>
                        <input type="number" id="contactNumber" name="number" value="<?php echo $res['contact_number']; ?>">
                        <span class="error" id="numberErr"></span>
                    </div>
                    <div class="grid-item">
                        <label for="profilePicture">Profile Picture:</label>
                        <input type="File" id="profilePicture" name="image" class="file">
                         
                    </div>
                    <div class="grid-item"> 
                    <button type="submit" name="update">Update</button>
                    </div>
                     
                    </div>
            </form>
        </div>
    </div>
    <script>
  function validateForm() {
            let fname = document.getElementById("firstName").value;
            let lname = document.getElementById("lastName").value;
            
          
            let birth_date = document.getElementById("dob").value;
            let gender = document.querySelector('input[name="gender"]:checked');
            let number = document.getElementById("contactNumber").value;

            // errors variables

            let fnameErr = document.getElementById("fnameErr");
            let lnameErr = document.getElementById("lnameErr");
          
            let dateErr = document.getElementById("dateErr");
            let genderErr = document.getElementById("genderErr");
            let numberErr = document.getElementById("numberErr");

            let nameRegex = /^[a-zA-Z ]+$/;
            //firstName validation
            if (fname.trim() == "") {
                 
                fnameErr.innerHTML = "first name is required";
                return false;
            } else if (!nameRegex.test(fname)) {

                fnameErr.innerHTML = "only contain letters";
                return false;
            } else {
                fnameErr.innerHTML = "";

            }

            let lnameRegex = /^[a-zA-Z ]+$/;
            //lastName validation
            if (lname.trim() == "") {
                lnameErr.innerHTML = "last name is required";
                return false;
            } else
            if (!lnameRegex.test(lname)) {

                lnameErr.innerHTML = "only contain letters";
                return false;
            } else {

                lnameErr.innerHTML = "";

            }

            // birth date validation
            if (birth_date == "") {
                dateErr.innerHTML = "please select birth date";
                return false;
            } else {
                dateErr.innerHTML = "";
            }

            // gender validation 
            if (!gender) {
                genderErr.innerHTML = "please select gender";
                return false;
            } else {
                genderErr.innerHTML = "";
            }

            // number validation
            let numberRegex = /^\d{10}$/;

            if (number == "") {

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