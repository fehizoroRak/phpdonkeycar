   <!-- Navigation -->
   <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
       <a class="navbar-brand" href="index.php">
           <img src="donkeycar_logo.png" alt="Logo" height="50">
       </a>
       <div class="navbar-collapse justify-content-end">
           <?php
            if (isset($_SESSION['username'])) {
            ?>
               <a href="my_reservations.php"><button>My Reservations</button></a>
           <?php

            }


            ?>
           <ul class="navbar-nav">
               <li class="nav-item" style="display:flex;">
                   <a class="nav-link" href="login.php">Login</a>
                   <a class="nav-link" href="register.php">Register</a>
                   <a id="logout" href="logout.php">Logout</a>
               </li>
           </ul>
       </div>
   </nav>


   <style>
  #logout {
            background-color: #b82b21;
            color: #fff !important;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
   </style>
 