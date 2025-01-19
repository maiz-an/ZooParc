<?php
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zoo_website";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$status_update_success = null; // Variable to track if the update was successful

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $booking_id = $_POST['booking_id'];
    $new_status = $_POST['status'];

    $sql = "UPDATE bookings SET status = ? WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Prepare() failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("ss", $new_status, $booking_id);
    $result = $stmt->execute();
    $stmt->close();

    $status_update_success = $result;
}

// Fetch all bookings from the database
$sql = "SELECT b.booking_id, e.event_name, u.user_id, b.quantity, b.total_price, b.booking_date, b.status 
        FROM bookings b 
        JOIN events e ON b.event_id = e.event_id
        JOIN users u ON b.user_id = u.user_id"; // Ensure correct user data is fetched
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        /* Center-align the heading */
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color:rgb(234, 57, 13);
            font-size:29px;
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color:whitesmoke;
            color: rgb(234, 57, 13);
            text-align:center;
        }
        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .status-select {
            width: 150px;
            padding: 5px;
            text-align: center;
        }

        .update-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            height: 35px;
        }

        .update-btn:hover {
            background-color: #218838;
        }
    </style>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($status_update_success !== null): ?>
                alert("<?php echo $status_update_success ? 'Booking status updated successfully!' : 'Error updating booking status.'; ?>");
            <?php endif; ?>
        });
    </script>
</head>
<?php
// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<body>

    <header>
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
                    <li>
                        <div class="nav-item home-item">
                            <a href="manage_bookings.php" class="home-button"style="color: #ffcc00;">Bookings</a>
                            <div class="animal-animation">
                                <img src="img/lion.gif" class="animal" alt="lion Animation">
                            </div>
                        </div>
                    </li>                  
                    <li><a href="manage_users.php" class="nav-btn">Users</a></li>
                    <li><a href="view_ticket_payments.php" class="nav-btn">Tickets</a></li>
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
    <h2>Manage Bookings</h2>

    <!-- Search Box -->
    <form method="GET" action="manage_bookings.php" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Search for bookings..." style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ddd;">
    </form>

    <!-- Display All Bookings -->
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Event Name</th>
                <th>User ID</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Booking Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['event_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_price']); ?></td>
                        <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                        <td>
                            <form method="POST" action="manage_bookings.php">
                                <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($row['booking_id']); ?>">
                                <select name="status" class="status-select">
                                    <option value="pending" <?php echo $row['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="confirmed" <?php echo $row['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="canceled" <?php echo $row['status'] == 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                                </select>
                        </td>
                        <td>
                                <button type="submit" name="update_status" class="update-btn">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align:center;">No bookings found</td>
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
