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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $ticket_type = $_POST['ticket_type'];
    $quantity = $_POST['quantity'];
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    // Calculate the total amount based on ticket type and quantity
    $amount = 0;
    if ($ticket_type == 'general') {
        $amount = 50 * $quantity;
    } elseif ($ticket_type == 'vip') {
        $amount = 100 * $quantity;
    } elseif ($ticket_type == 'student') {
        $amount = 30 * $quantity;
    }

    // Simulate payment processing (replace with real payment gateway logic)
    $payment_successful = true; // Assume payment is successful for this example

    if ($payment_successful) {
        $payment_status = 'paid';
        $payment_date = date('Y-m-d H:i:s');
        $user_id = $_SESSION['user_id']; // Get the logged-in user's ID from the session
    
        // Generate a new ticket_id manually, based on the last inserted one
        $result = $conn->query("SELECT MAX(ticket_id) AS max_ticket_id FROM member_ticket_payments");
        $row = $result->fetch_assoc();
        $ticket_id = $row['max_ticket_id'] + 1; // Increment by 1
    
        // Insert payment record with manually managed ticket_id
        $stmt = $conn->prepare("INSERT INTO member_ticket_payments (user_id, ticket_id, amount, payment_status, payment_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('iisss', $user_id, $ticket_id, $amount, $payment_status, $payment_date);
    
        if ($stmt->execute()) {
            // Redirect to the same page with a success parameter
            header("Location: tickets.php?success=1");
            exit();
        } else {
            echo "There was an error processing your payment. Please try again.";
        }
    
        $stmt->close();
    }
    
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Ticket Purchase</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Sevillana&display=swap" rel="stylesheet"><link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/ticket_members.css">
    <link rel="stylesheet" href="style/scroll up music.css">
    <link rel="stylesheet" href="style/ticket-btn.css">
    <link rel="stylesheet" href="style/map-btn.css">
    <link rel="stylesheet" href="style/loader.css">
    <script src="js/loader.js" defer></script>
    <script src="js/audio.js" defer></script>
    <script src="js/upbtn.js" defer></script>

    <script type="text/javascript">
        function showSuccessMessage() {
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success') && urlParams.get('success') == '1') {
                alert("Thank you! Your payment was successful, and your ticket has been purchased.");
                window.location.href = 'tickets.php'; // Redirect back to the same page
            }
        }

        window.onload = showSuccessMessage;
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
      h3{
        color:rgb(234, 57, 13);
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
        position: fixed; /* To position the animals relative to the button */
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
            width: 100%;

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
        <!-- Ticket Payment Form Section -->
        <div class="ticket-form-container">
            <a href="view_my_tickets.php" class="t-btn">My Ticket</a>
            <h2>Purchase Your Ticket</h2>
            <form action="tickets.php" method="POST"> <!-- Make sure this action points to the current file -->
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="ticket_type">Ticket Type:</label>
                <select id="ticket_type" name="ticket_type" required>
                    <option value="general">General Admission - $50</option>
                    <option value="vip">VIP Admission - $100</option>
                    <option value="student">Student Admission - $30</option>
                </select>

                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="1" max="10" required>

                <h3>Payment Information</h3>

                <label for="card_number">Card Number:</label>
                <input type="number" id="card_number" name="card_number" min="1" maxlength="9999999999999999" max="9999999999999999" required>

                <label for="expiry_date">Expiry Date:</label>
                <input type="month" id="expiry_date" name="expiry_date" required>

                <label for="cvv">CVV:</label>
                <input type="number" id="cvv" name="cvv" min="101" minlength="101" max="999" maxlength="999" required>

                <button type="submit" class="btn-sub">Submit Payment</button>
            </form>
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



