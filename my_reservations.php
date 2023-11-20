<?php
session_start();
require_once 'config.php';
require_once 'lib/index.php';
require 'templates/head.php';

$dbh = connectDB();
   // Check if the user is logged in
   if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: index.php");
    exit();
}

// Retrieve reservations for the logged-in user
$stmt = $dbh->prepare("SELECT * FROM reservations WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$reservations = $stmt->fetchAll();


?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
    <style>
     

        h2, h1 {
            margin: 40px 0;
            color: #363636;
        }

        table {
            border-collapse: collapse;
            margin: 0 auto;
            width: 80%;
            margin-bottom: 40px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

       

        select {
            margin-top: 5px;
            padding: 5px;
            width: 80%;
            border: none;
            border-radius: 2px;
            background-color: #62aaa7;
            color: #f6f6f6;
            cursor: pointer;
        }

        button {
            margin-top: 5px;
            padding: 5px;
            width: 80%;
            border: none;
            border-radius: 2px;
            background-color: #b82b21;
            color: #f6f6f6;
            cursor: pointer;
        }

        button:hover {
            background-color: #508c89;
        }

        .text {
            text-align: center;
            margin-top: 16px;
            color: #363636;
        }

        a {
            color: #62aaa7;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        p.error {
            color: red;
            text-align: center;
        }
    </style>
</head>

<body>
    <h2 class="text">Mes Réservations</h2>

    <?php if (!empty($reservations)) : ?>
        <table>
            <tr>
                <th>Reservation ID</th>
                <th>User ID</th>
                <th>Car ID</th>
                <th>Pickup Date</th>
                <th>Return Date</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($reservations as $reservation) : ?>
                <tr>
                    <td><?php echo $reservation['reservation_id']; ?></td>
                    <td><?php echo $reservation['user_id']; ?></td>
                    <td><?php echo $reservation['car_id']; ?></td>
                    <td><?php echo $reservation['pickup_date']; ?></td>
                    <td><?php echo $reservation['return_date']; ?></td>
                    <td><?php echo $reservation['qty']; ?></td>
                    <td><?php echo $reservation['price_total']; ?></td>
                    <td><?php echo $reservation['status']; ?></td>
                    <td>
                        <?php if ($reservation['status'] == 'pending') : ?>
                            <form action="update_status.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $reservation['reservation_id']; ?>">
                                <select name="status">
                                    <option value="confirmed">Confirm</option>
                                    <option value="cancelled">Cancel</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        <?php else : ?>
                            <?php echo "Already " . $reservation['status']; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p class="text">No reservations found.</p>
    <?php endif; ?>

    <h1 class="text" style="color:green">Réservations Confirmées</h1>

    <?php if (!empty($reservations)) : ?>
        <table>
            <tr>
                <th>Reservation ID</th>
                <th>User ID</th>
                <th>Car ID</th>
                <th>Pickup Date</th>
                <th>Return Date</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($reservations as $reservation) : ?>
                <?php if ($reservation['status'] == 'confirmed') : ?>
                    <tr>
                        <td><?php echo $reservation['reservation_id']; ?></td>
                        <td><?php echo $reservation['user_id']; ?></td>
                        <td><?php echo $reservation['car_id']; ?></td>
                        <td><?php echo $reservation['pickup_date']; ?></td>
                        <td><?php echo $reservation['return_date']; ?></td>
                        <td><?php echo $reservation['qty']; ?></td>
                        <td><?php echo $reservation['price_total']; ?></td>
                        <td><?php echo $reservation['status']; ?></td>
                        <td>
                            <!-- Action buttons or links here -->
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p class="text">No confirmed reservations found.</p>
    <?php endif; ?>

    <h1 class="text" style="color:#b82b21">Réservations Annulées</h1>

    <?php if (!empty($reservations)) : ?>
        <table>
            <tr>
                <th>Reservation ID</th>
                <th>User ID</th>
                <th>Car ID</th>
                <th>Pickup Date</th>
                <th>Return Date</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($reservations as $reservation) : ?>
                <?php if ($reservation['status'] == 'cancelled') : ?>
                    <tr>
                        <td><?php echo $reservation['reservation_id']; ?></td>
                        <td><?php echo $reservation['user_id']; ?></td>
                        <td><?php echo $reservation['car_id']; ?></td>
                        <td><?php echo $reservation['pickup_date']; ?></td>
                        <td><?php echo $reservation['return_date']; ?></td>
                        <td><?php echo $reservation['qty']; ?></td>
                        <td><?php echo $reservation['price_total']; ?></td>
                        <td><?php echo $reservation['status']; ?></td>
                        <td>
                            <!-- Action buttons or links here -->
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p class="text">No cancelled reservations found.</p>
    <?php endif; ?>

</body>

</html>


<?php
require 'templates/footer.php';
?>