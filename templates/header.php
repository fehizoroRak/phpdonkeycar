<?php
require_once 'config.php';
require_once 'lib/index.php';
//require 'templates/head.php';

$dbh = connectDB();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #fff;
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            background-color: #f6f6f6;
        }

        .navbar-brand,
        .navbar-nav li a {
            color: #363636 !important;
        }

        .header {
            background-image: url('templates/carok.png');
            background-size: cover;
            color: #f6f6f6;
            text-align: center;
            padding: 100px 0;
        }

        .search-bar {
            background-color: #b82b21;
            color: #f6f6f6;
            padding: 20px;
            border-radius: 10px;
            margin-top: -50px;
        }

        .section {
            padding: 50px 0;
        }

        .footer {
            background-color: #363636;
            color: #f6f6f6;
            padding: 20px 0;
            text-align: center;
        }

        .btn {
            background-color: #62aaa7;
            border: 1px solid #62aaa7;
            color: #f6f6f6;
        }
    </style>
    <title>Rental Car</title>
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <a class="navbar-brand" href="index.php">
            <img src="templates/donkeycar_logo.png" alt="Logo" height="50">
        </a>
        <div class="navbar-collapse justify-content-end">
        <ul class="navbar-nav">
                <li class="nav-item" style="display:flex;">
                    <a class="nav-link" href="login.php">Login</a>
                    <a class="nav-link" href="register.php">Register</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Header -->
    <header class="header">
        <h1>Welcome to DonkeyCar</h1>
        <p>Explore the world with our rental cars</p>
    </header>

    <!-- Search Bar -->
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1 search-bar">
                <form method="POST" action="results.php">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <?php
                              $sql = "SELECT * FROM citydeparture";
                              $stmt = $dbh->prepare($sql);
                              $stmt->execute();
                            ?>


                            <label for="departureTown">City Departure</label>
                            <select class="form-control" name="departureTown" id="departureTown" required>
                                <?php
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                            
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['address']. ', '. $row['city'].', '. $row['country']  ?></option>
                              <?php
                                }
                              ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <?php
                              $sql = "SELECT * FROM cityreturn";
                              $stmt = $dbh->prepare($sql);
                              $stmt->execute();
                            ?>


                            <label for="returnTown">City Return</label>
                            <select class="form-control" name="returnTown" id="returnTown" required>
                                <?php
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                            
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['address']. ', '. $row['city'].', '. $row['country']  ?></option>
                              <?php
                                }
                              ?>
                            </select>
                        </div>
                    
                        <div class="form-group col-md-2">
                            <label for="departureDate">Date Departure</label>
                            <input type="date" class="form-control" id="departureDate" name="departureDate" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="returnDate">Date Return</label>
                            <input type="date" class="form-control" id="returnDate" name="returnDate" required>
                        </div>
                        <div class="form-group col-md-2 ">
                            <label for="submit">Click here</label>
                            <button type="submit" class="btn btn-light" name="seeallcars">See All Cars</button>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>


    <?php


   

?>
