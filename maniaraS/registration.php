<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/PHPMailer/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/PHPMailer/src/SMTP.php';

require 'vendor/autoload.php';

$registrationMessage = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $number = $_POST['number'];

  if (!empty($username) && !empty($email) && !empty($password) && !empty($number)) {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'test');
    if ($conn->connect_error) {
      echo "$conn->connect_error";
      die("Connection Failed: " . $conn->connect_error);
    } else {
      $verifyCode = md5(uniqid(rand(), true));

      // Save user data and verification code in the database
      $stmt = $conn->prepare("INSERT INTO registration (username, email, password, number, verification_code) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param("sssis", $username, $email, $password, $number, $verifyCode);
      $execval = $stmt->execute();

      if ($execval) {
        $mail = new PHPMailer(true);

        try {
          $mail->SMTPDebug = 0;
          $mail->isSMTP();
          $mail->Host = 'smtp.gmail.com';
          $mail->SMTPAuth = true;
          $mail->Username = 'airajoyawing@gmail.com';
          $mail->Password = 'fhxmdceqdoxdkain';
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
          $mail->Port = 465;

          $mail->setFrom('from@example.com', 'Aira Awing');
          $mail->addAddress($email);

          $mail->isHTML(true);
          $mail->Subject = 'Email Verification';
          $vLink = 'http://localhost/maniara/verify.php?code=' . $verifyCode; 
          $mail->Body = "Click to verify your account: <a href='$vLink'>Verify</a>";

          $mail->send();

          // Registration completion message
          $registrationMessage = "Registration complete, please check your email to verify your account.";
        } catch (Exception $e) {
          $error['email'] = "Email verification email could not be sent.";
        }
      } else {
        $error['database'] = "Error occurred while registering.";
      }
      $stmt->close();
      $conn->close();
    }
  } else {
    $error['fields'] = "Please fill in all the required fields.";
  }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title> Registration Page </title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <div class="container">
      <div class="title">Registration Form</div>
      <div class="content">
        
        <form action="" method="post">
          
          <div class="user-details">
            <div class="input-box">
              <span class="details">Username</span>
              <input type="text" placeholder="Enter your first name"  id="username" name="username">
            </div>   
            <div class="input-box">
              <span class="details">Email Address</span>
              <input type="text" placeholder="Enter your email" id="email" name="email">
            </div>
            <div class="input-box">
              <span class="details">Phone Number</span>
              <input type="text" placeholder="Enter your number"id="number" name="number">
            </div>
            <div class="input-box">
              <span class="details">Password</span>
              <input type="password" placeholder="Enter your password" id="password" name="password">
            </div>
          </div>
         
          <div class="button">
            <button class="submit-info" type="submit">Register</button>
          </div>

        </form>

        <!-- Registration completion message -->
        <div class="registration-message">
          <p><?php echo $registrationMessage; ?></p>
        </div>

        <div class="login-link">
          <p>Already have an account? <a href="login.php">Sign In</a></p>
        </div>
      </div>
    </div>
  </body>
</html>
