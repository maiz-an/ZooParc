<?php
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection details
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "zoo_website";

// Create connection
$conn = mysqli_connect($servername, $db_username, $db_password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the event_id is provided in the URL
if (!isset($_GET['event_id'])) {
    header("Location: manage_events.php");
    exit();
}

$event_id = $_GET['event_id'];

// Fetch event data
$sql = "SELECT event_id, event_name, event_date, event_time, ticket_price, availability, event_image FROM events WHERE event_id = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt === false) {
    die("MySQL prepare statement error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, 'i', $event_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result === false || mysqli_num_rows($result) === 0) {
    header("Location: manage_events.php?error=event_not_found");
    exit();
}

$event = mysqli_fetch_assoc($result);

// Handle form submission for updating the event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_event'])) {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $ticket_price = $_POST['ticket_price'];
    $availability = $_POST['availability'];
    $event_image = $_FILES['event_image'];

    // Check if a new image is uploaded
    if ($event_image['error'] === UPLOAD_ERR_OK) {
        // Read the image file content
        $image_tmp_name = $event_image['tmp_name'];
        $image_content = file_get_contents($image_tmp_name);
    } else {
        // Use existing image if no new image is uploaded
        $image_content = $event['event_image'];
    }

    $update_sql = "UPDATE events SET event_name = ?, event_date = ?, event_time = ?, ticket_price = ?, availability = ?, event_image = ? WHERE event_id = ?";
    $update_stmt = mysqli_prepare($conn, $update_sql);

    if ($update_stmt === false) {
        die("MySQL prepare statement error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($update_stmt, 'sssdisi', $event_name, $event_date, $event_time, $ticket_price, $availability, $image_content, $event_id);

    if (mysqli_stmt_execute($update_stmt)) {
        header("Location: manage_events.php?event_updated=true");
        exit();
    } else {
        echo "<script>alert('Failed to update event. Please try again.'); window.history.back();</script>";
    }

    mysqli_stmt_close($update_stmt);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Sevillana&display=swap" rel="stylesheet"><link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/edit_event.css">
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
                            
                        <li><a href="admin_dashboard.php" class="nav-btn">Home</a></li>
                        
                        <li>
                        <div class="nav-item home-item">
                            <a href="manage_events.php" class="home-button"style="color: #ffcc00;">Events</a>
                            <div class="animal-animation">
                                <img src="img/lion.gif" class="animal" alt="lion Animation">
                            </div>
                        </div>
                    </li>             
                    <li><a href="manage_bookings.php" class="nav-btn">Bookings</a></li>
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
            <h2>Edit Event</h2>
            <form action="edit_event.php?event_id=<?php echo $event_id; ?>" method="POST" enctype="multipart/form-data">
                <label for="event_name">Event Name:</label>
                <input type="text" id="event_name" name="event_name" value="<?php echo htmlspecialchars($event['event_name']); ?>" required>

                <label for="event_date">Event Date:</label>
                <input type="date" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event['event_date']); ?>" required>

                <label for="event_time">Event Time:</label>
                <input type="time" id="event_time" name="event_time" value="<?php echo htmlspecialchars($event['event_time']); ?>" required>

                <label for="ticket_price">Ticket Price:</label>
                <input type="number" step="0.01" id="ticket_price" name="ticket_price" value="<?php echo htmlspecialchars($event['ticket_price']); ?>" required>

                <label for="availability">Availability:</label>
                <input type="number" id="availability" name="availability" value="<?php echo htmlspecialchars($event['availability']); ?>" required>

                <label for="event_image">Event Image:</label>
                <input type="file" name="event_image" id="event_image">
                <?php if (!empty($event['event_image'])): ?>
                    <p>Current Image:</p>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($event['event_image']); ?>" alt="Event Image" style="max-width: 150px; height: auto;">
                <?php endif; ?>

                <button type="submit" name="update_event">Update Event</button>
            </form>
        </main>
    </div>
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





