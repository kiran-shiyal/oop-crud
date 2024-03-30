 <?php
 session_start();

 if (isset($_SESSION['userName']) && !empty($_SESSION['userName']))
 {

     header("Refresh: 0; url = index.php");
     exit;
 }
 $emailErr = $passwordErr = "";
 $email = $password = "";
 if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]))
 {
    
     //  $conn = mysqli_connect("localhost","root","root"," student");
     // $email = $_POST["email"];
     // $password = $_POST["password"];
     if (empty($_POST["email"]))
     {
         $emailErr = "Email is required";
     } else
     {
         $email = $_POST["email"];
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

         $password = $_POST["password"];
         if (strlen($password) < 8)
         {
             $passwordErr = "Password too short! ";
         }
     }

     if (empty($emailErr) && empty($passwordErr))
     {
         // $sql ="SELECT * FROM users WHERE email = '$email' && password = '$password'";
         // $result = mysqli_query($conn, $sql);
         include ("crud.php");
         $student = new Database();
         $result = $student->login($email);

         if (mysqli_num_rows($result) == true)
         {
             $res = mysqli_fetch_assoc($result);
             $hashedPassword = $res['password'];

            
             if (password_verify($password, $hashedPassword))
             {
                 $_SESSION['userName'] = $res['first_name'] . " " . $res['last_name'];
                 echo "<script>alert('login successful');</script>";
                 header("Refresh: 0; url = index.php");
             } else
             {

                 $passwordErr = "Invalid password";
             }
         } else
         {

             $emailErr = "Invalid email";
             //  echo "<script>alert('Login failed. Invalid username or password.');</script>";
         }
     }
 }
 ?>



 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Login</title>
     <link rel="stylesheet" href="css/login.css">
 </head>

 <body>

        <div class="container">
           <div class="form">
     <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
         <h2>Login</h2>
           <div class="grid-container">

         
          <div class="grid-item">
          <label for="username">Email :</label>
         <input type="email" name="email" id="username" placeholder="enter a email" value="<?php echo $email; ?>">
        </div>
        <span class="error"> <?php echo $emailErr; ?></span>
          <div class="grid-item">
          <label for="password">Password :</label>
         <input type="password" name="password" id="password" placeholder="enter a password" value="<?php echo $password ?>"   >

        </div>
        <span class="error"> <?php echo $passwordErr; ?></span>
         <button type="submit" name="submit">Login</button>
         <a href="register.php">Click here to Register.</a>
         </div>
     </form>
     </div> 
     </div>
 </body>

 </html>