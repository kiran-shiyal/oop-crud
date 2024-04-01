<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
      <form class="d-flex align-items-center ">
      <div class = "text-white mx-3">
      <?php
            session_start();

            echo  $_SESSION['userName'];

            ?>
            </div>
         <a href="logout.php" class="btn btn-danger">Logout</a>  
      </form>
    </div>
  </div>
</nav>
<div class="container d-flex justify-content-between align-items-center" style="margin-top:40px;" >
        <h2>Users Details</h2>
       <a href="register.php" class="btn btn-success text-center">Add student</a>
    </div>
    <div class="container container-sm container-md ">
   
 <div class="table-responsive">
    <table  class="table table-bordered" id="data_table">
        <thead>

            <tr>
                <th>Id</th>
                <th>FullName</th>
                <th>Email</th>
                <th>Birth Date</th>
                <th>Gender</th>
                <th>Number</th>
                <th>Picture</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php

            include("crud.php");

            session_start();

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
                            <a href="update.php?id=<?php echo $res['id']; ?>" class="btn btn-primary">Edit</a>
                            <a href="delete.php?id=<?php echo $res['id']; ?>" onclick="return confirm('Are you sure want to delete?'); " class="btn btn-danger">Delete</a>
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
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
 <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
 <script src="//cdn.datatables.net/2.0.3/js/dataTables.min.js"></script>
 <script>
$(document).ready(function () {
    $("#data_table").dataTable();
});
    
 </script>
</body>
</html>