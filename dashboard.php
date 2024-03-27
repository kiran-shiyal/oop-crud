<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        #navbar {
            background-color: #333;
            color: #fff;
            padding: 10px;
            display: flex;
            border: 1px solid;
            border-radius: 12px;
        }

        #navbar h2 {
            margin: 0;
            flex-grow: 1;
        }

        #user-info {
            display: flex;
            align-items: center;
            margin-right: 10px;
        }

        #logout-link {
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            background-color: red;
            border-radius: 5px;
        }

        #logout-link:hover {

            background-color: green;
        }

        .add a {

            text-decoration: none;
            padding: 5px;
            border: 2px solid black;
            background-color: #4CAF50;
            border-radius: 10px;
            color: white;

        }

     
    </style>
</head>

<body>

    <div id="navbar">
        <h2>Dashboard</h2>
        <div id="user-info">
            <?php
            session_start();

            echo  $_SESSION['userName'];

            ?>
        </div>
        <a href="logout.php" id="logout-link">Logout</a>
    </div>

   
    <div id="content" style="text-align: center;">
     

</body>

</html>