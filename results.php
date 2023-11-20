<?php
session_start();
require_once 'config.php';
require_once 'lib/index.php';
require 'templates/head.php';

$dbh = connectDB();
?>

<div class="container">

    <style>
        a {
            text-decoration: none;
        }

        button {
            background-color: #62aaa7;
            color: #f6f6f6;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>





    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="styles.css">
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background-color: #fff;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 1200px;

                margin: 0 auto;
                padding: 20px;
            }

            .center {
                text-align: center;
                padding: 20px;
            }



            .aside {
                float: left;
                width: 20%;
                padding: 20px;
                background-color: #f6f6f6;
                color: #363636;
            }

            .car-card {
                width: 18rem;
                border: 1px solid #ccc;
                border-radius: 8px;
                margin: 10px;
                padding: 15px;
                background-color: #fff;
            }

            .car-card-details {
                width: 28rem;
                border: 1px solid #ccc;
                border-radius: 8px;
                margin: 0 auto;
                line-height: 12px;
                padding: 15px;
                background-color: #fff;
            }

            .car-image {
                max-width: 100%;
                height: auto;
                border-radius: 8px;
            }

            .rent-button {
                background-color: #62aaa7;
                color: #f6f6f6;
                padding: 10px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .logout {
                background-color: #b82b21;
                color: #f6f6f6;
                padding: 10px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
        </style>
        <title>Car Rental</title>
    </head>
    <?php
    if (isset($_SESSION['username'])) {
        echo '<p style="text-align:center; font-size: 1.8em;">Welcome <span style="font-size:1.3em; color:#b82b21; font-weight:bold;">' . htmlspecialchars($_SESSION['username']) . '</span>!</p>';

        //echo '<p style="text-align:center;"><a class="logout" href="logout.php">Logout</a></p>';
    }
    ?>





    <?php

    //var_dump($_SESSION);
    //if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    //header("Location: login.php");
    //exit();
    //}





    if ($_SERVER['REQUEST_METHOD'] === 'POST') {


        $departureTown = $_POST['departureTown'];
        $returnTown = $_POST['returnTown'];


        $departureDate = $_POST['departureDate'];
        $returnDate = $_POST['returnDate'];

        // Calculate the number of days between departure and return dates
        $nbr_days = date_diff(date_create($departureDate), date_create($returnDate))->format("%a");

        $sql = "INSERT INTO rental_date (departuredate, returndate, nbr_days) VALUES (:departureDate, :returnDate, :nbr_days)";
        $stmt = $dbh->prepare($sql);

        $stmt->bindParam(':departureDate', $departureDate);
        $stmt->bindParam(':returnDate', $returnDate);
        $stmt->bindParam(':nbr_days', $nbr_days);
        $stmt->execute();

        $lastInsertId = $dbh->lastInsertId();

        // Output the last inserted ID and nbr_days
        //echo "Last Inserted ID: $lastInsertId<br>";

        //echo "Number of Days: $nbr_days";


        if (isset($_SESSION)) {
            $_SESSION['departureDate'] = $departureDate;
            $_SESSION['returnDate'] = $returnDate;
        }


        // Display the values
        //echo '<div>';

        //echo '<p>Departure City: ' . htmlspecialchars($departureTown) . '</p>';
        //echo '<p>Return City: ' . htmlspecialchars($returnTown) . '</p>';
        // echo '<p>Departure Date: ' . htmlspecialchars($departureDate) . '</p>';
        //echo '<p>Return Date: ' . htmlspecialchars($returnDate) . '</p>';
        //echo '</div>';
    }


    $stmt = $dbh->prepare("SELECT c.* FROM cars c 
    WHERE c.is_available = 0 
   ");

    $stmt->execute();

    // $stmt = $dbh->prepare("
    // SELECT c.*
    // FROM cars c
    // LEFT JOIN availability a ON c.car_id = a.car_id
    // WHERE c.is_available = 0 OR (a.is_available = 0 AND CURDATE() BETWEEN a.start_date AND a.end_date)
    // ");
    // $stmt->bindParam(':start_date', $departureDate, PDO::PARAM_STR);
    // $stmt->bindParam(':end_date', $returnDate, PDO::PARAM_STR);
    // $stmt->execute();

    $availableCars = $stmt->fetchAll();

    // Fetch cars from the database
    //$stmt = $pdo->query("SELECT car_id, make, model, year, rental_price, image_url FROM cars");
    //$cars = $stmt->fetchAll();

    // Display the fetched data

    ?>
    <div class="container center">
        <div class="car-card-details">
            <h2>Rent details</h2>
            <p>Number of Days: <?= $nbr_days ?></p>

            <?php
        $sql = "SELECT address, city, country FROM citydeparture WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $departureTown, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

            ?>

            <p>Departure City: <?php echo  $result['address'].', '.$result['city'].', '.$result['country']?></p>

            <?php
        $sql = "SELECT address, city, country FROM cityreturn WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $returnTown, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

            ?>

            <p>Return City: <?php echo  $result['address'].', '.$result['city'].', '.$result['country']?></p>
            <p>Departure Date: <?php echo  $departureDate ?></p>
            <p>Return Date: <?php echo  $returnDate ?></p>
        </div>
    </div>



    <div class="row row-cols-1 row-cols-md-3 g-4">

        <aside class="aside">
            <h3>Filters</h3>

            <h5>Type of Vehicle</h5>
            <ul>
                <li>SUV</li>
                <li>Break</li>
                <li>Berline</li>
                <!-- Add more filters as needed -->
            </ul>

            <h5>Number of Seats</h5>
            <ul>
                <li>5 seats</li>
                <li>7 seats</li>
                <li>4 seats</li>
                <!-- Add more filters as needed -->
            </ul>

            <h5>Gearbox</h5>
            <ul>
                <li>Automatic</li>
                <li>Manual</li>

                <!-- Add more filters as needed -->
            </ul>
        </aside>




        <?php foreach ($availableCars as $car) { ?>

            <div class="car-card">
                <!--$car['image_url'] -->
                <img src="templates/white-car.jpg" alt="Toyota Camry" class="car-image">
                <h3><?= $car['make'] ?></h3>
                <h3><?= $car['model'] ?></h3>
                <p><?= $car['year'] ?></p>
                <p>Rental Price: €<?= $car['rental_price'] ?>/day</p>
                <?php
                $total_price = $nbr_days *  $car['rental_price'];
                ?>
                <p>Rental Total Price: € <?= $total_price ?></p>
                <p>Number of days between departure and return: <?= $nbr_days ?> days</p>

                <?php
                if (!isset($_SESSION['username'])) {

                    echo '<a href="register.php" class="rent-button">Rent Now</a>';
                } else {

                    echo '<a class="rent-button" href="car_details.php?car_id=' . $car['car_id'] . '&total_price=' . $total_price . '">Rent Now</a>';
                }

                ?>


            </div>

        <?php } ?>
    </div>



</div>

<?php
require 'templates/footer.php';
?>