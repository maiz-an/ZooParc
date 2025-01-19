<?php
session_start();

// Ensure the user is a member
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: login.php");
    exit();
}


// Check if the session variable 'username' is set
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Sevillana&display=swap" rel="stylesheet"><link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/member_dashboard.css">
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
    
      section {
    margin-bottom: 2em;
}

#welcome {
    text-align: center;
}

#quick-links {
    text-align: center;
}

#quick-links .links {
    display: flex;
    justify-content: center;
    gap: 1em;
}

#quick-links .btn {
    display: inline-block;
    padding: 0.8em 1.5em;
    background-color: #333;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    transition: background-color 0.3s;
}

#quick-links .btn:hover {
    background-color: #555;
}

#latest-updates .updates {
   padding-left: 10%;
    display: flex;
    flex-wrap: wrap;
    gap: 1em;
}

#latest-updates .update-item {
    align-items: center;
    flex: 1;
    min-width: 200px;
    max-width: 250px;
    text-align: center;
}

#latest-updates .update-item img {
    width: 100%;
    border-radius: 5px;
    margin-bottom: 0.5em;
}

main{
    width: 70%;
    height: inherit;
    background-color: white;
}

@media (max-width: 600px) {
    nav ul {
        flex-direction: column;
    }

    nav ul li {
        margin: 0.5em 0;
    }

    main {
        padding: 1em;
    }

    section {
        padding: 1em;
    }

}
/* Styling for Recent Activities Section */
#recent-activities {
    margin-top: 2em;
    padding: 2em;
    background-color: #fff; /* Background color to match the main content */
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    width: 80%; /* Center align within the main content */
    margin-left: auto;
    margin-right: auto;
}

.activity-section-heading {
    font-family: 'Arial', sans-serif;
    color: #333;
    margin-top: 2em;
    margin-bottom: 1em;
    font-size: 1.5em;
    text-align: left; /* Align left for better readability */
    border-bottom: 2px solid #ddd; /* Underline style for separation */
    padding-bottom: 0.5em;
}





#recent-activity-bookings,
#recent-activity-payments {
    display: grid; /* Use CSS Grid */
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); /* Responsive grid with minimum column size */
    gap: 1.5em; /* Gap between grid items */
    margin-top: 1em;
    align-items: start; /* Align items to the start */
    justify-content: space-between;
}

.activity-item {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    padding: 1.5em;
    width: 80%; /* Full width within the grid cell */
    text-align: left;
    transition: transform 0.3s, box-shadow 0.3s;
    font-family: 'Arial', sans-serif; /* Fallback font */
}

.activity-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.activity-item p {
    margin: 0.5em 0;
    font-size: 1em;
    color: #333;
}

.activity-item p strong {
    color: #111;
    font-weight: bold;
}

.activity-item p + p {
    border-top: 1px solid #eee;
    padding-top: 0.5em;
    margin-top: 0.5em;
}

@media (max-width: 768px) {
    #recent-activity {
        flex-direction: column;
        align-items: center;
    }

    .activity-item {
        width: 80%;
    }
}
h3{
    color: #c4a611;
}

  </style>

</head>
<?php
// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>
<body>

    <header>
        <div class="header-container">
                <a href="member_dashboard.php" class="logo">
                    <img src="img/logo.png" alt="Zoo Parc Logo">
                </a>
                <div class="title-nav">
                    <h1>Zoo Parc
                    </h1>
                    <nav>
                        <ul>     
                        <li>
                            <div class="nav-item home-item">
                                <a href="member_dashboard.php" class="home-button"style="color: #ffcc00;">Home</a>
                                <div class="animal-animation">
                                    <img src="img/lion.gif" class="animal" alt="lion Animation">
                                </div>
                            </div>
                        </li>
                        <li class="my-events"><a href="member_view_events.php" class="nav-btn">My Events</a></li>
                        <li><a href="view_events_member.php" class="nav-btn">Events</a></li>
                        <li><a href="contact_form.php" class="nav-btn">Information</a></li>
                        <li class="log-out"><a href="logout.php" class="nav-btn">Log Out</a></li>
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
        <section id="event">
                    <div class="event-container">
                        <h2 class="special-font">Welcome to Zoo Parc, <?php echo $username; ?></h2>
                        <p>We are delighted to have you here! Explore your dashboard to manage your reservations and pre-orders, and stay updated with our latest news and promotions. Enjoy your day with us.</p>
                    </div>
                </section>
                
            </div>
            
        <section class="event-item">
            
            <section id="latest-updates">
                <h3>Latest Updates & Promotions</h3>
                <div class="updates">
                    <div class="update-item">
                        <img src="img/eVENT.jpg" alt="Zoo event">
                        <p><strong>Event name:</strong> Gator Groove</p>
                    </div>
                    <div class="update-item">
                        <img src="img/dolphinevent.jpg" alt="Zoo event">
                        <p><strong>Event name:</strong> Dolphin Spectacle</p>
                    </div>
                    <div class="update-item">
                        <img src="img/posters1.jpg" alt="Zoo event">
                        <p><strong>Event name:</strong> Mammal Mysteries</p>
                    </div>
                </div>
            </section>
        </section>





        <section id="recent-activities">
    <h3>Recent Activities</h3>
    <div id="recent-activity">
        <?php
        // Database connection details
        $servername = "localhost";
        $db_username = "root";  // Corrected variable name
        $db_password = "";      // Corrected variable name
        $dbname = "zoo_website";

        // Create connection
        $conn = new mysqli($servername, $db_username, $db_password, $dbname);  // Corrected variable names

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get the logged-in user's ID from the session
        $user_id = $_SESSION['user_id'];

        // Fetch user's event bookings (without JOIN)
        $booking_query = "SELECT booking_id, event_id, quantity, total_price, booking_date, status 
                          FROM bookings 
                          WHERE user_id = ?";
        $stmt = $conn->prepare($booking_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $booking_result = $stmt->get_result();

        // Fetch user's ticket payments (without JOIN)
        $payment_query = "SELECT ticket_id, amount, payment_status, payment_date 
                          FROM member_ticket_payments 
                          WHERE user_id = ?";
        $stmt = $conn->prepare($payment_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $payment_result = $stmt->get_result();
        ?>

        <!-- Ticket Payments Section -->
    <?php if ($payment_result->num_rows > 0) : ?>
        <h3 class="activity-section-heading">Your Ticket Payments</h3>
        <div id="recent-activity-payments">
            <?php while ($row = $payment_result->fetch_assoc()) : ?>
                <div class='activity-item'>
                    <p><strong>Ticket ID:</strong> <?php echo htmlspecialchars($row['ticket_id']); ?></p>
                    <p><strong>Amount:</strong> $<?php echo htmlspecialchars($row['amount']); ?></p>
                    <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($row['payment_status']); ?></p>
                    <p><strong>Payment Date:</strong> <?php echo htmlspecialchars($row['payment_date']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <p class="no-activity">No recent ticket payments.</p>
    <?php endif; ?>
        
        <!-- Event Bookings Section -->
    <?php if ($booking_result->num_rows > 0) : ?>
        <h3 class="activity-section-heading">Your Event Bookings</h3>
        <div id="recent-activity-bookings">
            <?php while ($row = $booking_result->fetch_assoc()) : ?>
                <div class='activity-item'>
                    <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($row['booking_id']); ?></p>
                    <p><strong>Event ID:</strong> <?php echo htmlspecialchars($row['event_id']); ?></p>
                    <p><strong>Quantity:</strong> <?php echo htmlspecialchars($row['quantity']); ?></p>
                    <p><strong>Total Price:</strong> $<?php echo htmlspecialchars($row['total_price']); ?></p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($row['booking_date']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <p class="no-activity">No recent event bookings.</p>
    <?php endif; ?>
        
    </div>
</section>





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
          <a href="member_dashboard.php" class="quick-link-btn">Home</a>|
          <a href="tickets.php" class="quick-link-btn">Buy Tickets</a>|
          <a href="registration.php" class="quick-link-btn">Register</a>|
          <a href="forgot_password.php" class="quick-link-btn">Reset-Password</a> </p>
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
     <!-- Floating ticket Button -->
     <div class="floating-cart">
        <a href="view_my_tickets.php" class="cart-btn">
            <img src="img/ticket-icon.png" alt="Buy Ticket">
        </a>

        <!-- Floating Map Button -->
     <div class="floating-map">
        <a href="ZooMapmem.html" class="map-btn">
            <img src="img/map.gif" alt="Zoo Map">
        </a>

</html>



