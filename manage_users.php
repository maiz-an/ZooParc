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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle user deletion
if (isset($_GET['delete_user_id'])) {
    $delete_user_id = $_GET['delete_user_id'];

    $delete_sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($delete_sql);

    if ($stmt) {
        $stmt->bind_param('i', $delete_user_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "<script>alert('User deleted successfully!'); window.location.href='manage_users.php';</script>";
            } else {
                echo "<script>alert('No user found with that ID!'); window.location.href='manage_users.php';</script>";
            }
        } else {
            echo "<script>alert('Error executing delete query: " . $stmt->error . "'); window.location.href='manage_users.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . $conn->error . "'); window.location.href='manage_users.php';</script>";
    }
}

// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Sevillana&display=swap" rel="stylesheet"><link rel="stylesheet" href="style/index.css">
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

        .search-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .search-container a {
            padding: 8px 16px;
            background-color: rgb(234, 57, 13);
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s, color 0.3s;
            border: 3px solid rgb(234, 57, 13);
        }

        .search-container a:hover {
            background-color: whitesmoke;
            color: rgb(234, 57, 13);
            transform: scale(1.1);
        }

        .search-container input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-right: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .actions {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .actions a, .actions button {
            display: inline-block;
            padding: 5px 15px;
            color: #fff;
            background-color: rgb(234, 57, 13);
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-size: 14px;
            width: 60px;
            height: 25px;
            line-height: 25px;
        }

        .actions a:hover, .actions button:hover {
            background-color: #28a745;
        }

        .actions a.delete {
            background-color:rgb(234, 57, 13);
        }

        .actions a.delete:hover{
            background-color: #28a745;
        }

        .actions form {
            width: 100%;
            text-align: center;
        }

        .actions button {
            background-color: black;
            width: 100px;
            margin: 0 auto;
        }
        .btn-lg{
           
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
       .btn-lg:hover{
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
        <a href="add_user.php" class="btn-lg">Add</a>

            <h2>Modify Users</h2>

            <div class="search-container">
                
                <input type="text" id="search" placeholder="Search for users..." onkeyup="searchUsers();">
            </div>

            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <!-- Users will be loaded here via AJAX -->
                </tbody>
            </table>

        </main>
        
        

    <script>
        function searchUsers() {
            var query = document.getElementById('search').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'search_users.php?query=' + encodeURIComponent(query), true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('usersTableBody').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        // Load all users when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            searchUsers();
        });
    </script>

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

