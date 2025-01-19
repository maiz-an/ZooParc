<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "zoo_website";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch event details using event_id
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $sql = "SELECT event_name, event_date, event_time, ticket_price, availability FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id); // Updated to use "i" for integer
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    $stmt->close();
} else {
    die("Invalid event ID.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle booking and payment logic here
    $quantity = $_POST['quantity'];
    $total_price = $quantity * $event['ticket_price'];

    // Insert booking into the database
    $user_id = $_SESSION['user_id'];
    $sql = "INSERT INTO bookings (event_id, user_id, quantity, total_price, booking_date) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiid", $event_id, $user_id, $quantity, $total_price); // Updated to use "iiid" for integers and double
    $stmt->execute();
    $stmt->close();

    // After processing, redirect to the confirmation page with a success message
    header("Location: confirmation.php?event_id=$event_id&success=1");
    exit();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Event</title>
 <!-- Font Awesome for Icons -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Sevillana&display=swap" rel="stylesheet"><link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/Booking_form.css">
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
  </style>
    
</head>

</head>
<?php
// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<body>
<header>
        <div class="header-container">
                <a href="index.html" class="logo">
                    <img src="img/logo.png" alt="Zoo Parc Logo">
                </a>
                <div class="title-nav">
                    <h1>Zoo Parc
                    </h1>
                    <nav>
                        <ul>
                            
                        <li><a href="member_dashboard.php" class="nav-btn">Home</a></li>
                        <li><a href="member_view_events.php" class="nav-btn">My Events</a></li>
                        <li>
                            <div class="nav-item home-item">
                                <a href="view_events_member.php" class="home-button"style="color: #ffcc00;">Events</a>
                                <div class="animal-animation">
                                    <img src="img/lion.gif" class="animal" alt="lion Animation">
                                </div>
                            </div>
                        </li>
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
    <div class="booking-container">
        <h2>Book Event: <?php echo htmlspecialchars($event['event_name']); ?></h2><br>
        <p>Date: <?php echo htmlspecialchars($event['event_date']); ?></p>
        <p>Time: <?php echo htmlspecialchars($event['event_time']); ?></p>
        <p>Price: $<?php echo htmlspecialchars($event['ticket_price']); ?></p>
        <p>Availability: <?php echo htmlspecialchars($event['availability']); ?></p>

        <form action="book_event.php?event_id=<?php echo $event_id; ?>" method="POST">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" max="10" required>

            <button type="submit">Confirm Booking</button>
        </form>
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
            <i class="fas fa-leaf icon"></i>
            <a href="#"><i class="fab fa-facebook"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-google-plus"></i></a>
          <a href="#"><i class="fab fa-youtube"></i></a>
          <i class="fas fa-paw icon"></i> <br>
          <p> <br>&copy; 2024 Zoo Parc | 
            Designed by<a href="https://www.instagram.com/luna.layl_/" class="btn-su" 
            target="_blank" >Sumalka Kodithuwakku<i class="fab fa-pagelines icon"></i>




            </a></p>
          <p>Together, we CAN make a difference! If you'd like to help our wildlife charity,
             there are lots of ways you can do so... THANK YOU for your ongoing SUPPORT.</p>           
      
    <p>Zoo Parc is home to over 31,000 animals across 128 acres of lush gardens. Come explore and learn about wildlife and conservation efforts.</p>
    </div>
</div> 
    </div>
    
            <div class="quick-links">
              <p> Quick-links  
                <a href="#" class="quick-link-btn">Home</a>
                <a href="#" class="quick-link-btn">News</a>
                <a href="#" class="quick-link-btn">About Us</a>
                <a href="#" class="quick-link-btn">Contact Us</a>
                <a href="#" class="quick-link-btn">Our Team</a> </p>
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
        <a href="Tickets.html" class="cart-btn">
            <img src="img/ticket-icon.png" alt="Buy Ticket">
        </a>

        <!-- Floating Map Button -->
     <div class="floating-map">
        <a href="ZooMap.html" class="map-btn">
            <img src="img/map.gif" alt="Zoo Map">
        </a>

</html>






