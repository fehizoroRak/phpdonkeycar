<?php
session_start();
require_once 'config.php';
require_once 'lib/index.php';
require 'templates/head.php';

$dbh = connectDB();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect to the login page if not logged in
        header("Location: index.php");
        exit();
    }

    // Validate and sanitize user inputs
    $userId = $_SESSION['user_id'];
    $carId = filter_input(INPUT_POST, 'car_id', FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);


    // Check if the item already exists in the cart for the user
    $stmt = $dbh->prepare("SELECT cart_item_id, quantity FROM cart_items WHERE user_id = :user_id AND car_id = :car_id");
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':car_id', $carId);
    $stmt->execute();
    $existingCartItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingCartItem) {
        // Update the quantity if the item already exists in the cart
        $newQuantity = $existingCartItem['quantity'] + $quantity;
        $stmt = $dbh->prepare("UPDATE cart_items SET quantity = :quantity WHERE cart_item_id = :cart_item_id");
        $stmt->bindParam(':quantity', $newQuantity);
        $stmt->bindParam(':cart_item_id', $existingCartItem['cart_item_id']);
    } else {
        // Insert a new item into the cart
        $stmt = $dbh->prepare("INSERT INTO cart_items (user_id, car_id, quantity) VALUES (:user_id, :car_id, :quantity)");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    }

    // Execute the prepared statement
    if (!$stmt->execute()) {
        echo "SQL Error: " . $stmt->errorInfo()[2];
        exit();
    }
}


// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validate_cart'])) {
    // Assuming you have $pickupDate and $returnDate set elsewhere in your code
    $pickupDate = $_SESSION['departureDate'];
    $returnDate = $_SESSION['returnDate'];

    // Retrieve cart items for the logged-in user
    $stmt = $dbh->prepare("SELECT ci.*, c.rental_price, c.rent_total_price, (ci.quantity * c.rent_total_price) AS total_price
                               FROM cart_items ci
                               JOIN cars c ON ci.car_id = c.car_id
                               WHERE ci.user_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $cartItems = $stmt->fetchAll();

    // Insert cart items into the "reservations" table
    foreach ($cartItems as $cartItem) {
        $userId = $cartItem['user_id'];
        $carId = $cartItem['car_id'];
        $quantity = $cartItem['quantity'];
        $rentalPrice = $cartItem['rental_price'];
        $totalPrice = $cartItem['total_price'];

        $stmt = $dbh->prepare("INSERT INTO reservations 
                                    (user_id, car_id, pickup_date, return_date, qty, price_total, status) 
                                    VALUES (:user_id, :car_id, :pickup_date, :return_date, :qty, :price_total, 'Pending')");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
        $stmt->bindParam(':pickup_date', $pickupDate, PDO::PARAM_STR);
        $stmt->bindParam(':return_date', $returnDate, PDO::PARAM_STR);
        $stmt->bindParam(':qty', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':price_total', $totalPrice, PDO::PARAM_INT);

        // Execute the prepared statement
        if (!$stmt->execute()) {
            echo "SQL Error: " . $stmt->errorInfo()[2];
            exit();
        }
    }

    // Clear the cart after validating
    $stmt = $dbh->prepare("DELETE FROM cart_items WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();

    echo "Cart validated successfully!";
}

// Display cart items after validation
$stmt = $dbh->prepare("SELECT ci.*, c.rental_price, c.rent_total_price, (ci.quantity * c.rent_total_price) AS total_price
                           FROM cart_items ci
                           JOIN cars c ON ci.car_id = c.car_id
                           WHERE ci.user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$cartItems = $stmt->fetchAll();

//var_dump($cartItems);

// Display cart items

if (!empty($cartItems)) {
    
    foreach ($cartItems as $cartItem) {

?>

<div class="container">
        <div class="cart-item">
            <h2>Cart Items</h2>
            <table class="cart-info">
                <tr>
                    <th>Cart Item ID</th>
                    <th>User ID</th>
                    <th>Car ID</th>
                    <th>Quantity</th>
                    <th>Rental Price</th>
                  
                </tr>
                <tr>
                    <td><?= $cartItem['cart_item_id'] ?></td>
                    <td><?= $cartItem['user_id'] ?></td>
                    <td><?= $cartItem['car_id'] ?></td>
                    <td><?= $cartItem['quantity'] ?></td>
                    <td><?= $cartItem['rental_price'] ?></td>
                   
                </tr>
                <!-- Add more rows for additional cart items -->
            </table>

            <div class="total-price">Total Price: €<?= $cartItem['total_price'] ?></div>
            <form method="POST" action="">
                <input type="submit" name="validate_cart" class="validate-cart" value="Validate Cart">
            </form>
           
        </div>
    </div>

<?php

    }
    echo '</table>';
} else {
    echo 'No items in the cart.';
}

?>


    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .cart-item {
            border: 1px solid #ccc;
            border-radius: 8px;
            margin: 10px;
            padding: 15px;
            background-color: #fff;
            text-align: center;
        }

        .cart-info {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }

        .cart-info th,
        .cart-info td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .cart-info th {
            background-color: #f2f2f2;
        }

        .total-price {
            font-size: 20px;
            font-weight: bold;
            color: #b82b21;
        }

        .validate-cart {
            background-color: #62aaa7;
            color: #f6f6f6;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
    </style>













<?php
require 'templates/footer.php';
?>