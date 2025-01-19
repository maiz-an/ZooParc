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

// Fetch booked events for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT b.booking_id, e.event_name, e.event_date, e.event_time, b.quantity, b.total_price, b.booking_date, b.status 
        FROM events e
        JOIN bookings b ON e.event_id = b.event_id
        WHERE b.user_id = ?
        ORDER BY e.event_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// Check if there are any confirmed bookings that need payment
$has_pending_payments = false;
while($row = $result->fetch_assoc()) {
    if ($row['status'] === 'confirmed') {
        $has_pending_payments = true;
        break;
    }
}
$result->data_seek(0); // Reset result pointer

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
    <link rel="stylesheet" href="style/booking_table.css">
    <link rel="stylesheet" href="style/scroll up music.css">
    <link rel="stylesheet" href="style/ticket-btn.css">
    <link rel="stylesheet" href="style/map-btn.css">
    <link rel="stylesheet" href="style/loader.css">
    <script src="js/loader.js" defer></script>
    <script src="js/audio.js" defer></script>
    <script src="js/upbtn.js" defer></script>

    <script type="text/javascript">
        function confirmCancel(bookingId, status) {
            if (status === 'confirmed') {
                alert("This booking has been confirmed by the admin and cannot be canceled.");
            } else {
                if (confirm("Are you sure you want to cancel this booking?")) {
                    window.location.href = "cancel_booking.php?booking_id=" + bookingId;
                }
            }
        }
        function proceedToPayment(bookingId) {
    // Redirect to the Booking Payment Page
    window.location.href = "booking_payment_page.php?booking_id=" + bookingId;
}

    </script>


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
                        <li>
                            <div class="nav-item home-item">
                                <a href="member_view_events.php" class="home-button"style="color: #ffcc00;">My Events</a>
                                <div class="animal-animation">
                                    <img src="img/lion.gif" class="animal" alt="lion Animation">
                                </div>
                            </div>
                        </li>
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
        <h2>My Booked Events</h2>
        <table class="events-table">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Booking Date</th>
                    <th>Status</th> <!-- Added this line -->
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['event_name']); ?></td>
                <td><?php echo htmlspecialchars($row['event_date']); ?></td>
                <td><?php echo htmlspecialchars($row['event_time']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td>$<?php echo htmlspecialchars($row['total_price']); ?></td>
                <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td> <!-- Display booking status -->
                <td>
                    <?php if ($row['status'] === 'pending'): ?>
                        <button class="cancel-btn" onclick="confirmCancel('<?php echo $row['booking_id']; ?>', '<?php echo $row['status']; ?>')">Cancel Booking</button>
                    <?php elseif ($row['status'] === 'confirmed'): ?>
                        <button class="call-btn" onclick="window.location.href='tel:+94753357777';">Call Now</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="8">You haven't booked any events yet.</td> <!-- Adjusted colspan to 8 -->
        </tr>
    <?php endif; ?>
</tbody>

            

        </table>
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

