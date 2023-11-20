<?php
session_start();
require_once 'config.php';
require_once 'lib/index.php';
require 'templates/head.php';

$dbh = connectDB();


    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get car_id, departureDate, and returnDate from the form submission
        $carId = filter_input(INPUT_POST, 'car_id', FILTER_VALIDATE_INT);

        // Retrieve values from sessions
        $departureDate = isset($_SESSION['departureDate']) ? $_SESSION['departureDate'] : null;
        $returnDate = isset($_SESSION['returnDate']) ? $_SESSION['returnDate'] : null;
       var_dump($carId);

       var_dump($_SESSION['departureDate']);
       var_dump($_SESSION['returnDate']);

        // Validate inputs
        if ($carId === false || empty($departureDate) || empty($returnDate)) {
            echo "Invalid input data.";
            exit();
        }

        // Insert into the availability table
        $insertStmt = $dbh->prepare("INSERT INTO availability (car_id, start_date, end_date, is_available) VALUES (:car_id, :start_date, :end_date, 1)");
        $insertStmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
        $insertStmt->bindParam(':start_date', $departureDate);
        $insertStmt->bindParam(':end_date', $returnDate);

        if ($insertStmt->execute()) {
            // Update the is_available column in the cars table to 1 (not available)
            $updateStmt = $dbh->prepare("UPDATE cars SET is_available = 1 WHERE car_id = :car_id");
            $updateStmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
            $updateStmt->execute();

            echo "Booking information inserted successfully.";
        } else {
            echo "Error inserting booking information.";
        }
    


        $stmt = $dbh->prepare("SELECT rent_total_price FROM cars WHERE car_id = :car_id");
        $stmt->bindParam(':car_id', $carId);
        $stmt->execute();
        $car = $stmt->fetch(PDO::FETCH_ASSOC);
        var_dump($car);

        // Insert into the reservations table
        $insertStmt = $dbh->prepare("
            INSERT INTO reservations (user_id, car_id, pickup_date, return_date, price_total, status)
            VALUES (:user_id, :car_id, :pickup_date, :return_date, :price_total, 'Pending')
        ");
        $insertStmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $insertStmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
        $insertStmt->bindParam(':pickup_date', $departureDate);
        $insertStmt->bindParam(':return_date', $returnDate);
        $insertStmt->bindParam(':price_total', $car, PDO::PARAM_INT);

        if ($insertStmt->execute()) {
            echo "Reservation information inserted successfully.";
        } else {
            echo "Error inserting reservation information.";
        }




    }

?>



<?php
require 'templates/footer.php';
?>


