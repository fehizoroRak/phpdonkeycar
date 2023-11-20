<?php

require_once 'config.php';
require_once 'lib/index.php';
require 'templates/header.php';

$dbh = connectDB();


?>

<div class="container section">
    <h2 class="text-center mb-5">Discover our diverse range of high-quality rental cars !</h2>
    <div class="row">
        <div class="col-md-6">
            <img src="templates/carok.png" alt="Offer 1" class="img-fluid">
        </div>
        <div class="col-md-3">
            <img src="templates/carok.png" alt="Offer 2" class="img-fluid">
        </div>
        <div class="col-md-3">
            <img src="templates/carok.png" alt="Offer 3" class="img-fluid">
        </div>
    </div>
</div>
  <!-- Other Sections -->
<div class="container section text-center ">
    <h2>Explore Our Fleet</h2>
    <p>Discover our diverse range of high-quality rental cars, from compact cars to spacious SUVs. Whether you're planning a weekend getaway or a family road trip, we have the perfect vehicle for your needs.</p>
</div>

<!-- New Section -->
<div class="container section ">
    <h2 class="text-center mb-5">Car and utility rental: current offers</h2>
    <div class="row d-flex justify-content-center">
        <div class="col-md-3">
            <img src="templates/carok.png" alt="Offer 1" class="img-fluid">
            <h3>Special Summer Discount</h3>
            <p>Enjoy a fantastic summer adventure with our special discount on selected vehicles. Book now and save on your next road trip!</p>
        </div>
        <div class="col-md-3">
            <img src="templates/carok.png" alt="Offer 2" class="img-fluid">
            <h3>Weekend Getaway Package</h3>
            <p>Plan a quick weekend escape with our exclusive getaway package. Reliable cars and unbeatable prices for your short trips.</p>
        </div>
        <div class="col-md-3">
            <img src="templates/carok.png" alt="Offer 3" class="img-fluid">
            <h3>Family-Friendly Options</h3>
            <p>Explore our family-friendly vehicles, perfect for vacations with your loved ones. Spacious interiors and safety features for a worry-free journey.</p>
        </div>
    </div>
</div>



<h1 class="mt-5 text-center">DonkeyCar - Your mobility partner</h1>

<p class="text-center">Experience the freedom of the road with DonkeyCar, your ultimate travel companion.
    <br>Take advantage of the flexibility of our monthly car subscription offers, without obligation, all-inclusive!
</p>


<div class="container d-flex justify-content-around mt-5">

    <div class="card" style="width: 24rem;">
        <img src="templates/white-car.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">DonkeyCar Unlimited</h5>
            <p class="card-text">Parcourez le monde en toute liberté avec notre abonnement premium. Un prix mensuel fixe, aucune contrainte, tout inclus !</p>
            <a href="#" class="btn btn-primary ">Réserver dès maintenant</a>
        </div>
    </div>

    <div class="card" style="width: 24rem;">
        <img src="templates/white-car.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">Location de voitures pour les entreprises</h5>
            <p class="card-text">DonkeyCar vous offre des avantages exclusifs en tant que professionnel. </p>
            <a href="#" class="btn btn-primary">Réserver maintenant</a>
        </div>
    </div>
</div>





<?php
require 'templates/footer.php';
