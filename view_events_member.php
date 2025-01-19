<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection (Replace with your actual DB connection details)
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "zoo_website";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure correct charset for the connection
$conn->set_charset("utf8mb4");

// Fetch events from the database
$sql = "SELECT event_id, event_name, event_date, event_time, ticket_price, availability, event_image FROM events ORDER BY event_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Events</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Sevillana&display=swap" rel="stylesheet"><link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/Member_events.css">
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
                <a href="member_dashboard.php" class="logo">
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

<h2>Available Events</h2>

        <!-- Events Card Container -->
        <div class="events-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    
                    <div class="event-card">
                    <div class="img-cart">
                        
                    <?php
// Display the event image from the BLOB if data length is sufficient
if (!empty($row['event_image'])) {
    $image_data = base64_encode($row['event_image']);
    
    // Detect the MIME type based on the binary data if necessary
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->buffer($row['event_image']);

    echo '<img src="data:' . $mime_type . ';base64,' . $image_data . '" alt="Event Image"/>';
} else {
    echo '<img src="uploads/default_image.jpg" alt="Default Image"/>';
}
?></div>
                        <div class="event-card-content">
                            <div class="event-card-title"><?php echo htmlspecialchars($row['event_name']); ?></div>
                            <div class="event-card-details">
                                Date: <?php echo htmlspecialchars($row['event_date']); ?><br>
                                Time: <?php echo htmlspecialchars($row['event_time']); ?>
                            </div>
                            <div class="event-card-price">Price: $<?php echo htmlspecialchars($row['ticket_price']); ?></div>
                            <div class="event-card-availability">Availability: <?php echo htmlspecialchars($row['availability']); ?></div>
                            <a href="book_event.php?event_id=<?php echo $row['event_id']; ?>" class="book-now-btn">Book Now</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No events available at the moment. Please check back later.</p>
            <?php endif; ?>
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



<?php
$conn->close();
?>


