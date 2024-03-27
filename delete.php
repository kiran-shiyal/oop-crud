<?php 

include("crud.php");

$student = new Database();

$id = $_GET['id'];

$result = $student->delete($id);

if ($result) {
     
    header("Refresh: 0; url = index.php");   

}


 