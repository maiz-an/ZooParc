<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Payments</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Sevillana&display=swap" rel="stylesheet"><link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/scroll up music.css">
    <link rel="stylesheet" href="style/ticket-btn.css">
    <link rel="stylesheet" href="style/map-btn.css">
    <link rel="stylesheet" href="style/loader.css">
    <script src="js/loader.js" defer></script>
    <script src="js/audio.js" defer></script>
    <script src="js/upbtn.js" defer></script>
    <style>
    
    /* Import desired fonts from Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Pacifico&family=Bree+Serif&family=Lobster&display=swap');

    @keyframes blink {
        0% {
            opacity: 1;
        }
        50% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

    h1 {
        font-size: 70px !important; /* Force the font size to apply */
        color: #ffcc00;
        text-align: center; 
        font-family: 'Pacifico', 'Lobster', 'Bree Serif', 'Arial', sans-serif; /* Multiple fonts for fallback */
        margin-top: 1px;
        padding: 1px; 
        border-radius: 1px; 
        display: inline-block; 
        text-shadow: 
            0 0 1px #000000, 
            0 0 10px #fffef9, 
            0 0 20px #ECDFCC, 
            0 0 30px #ffffff, 
            0 0 40px #ECDFCC, 
            0 0 50px #ffffff;
        animation: blink 0.09s infinite; /* Adjusted animation for a smoother effect */
    }
       main {
           padding: 2em 1em;
           max-width: 1100px;
           margin: 2em auto;
           background-color: white;
           box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
           border-radius: 8px;
           height: 850px;
       }
       h2 {
           text-align: center;
           margin-bottom: 20px;
           color: rgb(234, 57, 13);
           font-size: 30px;
       }
       .card-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            width: 500px; /* Increased the width */
            margin: 20px;
            text-align: center;
            overflow: hidden;
            transition: transform 0.3s ease; /* Add transition for pop-up effect */
        }

        .card:hover {
            transform: scale(1.05); /* Pop-up effect on hover */
        }

        .card img {
            width: 100%;
            height: 500px; /* Increased the height */
            object-fit: cover;
        }

        .card h3 {
            margin: 20px 0 10px 0;
            font-size: 28px; /* Increased the font size */
            color: #333;
        }

        .card p {
            padding: 0 20px 20px 20px;
            color: #777;
            font-size: 18px; /* Increased the font size */
        }

        .card-button {
            display: inline-block;
            padding: 15px 30px; /* Increased the padding */
            margin-bottom: 20px;
            background-color: rgb(234, 57, 13);
            color:white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px; /* Increased the font size */
            font-weight: bold;
            transition: background-color 0.3s ease;
            border: 2px solid transparent;
        }

        .card-button:hover {
            background-color: whitesmoke;
            border: 2px solid rgb(234, 57, 13);
            color: rgb(234, 57, 13);
            transform: scale(1.1);
        }
    </style>
    <title>View Ticket Payments</title>
</head>
<?php
// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<body>

    <header>
  
  </div>
      <div class="header-container">
          <a href="admin_dashboard.php" class="logo">
              <img src="img/logo.png" alt="Zoo Parc Logo">
          </a>
          <div class="title-nav">
              <h1>Zoo Parc
              </h1>
              <nav>
              <ul>
                            
                            <li><a href="admin_dashboard.php" class="nav-btn">Home</a></li>
                            <li><a href="manage_events.php" class="nav-btn">Events</a></li>
                            <li><a href="manage_bookings.php"class="nav-btn">Bookings</a></li>
                            <li><a href="manage_users.php"  class="nav-btn">Users</a></li>
                                <li>   
                        <div class="nav-item home-item">
                                <a href="view_ticket_payments.php" class="home-button"style="color: #ffcc00;">Tickets</a>
                                <div class="animal-animation">
                                    <img src="img/lion.gif" class="animal" alt="lion Animation">
                                </div>
                            </div>
                        </li>          
                        <li><a href="reports.php" class="nav-btn">Reports</a></li>
                        <li><a href="logout.php" class="nav-btn logout-btn">Logout</a></li>
                            </ul>
              </nav>
          </div>
      </div>
  </header>
  <div id="loader">
    <img src="img/logo.png" alt="Zoo Parc Logo">
    <p>Loading, please wait...</p>
</div>

<main>
        <h2>View Ticket Payments</h2>


  <!-- Large Button or Card Section -->
  <div class="card-container">

            <div class="card">
                <img src="img/zoomember.jpg" alt="Member Ticket Payments">
                <h3>Member Ticket Payments</h3>
                <p>Click below to view detailed information about member ticket payments.</p>
                <a href="view_member_payments.php" class="card-button">View Member Payments</a>
            </div>
        </div>
    </main>
       
</body>



 <!-- Enhanced Footer -->
 <footer
  class="footer">
    <div class="footer-content">
        
        </div>
        <div class="footer-section links">
            
        </div>
    </div>
    <br>
    <div class="footer-bottom">
      <div class="footer-section about">
        <div class="social-icons">
            <p class="footer-head"><i class="fas fa-leaf icon"></i>
                Zoo Parc - The Zoological Park
          <i class="fas fa-paw icon"></i> <br></p>
          <p>Together, we CAN make a difference! If you'd like to help our wildlife charity,
             there are lots of ways you can do so... THANK YOU for your ongoing SUPPORT.</p>           
      
    <p>Zoo Parc is home to over 31,000 animals across 128 acres of lush gardens.
         Come explore and learn about wildlife and conservation efforts.</p> <br>
         <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-google-plus"></i></a>
        <a href="#"><i class="fab fa-youtube"></i></a>
        <p> &copy; 2024 Zoo Parc - The Zoological Park | 
            Designed by<a href="https://www.instagram.com/luna.layl_/" class="btn-su" 
            target="_blank" >Sumalka Kodithuwakku<i class="fab fa-pagelines icon-lv" id="icon-lv"></i></a></p>
        
            


        </div>
        
        </div> 
    </div>
    <div class="quick-links">
        <p> Quick - Links  
          <a href="#" class="quick-link-btn">Home</a>|
          <a href="add_event.php" class="quick-link-btn">Add Event</a>|
          <a href="view_member_payments.php" class="quick-link-btn">View Tickets</a>|
          <a href="edit_user.php" class="quick-link-btn">Edit Member's Info</a> </p>
      </div>
            
</footer>



        <!-- Scroll to Top Button -->
<div class="scroll-to-top" id="scrollToTop">
<a href="#top" class="scroll-btn">
    <i class="fas fa-chevron-up"></i>
</a>
</div>
    <!-- Floating Audio Control -->
<div id="audio-control">
<button id="musicPlaying">&#9654;</button> <!-- Play Icon -->
<audio id="background-music" loop>
    <source src="images/mp3.mp3" type="audio/mpeg">
    browser not support the audio
</audio>
</div>





</html>




