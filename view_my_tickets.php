<?php
session_start();

// Check if the user is logged in as a member
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Update this if your root user has a password
$dbname = "zoo_website";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID from the session

// Fetch payment and ticket details for the logged-in user
$sql = "SELECT payment_id, ticket_id, amount, payment_status, payment_date FROM member_ticket_payments WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Sevillana&display=swap" rel="stylesheet"><link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/ticket_table.css">
   
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
        
        .t-btn{
            background-color: rgb(234, 57, 13);
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .t-btn :hover{
            background-color: #333;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .home-item-t {
    position: relative; /* To position the animals relative to the button */
    }

    .animal-animation-t {
        position: absolute;
        top: -100px; /* Adjust to position the animation above the button */
        left: 75px;
        transform: translateX(-50%);
        display: flex;
        gap: 10px; /* Adjust space between animals */
    }

    .animal-t {
        width: 60px; /* Adjust size as needed */
        animation: play 5s infinite; /* Example animation */
    }

        .t-btn {
            width: 100%;
           padding: 7px 10px;
           border-radius: 5px;
           margin-left: 1px;
         
           background-color: white;
           color:  rgb(234, 57, 13);
           text-decoration: none;
           margin: 5px;
           cursor: pointer;
           border: 2px solid rgb(234, 57, 13);
        }
        .t-btn:hover{
            background-color: rgb(234, 57, 13);
           border: 2px solid rgb(234, 57, 13);
           color: white;
           transform: scale(1.1);

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
                        <li><a href="member_view_events.php" class="nav-btn">My Events</a></li>
                        <li><a href="view_events_member.php"  class="nav-btn">Events</a></li>
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
    <a href="tickets.php" class="t-btn">Buy Ticket</a>
        <h2>My Ticket Purchases</h2>
        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Ticket ID</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    <th>Payment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['payment_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['ticket_id']); ?></td>
                            <td>$<?php echo htmlspecialchars($row['amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No ticket purchases found.</td>
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
