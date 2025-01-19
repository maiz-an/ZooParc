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

// Fetch user details based on user_id from the URL
$user_id = $_GET['user_id'] ?? null;

if ($user_id) {
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit();
    }

    $stmt->close();
} else {
    echo "No user ID provided.";
    exit();
}
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
    
        /* Your existing styles */
        main {
            padding: 2em 1em;
            max-width: 900px;
            margin: 2em auto;
            background-color: whitesmoke;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding-bottom: 40px;
            position: relative;
            height: 750px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: rgb(234, 57, 13);
            font-size: 30px;
        }
        .go-back-btn {
            background-color: rgb(234, 57, 13);
            color: white;
            padding: 10px 20px;
            border: 2px solid transparent;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            display: inline-block;
            width: 150px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, transform 0.3s, color 0.3s;
            border: 3px solid rgb(234, 57, 13);
        }
        .go-back-btn:hover {
            background-color: whitesmoke;
            border: 2px solid rgb(234, 57, 13);
            color: rgb(234, 57, 13);
            transform: scale(1.1);
        }
        form {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }
        input[type="text"], input[type="number"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .form-actions {
            text-align: center;
        }
        .btn {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #555;
        }
        .cancel-btn {
            background-color: #e74c3c;
        }
        .cancel-btn:hover {
            background-color: #c0392b;
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
          <a href="index.html" class="logo">
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
                            <li>   
                        <div class="nav-item home-item">
                                <a href="manage_users.php" class="home-button"style="color: #ffcc00;">Users</a>
                                <div class="animal-animation">
                                    <img src="img/lion.gif" class="animal" alt="lion Animation">
                                </div>
                            </div>
                        </li>                        
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
            <a href="manage_users.php" class="go-back-btn">Go Back</a>
            <h2>Edit User</h2>
            <form action="update_user.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">

                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>

                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="member" <?php echo ($user['role'] === 'member') ? 'selected' : ''; ?>>Member</option>
                </select>

                <div class="form-actions">
                    <button type="submit" class="btn">Update User</button>
                    <a href="manage_users.php" class="btn cancel-btn">Cancel</a>
                </div>
            </form>
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


