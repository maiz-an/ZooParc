<?php
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your database password if set
$dbname = "zoo_website";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch member payments from the member_ticket_payments table using user_id
$sql_members = "SELECT p.payment_id, p.ticket_id, p.user_id, u.username, p.amount, p.payment_status, p.payment_date 
                FROM member_ticket_payments p
                JOIN users u ON p.user_id = u.user_id
                ORDER BY p.payment_date DESC";
$result_members = $conn->query($sql_members);

if (!$result_members) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <title>Member Ticket Payments</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Sevillana&display=swap" rel="stylesheet"><link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/index.css">
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
           max-width: 1300px;
           margin: 2em auto;
           background-color: white;
           box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
           border-radius: 8px;
           height: auto;
       }

       h2 {
           text-align: center;
           margin-bottom: 20px;
           color: rgb(234, 57, 13);
           font-size: 30px;
       }

       table {
           width: 100%;
           border-collapse: collapse;
           margin-bottom: 20px;
       }

       table, th, td {
           border: 1px solid #ddd;
       }

       table th {
           background-color: whitesmoke;
           color: rgb(234, 57, 13);
           text-align: center;
       }

       th, td {
           padding: 12px;
           text-align: left;
       }

       .no-users {
           text-align: center;
           padding :20px;
           font-size: 18px;
           color:#333;
           font-weight: bold;
       }

       .go-back-button {
            display: inline-block;
            padding: 10px 20px;
            margin-bottom: 20px;
            background-color: rgb(234, 57, 13);
            color:white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease, color 0.3s ease;
            border: 2px solid transparent;
        }

        .go-back-button:hover {
            background-color: whitesmoke;
            border: 2px solid rgb(234, 57, 13);
            color: rgb(234, 57, 13);
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
    <!-- Go Back Button -->
    <a href="view_ticket_payments.php" class="go-back-button">Go Back</a>
    
    <h2>Member Ticket Payments</h2>

    <table class="events-table" style="width: 100%; margin-bottom: 20px;">
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>User ID</th> <!-- New User ID column -->
                <th>Username</th>
                <th>Amount Paid</th>
                <th>Payment Status</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Loop through and display member payments
            if ($result_members->num_rows > 0): 
                while($row = $result_members->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ticket_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td> <!-- Display User ID -->
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td>$<?php echo htmlspecialchars($row['amount']); ?></td>
                        <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                        <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
                    </tr>
                <?php endwhile; 
            else: ?>
                <tr>
                    <td colspan="6" class="no-users">No member ticket payments found.</td>
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

<?php
$conn->close();
?>


