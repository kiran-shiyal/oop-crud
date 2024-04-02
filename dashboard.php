<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark rounded" id ="navbar">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>