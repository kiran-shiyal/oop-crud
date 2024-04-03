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
    $fname = $lname  = $dob = $gender = $number = $img =  "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $fname = trim($_POST["firstName"]);
        $lname = trim($_POST["lastName"]);
        $dob = trim($_POST["dob"]);
        $gender = $_POST['gender'];
        $number = trim($_POST['number']);

        $img = $_FILES['image']['name'];
        $img_temp = $_FILES['image']['tmp_name'];
        if ($img == "") {
            $folder = $res['profile_picture'];
        } else {
            // No new file uploaded, retain the existing file information
            // Fetch existing file path from the database or wherever it is stored
            // Replace with actual retrieval of existing file path
            $folder = "images/" . $img;
        }


        move_uploaded_file($img_temp, $folder);


        $result = $student->update_data($fname, $lname, $dob, $gender, $number, $folder, $id);


        if ($result) {

            echo "<script>alert('Data  updated successfully'); </script>";
            header("Refresh: 0; url = userlist_datatable.php");
        } else {
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
     <title>update data</title>
     <style>
        span.error{
            height: 15px;
        }
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
                         <span class="error" id="dateErr"> </span>
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
                     <span class="error" id="genderErr"></span>
                     <div class="grid-item">
                         <label for="contactNumber">Contact Number :</label>
                         <input type="number" id="contactNumber" name="number" value="<?php echo $res['contact_number']; ?>">
                         <span class="error" id="numberErr"></span>
                     </div>
                     <div class="grid-item">
                         <label for="profilePicture">Profile Picture:</label>
                         <input type="file" id="file" name="image" class="file">
                         <span class="error" id="imgErr"> </span>
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

             let fname = document.getElementById("firstName").value.trim();
             let lname = document.getElementById("lastName").value.trim();


             let birth_date = document.getElementById("dob").value.trim();
             let gender = document.querySelector('input[name="gender"]:checked');
             let number = document.getElementById("contactNumber").value.trim();
             let img_path =document.getElementById("file");

             // errors variables

             let fnameErr = document.getElementById("fnameErr");
             let lnameErr = document.getElementById("lnameErr");

             let dateErr = document.getElementById("dateErr");
             let genderErr = document.getElementById("genderErr");
             let numberErr = document.getElementById("numberErr");
             let fileErr = document.getElementById("imgErr");
             let nameRegex = /^[a-zA-Z ]+$/;
   
             let flag = 1;
             //firstName validation
             if (fname === "") {
                 fnameErr.innerHTML = "First name is required";
                 fnameErr.style.padding = "0px";
                 flag = 0;
             } else if (!nameRegex.test(fname)) {
                 fnameErr.innerHTML = "Only contain letters";
                 flag = 0;
             } else {
                 fnameErr.innerHTML = "";
                 fnameErr.style.padding = "7px 0px";
             }
             
             let lnameRegex = /^[a-zA-Z ]+$/;
             //lastName validation
             if (lname === "") {
                 lnameErr.innerHTML = "Last name is required";
                 lnameErr.style.padding = "0px";
                
                 flag = 0;
             } else if (!nameRegex.test(lname)) {
                 lnameErr.innerHTML = "Only contain letters";
                 flag = 0;
             } else {
                 lnameErr.innerHTML = "";
                 lnameErr.style.padding = "7px 0px";
             }

             // birth date validation
             if (birth_date === "") {
                 dateErr.innerHTML = "Please select birth date";
                 dateErr.style.padding = "0px";
                 flag = 0;
             } else {
                 dateErr.innerHTML = "";
                 dateErr.style.padding = "7px 0px";
             }

             // gender validation 
             if (!gender) {
                 genderErr.innerHTML = "Please select gender";
                 genderErr.style.padding = "0px";
                 flag = 0;
             } else {
                 genderErr.innerHTML = "";
                 genderErr.style.padding = "7px 0px";
             }

             // number validation
             let numberRegex = /^\d{10}$/;

             if (number === "") {
                 numberErr.innerHTML = "Please enter phone number.";
                 numberErr.style.padding = "0px";
                 flag = 0;
             } else if (!numberRegex.test(number)) {
                 numberErr.innerHTML = "Please enter a valid 10-digit phone number.";
                 flag = 0;
             } else {
                 numberErr.innerHTML = "";
                 numberErr.style.padding = "7px 0px";
             }

            
              if (img_path.value) {
                
                let img = img_path.files[0].size;
                 // Check file extension
                 let allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
                let img_name = img_path.files[0].name;
                let img_size = img / 1024;
                if (img_size > (1024 * 2)) {
                    fileErr.innerHTML = "File must be smaller than 2MB";
                    flag = 0;
                } else if (!allowedExtensions.exec(img_name)) {

                        fileErr.innerHTML = "Allowed file types are .jpg, .jpeg, and .png";
                        fileErr.style.padding = "0px";
                        flag = 0;
                    } else {
                        fileErr.innerHTML = "";
                        fileErr.style.padding = "7px 0px";
                    }
                }

         

             if (flag == 0) {
               
                
                 return false;
             } else {

                 return true;
             }
         }
     </script>
 </body>

 </html>