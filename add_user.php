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
$db_username = "root";
$db_password = "";
$dbname = "zoo_website";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (name, username, email, phone_number, role, password) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $username, $email, $phone_number, $role, $password);

    if ($stmt->execute()) {
        echo "<script>
                alert('User added successfully');
                window.location.href='manage_users.php';
              </script>";
        exit();
    } else {
        echo "<script>alert('Error adding user. Please try again.');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Sevillana&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/form_table_styles.css">
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
        
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: rgb(234, 57, 13);
            font-size: 35px;
        }
        .modify-btn {
            background-color: rgb(234, 57, 13);
            color: white;
            padding: 10px 20px;
            border: 2px solid transparent;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            display: inline-block;
            width: 100px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, transform 0.3s, color 0.3s;
            border: 3px solid rgb(234, 57, 13);
        }

        .modify-btn:hover {
            background-color: whitesmoke;
            border: 2px solid rgb(234, 57, 13);
            color: rgb(234, 57, 13);
            transform: scale(1.1);
        }
        .add-user-btn {
            background-color:  rgb(234, 57, 13);
            color: white;
            text-decoration: none;
            padding: 0.7em 1.5em;
            transition: background-color 0.3s, transform 0.3s, color 0.3s;
            font-weight: bold;
            border: 2px solid transparent;
            border-radius: 5px;
            display: inline-block;
            border: 3px solid rgb(234, 57, 13);
        }

        .add-user-btn:hover {
            background-color: whitesmoke;
            border: 2px solid  rgb(234, 57, 13);
            color: rgb(234, 57, 13);
            transform: scale(1.1);
        }
        .btn-lg {
            width: 10%;
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
       .btn-lg:hover {
           background-color: rgb(234, 57, 13);
           border: 2px solid rgb(234, 57, 13);
           color: white;
           transform: scale(1.05);
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
                <h1>Zoo Parc</h1>
                <nav>
                    <ul>
                        <li><a href="admin_dashboard.php" class="nav-btn">Home</a></li>
                        <li><a href="manage_events.php" class="nav-btn">Events</a></li>
                        <li><a href="manage_bookings.php" class="nav-btn">Bookings</a></li>
                        <li>   
                            <div class="nav-item home-item">
                                <a href="manage_users.php" class="home-button" style="color: #ffcc00;">Users</a>
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
        <button class="btn-lg" onclick="window.location.href='manage_users.php'">Modify</button>
        <h2>Add User</h2>
        <form method="POST" action="add_user.php">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br>

            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>

            <label for="phone_number">Phone Number:</label><br>
            <input type="text" id="phone_number" name="phone_number" required><br>

            <label for="role">Role:</label><br>
            <select id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="member">Member</option>
            </select><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <input type="submit" class="add-user-btn" name="add_user" value="Add User">
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


