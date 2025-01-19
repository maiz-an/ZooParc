<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['usernameOrEmail'];
    $newPassword = $_POST['newPassword'];
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = ""; // Replace with your database password
    $dbname = "zoo_website";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Validate and process the email address or username
    $sql = "SELECT user_id FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update the password in the database
        $stmt->close();

        $sql_update = "UPDATE users SET password = ? WHERE email = ? OR username = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sss", $hashedPassword, $email, $email);
        $stmt_update->execute();

        if ($stmt_update->affected_rows > 0) {
            $_SESSION['reset_success'] = true;
            header('Location: forgot_password.php');
            exit();
        } else {
            $error = "Failed to update the password. Please try again.";
        }

        $stmt_update->close();
    } else {
        $error = "Email address or username not found.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoo Parc</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Link to New CSS -->
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/auth_style.css">
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
      .btn-sub {
            background-color: #d23939;
            color: white;
            border: 2px solid  #d23939;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            width: 100%;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
        }
        .btn-sub:hover{
            background-color: white;
            color: #d23939;
            border: 2px solid  #d23939;

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

        p {
            margin-top: 25px;
            text-align: center;
        }
  </style>
  <script>
    function validatePasswords() {
        var newPassword = document.getElementById("newPassword").value;
        var confirmNewPassword = document.getElementById("confirmNewPassword").value;

        if (newPassword !== confirmNewPassword) {
            alert("Passwords do not match!");
            return false; // Prevent form submission
        }
        return true; // Allow form submission if passwords match
    }

    document.addEventListener("DOMContentLoaded", function () {
        <?php if (isset($_SESSION['reset_success']) && $_SESSION['reset_success']): ?>
            alert("Password has been successfully reset.");
            <?php unset($_SESSION['reset_success']); // Clear the message after displaying it ?>
        <?php endif; ?>
    });
</script>


</head>
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
                        <li><a href="index.html">Home</a></li>                           
                        <li><a href="AboutUs.html">About Us</a></li>
                        <li><a href="Events.html">Events</a></li>
                        <li><a href="Gallery.html">Gallery</a></li>
                        <li><a href="FoodOutlets.html">Dining</a></li>
                        <li>
                            <div class="nav-item home-item">
                                <a href="Login.html" class="home-button"style="color: #ffcc00;">Login</a>
                                <div class="animal-animation">
                                    <img src="img/lion.gif" class="animal" alt="lion Animation">
                                </div>
                            </div>
                        </li>
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
    <div class="forgot-password-container">
        <h2>Forgot Password</h2>
        
        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="forgot_password.php" method="POST" onsubmit="return validatePasswords()">
            <label for="usernameOrEmail">Username or Email:</label>
            <input type="text" id="usernameOrEmail" name="usernameOrEmail" required>

            <label for="newPassword">New Password:</label>
            <input type="password" id="newPassword" name="newPassword" required>

            <label for="confirmNewPassword">Confirm New Password:</label>
            <input type="password" id="confirmNewPassword" name="confirmNewPassword" required> <br><br>

            <button type="submit" class="btn-sub">Reset Password</button>
        </form>
        <div class="login-link">
           <p> Remembered your password? <a href="login.html"  class="btn-lg">Login here</a></p>
        </div>
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
          <a href="index.html" class="quick-link-btn">Home</a>|
          <a href="AboutUs.html" class="quick-link-btn">About Us</a>|
          <a href="Gallery.html" class="quick-link-btn">Gallery</a>|
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
        <a href="Tickets.html" class="cart-btn">
            <img src="img/ticket-icon.png" alt="Buy Ticket">
        </a>

        <!-- Floating Map Button -->
     <div class="floating-map">
        <a href="ZooMap.html" class="map-btn">
            <img src="img/map.gif" alt="Zoo Map">
        </a>

</html>
