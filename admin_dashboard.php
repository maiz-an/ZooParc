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
$password = "";
$dbname = "zoo_website";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all bookings to manage
$sql = "SELECT b.booking_id, e.event_name, b.user_id, b.quantity, b.total_price, b.status, b.booking_date
        FROM bookings b
        JOIN events e ON b.event_id = e.event_id
        ORDER BY b.booking_date DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Bookings</title>
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



  .main1 {
    padding: 2em 1em;
    max-width: 70%;
    height: fit-content;
    margin: 2em auto;
    background-color: whitesmoke;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding-top: 2px;
}

/* Custom styles for image */
.content img {
    width: 90%; /* Increase the width */
    max-width: 1500px; /* Optional: Set a larger maximum width */
    height: 600px; /* Set a specific height to reduce the image height */
    object-fit: cover; /* Ensures the image covers the area without distortion */
    display: block;
    margin: 20px auto; /* Center the image with some margin */
    border-radius: 10px; /* Optional: Rounded corners */
}

/* Style for h2 with center alignment and color */
.special-font {
    text-align: center; /* Center-align the text */
    color: #ff4500; /* Add color (e.g., OrangeRed) */
    font-size: 2.5em; /* Adjust the font size if needed */
    margin-top: 20px; /* Add some space above */
    margin-bottom: 20px; /* Add some space below */
    font-weight: bold; /* Make the text bold */
    font-family: 'Arial', sans-serif; /* Change the font if desired */
}
/* Style for p elements */
.main1 p {
    font-size: 1.2em; /* Increase the font size */
    line-height: 1.6; /* Adjust line height for better readability */
    color: #333; /* Darker color for better contrast */
    margin-bottom: 20px; /* Space between paragraphs */
}

/* Specific style for the first p element after "Dear Admin" */
.main1 p:first-of-type + p {
    margin-top: 5px; /* Reduce the top margin to minimize the gap */
}

/* Hover effect for the image */
.content img:hover {
    transform: scale(1.02); /* Slightly increase the size */
    transition: transform 0.3s ease; /* Smooth transition */
}

/* Hover effect for the main1 section */
.main1:hover {
    transform: scale(1.01); /* Slightly increase the size */
    transition: transform 0.3s ease; /* Smooth transition */
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
                <a href="admin_dashboard.php" class="logo">
                    <img src="img/logo.png" alt="Zoo Parc Logo">
                </a>
                <div class="title-nav">
                    <h1>Zoo Parc
                    </h1>
                    <nav>
                        <ul>
                            
                        <li>
                            <div class="nav-item home-item">
                                <a href="admin_dashboard.php" class="home-button"style="color: #ffcc00;">Home</a>
                                <div class="animal-animation">
                                    <img src="img/lion.gif" class="animal" alt="lion Animation">
                                </div>
                            </div>
                        </li>
                        
                    <li><a href="manage_events.php" class="nav-btn">Events</a></li>
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



        
            
                <section class="main1">
                <h2 class="special-font">Welcome to Zoo Parc Admin Dashboard</h2>
                 <p>
                  Welcome to the Zoo Parc Admin Dashboard. Here, you have full control over managing the zoo's events, bookings, users, ticket sales. 
                  Your role is crucial in ensuring that our visitors have a seamless and enjoyable experience at Zoo Parc.
                </p>
                <section class="container-new">
                <div class="content">
                    <img src="img/zooparc.jpeg" alt="Zooparc"> <!-- Image with increased width and reduced height -->
                </div>
            </section>
            </section>
           

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
