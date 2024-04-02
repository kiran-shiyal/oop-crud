<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/index.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  
</head>

<body>

<nav class="navbar navbar-expand-sm navbar-dark bg-dark" id ="navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="javascript:void(0)">Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav me-auto">
        
      </ul>
      <form class="d-flex">
      <div class = "info">
      <?php
            session_start();

            echo  $_SESSION['userName'];

            ?>
            </div>
         <a href="logout.php" id="logout-link">Logout</a>  
      </form>
    </div>
  </div>
</nav>
 
<div class="container mt-3">
<div class="d-flex justify-content-between align-items-center">  
    <h2>Students Details</h2>
       <a href="register.php" class="add">Add student</a></div>
   
    <div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Id</th>
                <th>FullName</th>
                <th>Email</th>
                <th>Birth Date</th>
                <th>Gender</th>
                <th> Number</th>
                <th> Picture</th>
                <th> Action </th>
            </tr>
        </thead>
        <tbody>
            <?php

            include("crud.php");

            session_start();

            // echo var_dump(!isset($_SESSION['userName']));
            // echo var_dump(empty($_SESSION['userName']));
            // exit;
            
            if (!isset($_SESSION['userName']) && empty($_SESSION['userName'])) {
                 
                header("Refresh: 0; url = login.php"); 
                exit;
            }

            $student = new Database();

            $result = $student->displayData();
            if (mysqli_num_rows($result) > 0) {

                while($res = mysqli_fetch_assoc($result)) {

                    //formetinge date
                    $date = date_create(($res['dob']));
                    $formatted_date = date_format($date, "d/m/Y");
            ?>
                    <tr>
                        <td> <?php echo $res['id']; ?></td>
                        <td> <?php echo $res['first_name'] . " " . $res['last_name']; ?></td>
                        <td> <?php echo $res['email']; ?> </td>
                        <td> <?php echo  $formatted_date ?> </td>
                        <td> <?php echo $res['gender']; ?> </td>
                        <td> <?php echo $res['contact_number'] ?> </td>
                        <td> <img src="<?php echo $res['profile_picture'] ?>" width="80" height="60"> </td>
                        <td>
                            <a href="update.php?id=<?php echo $res['id']; ?>" class="edit">Edit</a>
                            <a href="delete.php?id=<?php echo $res['id']; ?>" onclick="return confirm('Are you sure want to delete?'); " class="delete">Delete</a>
                        </td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
 </div>
 </div>
</body>

</html>