<?php
session_start();

// Assuming $sql is your prepared statement and it has been prepared correctly
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
          animation: blink 0.09s infinite; 
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
        }

        .error {
            border-color: red;
        }

        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .success-message {
            color: green;
            font-size: 14px;
            margin-top: 5px;
            display: block;
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle .fa-eye {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        #password-strength {
            font-size: 12px;
            margin-top: 5px;
        }
  </style>
  

  <script>
        function validateForm() {
            var valid = true;
            var username = document.getElementById('username');
            var email = document.getElementById('email');
            var password = document.getElementById('password');
            var phoneNumber = document.getElementById('phone_number');

            

            clearErrors();

            if (username.value.trim() === "") {
                showError(username, "Username must be filled out");
                valid = false;
            }

            if (email.value.trim() === "") {
                showError(email, "Email must be filled out");
                valid = false;
            } else if (!validateEmail(email.value)) {
                showError(email, "Invalid email format");
                valid = false;
            }

            if (password.value.trim() === "") {
                showError(password, "Password must be filled out");
                valid = false;
            } else if (password.value.length < 8) {
                showError(password, "Password must be at least 8 characters long");
                valid = false;
            } else if (!/[A-Z]/.test(password.value)) {
                showError(password, "Password must contain at least one uppercase letter");
                valid = false;
            } else if (!/[a-z]/.test(password.value)) {
                showError(password, "Password must contain at least one lowercase letter");
                valid = false;
            } else if (!/[0-9]/.test(password.value)) {
                showError(password, "Password must contain at least one number");
                valid = false;
            } else if (!/[!@#$%^&*(),.?":{}|<>]/.test(password.value)) {
                showError(password, "Password must contain at least one special character");
                valid = false;
            }

            if (phoneNumber.value.trim() === "") {
                showError(phoneNumber, "Phone number must be filled out");
                valid = false;
            } else if (phoneNumber.value.length < 9) {
                showError(phoneNumber, "Enter a valid phone number");
                valid = false;
            }

            return valid;
        }

        function validateEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function showError(input, message) {
            var error = document.createElement('span');
            error.className = 'error-message';
            error.innerText = message;
            input.parentNode.insertBefore(error, input.nextSibling);
            input.classList.add('error');
        }

        function clearErrors() {
            var errors = document.querySelectorAll('.error-message');
            errors.forEach(function (error) {
                error.remove();
            });

            var inputs = document.querySelectorAll('.error');
            inputs.forEach(function (input) {
                input.classList.remove('error');
            });
        }

        function togglePassword() {
            var passwordField = document.getElementById('password');
            var toggleIcon = document.getElementById('togglePassword');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        function updatePasswordStrength() {
            var password = document.getElementById('password').value;
            var strengthText = document.getElementById('password-strength');

            if (password.length >= 8) {
                strengthText.textContent = "Password strength: Strong";
                strengthText.style.color = "green";
            } else if (password.length >= 4) {
                strengthText.textContent = "Password strength: Medium";
                strengthText.style.color = "orange";
            } else {
                strengthText.textContent = "Password strength: Weak";
                strengthText.style.color = "red";
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
        <?php if (isset($_SESSION['success'])): ?>
            alert("<?php echo $_SESSION['success']; ?>");
            <?php unset($_SESSION['success']); // Clear the message after displaying it ?>
        <?php elseif (isset($_SESSION['error'])): ?>
            alert("<?php echo $_SESSION['error']; ?>");
            <?php unset($_SESSION['error']); // Clear the message after displaying it ?>
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
                <section id="auth">
                    <div class="auth-toggle"></div>
                    <div id="login-form-container">
                        <h2>Create an Account</h2>
                        <form action="register.php" method="post" onsubmit="return validateForm()">
                            <input type="hidden" name="add_user" value="1">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" required><br>

                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" required><br>

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required><br>

                            <label for="password">Password:</label>
                            <div class="password-toggle">
                                <input type="password" id="password" name="password" required oninput="updatePasswordStrength()">
                                <i class="fa fa-eye" id="togglePassword" onclick="togglePassword()"></i>
                            </div>
                            <div id="password-strength"> Password strength: Weak</div><br>

                            <label for="phone_number">Phone Number:</label>
                            <input type="text" id="phone_number" name="phone_number" required><br><br>

                            <button type="submit" class="btn-sub">Register</button>
                        </form>
                        <p>Already registered? <a href="login.html" class="btn-lg">Login here</a></p>
                    </div>
                </section>
            </main>
       
<br>
    
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
