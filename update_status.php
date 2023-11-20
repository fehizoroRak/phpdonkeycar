<?php
session_start();
require_once 'config.php';
require_once 'lib/index.php';
require 'templates/head.php';

$dbh = connectDB();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
   if (isset($_POST['id']) && isset($_POST['status'])) {
       $reservation_id = $_POST['id'];
       $new_status = $_POST['status'];

   
       $allowed_statuses = ['confirmed', 'cancelled'];

       if (in_array($new_status, $allowed_statuses)) {
           
   
           $stmt = $dbh->prepare("UPDATE reservations SET status = :status WHERE reservation_id = :id");
           $stmt->bindParam(':status', $new_status);
           $stmt->bindParam(':id', $reservation_id);

           if ($stmt->execute()) {
               echo "Status updated successfully!";
               header('Location:my_reservations.php');
           } else {
               echo "Error updating status.";
           }
       } else {
           echo "Invalid status.";
       }
   } else {
       echo "Invalid parameters.";
   }
} else {
   echo "Invalid request method.";
}



require 'templates/footer.php';
?>