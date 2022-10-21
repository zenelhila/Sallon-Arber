<?php
$title = 'Delete Appointment';
require_once 'connection.php';
require_once 'databaseFunctions.php';

if (isset($_GET['appointment_id'])) {
    $appointment_id= $_GET['appointment_id'];
    header("Location: ../appointments");
    if (deleteApp($appointment_id)) {
        echo '<div class="alert alert-success" role="alert">
                Appointment deleted!</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">
                Appointment deletion failed!</div>';
    }
  
}


?>