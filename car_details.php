<?php
session_start();
require_once 'config.php';
require_once 'lib/index.php';
require 'templates/head.php';

$dbh = connectDB();


// Check if the car ID is provided in the URL
if (isset($_GET['car_id'])) {

    $carId = $_GET['car_id']; // Replace 'car_id' with the actual parameter name
    $totalprice = $_GET['total_price']; // Replace 'total_price' with the actual parameter name

    // Update the total_price in the cars table
    $stmt = $dbh->prepare("UPDATE cars SET rent_total_price = :totalprice WHERE car_id = :carId");
    $stmt->bindParam(':totalprice', $totalprice);
    $stmt->bindParam(':carId', $carId);
    $stmt->execute();

    //echo "Total price updated successfully";

    // Retrieve car details from the database based on the car ID
    $stmt = $dbh->prepare("SELECT make, model, year, rental_price, image_url FROM cars WHERE car_id = :car_id");
    $stmt->bindParam(':car_id', $carId);
    $stmt->execute();
    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the car exists
    if ($car) {
        $carMake = htmlspecialchars($car['make']);
        $carModel = htmlspecialchars($car['model']);
        $carYear = htmlspecialchars($car['year']);
        $rentalPrice = htmlspecialchars($car['rental_price']);
        $imageUrl = htmlspecialchars($car['image_url']);

?>

        <div class="container">
            <div class="car-details">
                <img src="templates/white-car.jpg" alt="Toyota Camry" class="car-image">
                <h2><?= $carMake ?></h2>
                <h2><?= $carModel ?></h2>
                <p><?= $carYear ?></p>
                <p>Rental Price: €<?= $rentalPrice ?>/day</p>

                <div class="rental-info">
                    <p class="total-price">Total Price: €<?= $_GET['total_price'] ?></p>
                    <p class="quantity">Quantity: 1</p>
                </div>

                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="car_id" value="<?= $carId ?> ">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" required>
                    <button class="add-to-cart" type="submit">Add to Cart</button>
                </form>

                <form action="rent_now.php" method="post">
                    <input type="hidden" name="car_id" value="<?php echo $carId; ?>">
                    <button class="book-now" type="submit">Rent Now</button>
                </form>

            </div>
        </div>
<?php


    } else {
        // Display a message if the car does not exist
        echo '<p>Car not found.</p>';
    }
} else {
    // Redirect to a page with a list of cars if car ID is not provided
    header("Location: cars.php");
    exit();
}

?>





    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .car-details {
            border: 1px solid #ccc;
            border-radius: 8px;
            margin: 10px;
            padding: 15px;
            background-color: #fff;
            text-align: center;
        }

        .car-image {
            max-width: 100%;
            width: 22rem;
            height: auto;
            border-radius: 8px;
        }

        .rental-info {
            margin-top: 20px;
        }

        .total-price {
            font-size: 20px;
            font-weight: bold;
            color: #b82b21;
        }

        .quantity {
            font-size: 18px;
            margin-top: 10px;
        }

        .action-buttons {
            margin-top: 20px;
        }

        .rent-button {
            background-color: #62aaa7;
            color: #f6f6f6;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-to-cart {
            background-color: #62aaa7;
            color: #f6f6f6;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        .book-now {
            background-color: #b82b21;
            color: #f6f6f6;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }



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



<?php
require 'templates/footer.php';
?>