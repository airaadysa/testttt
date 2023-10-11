<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Connect to your database
  $conn = new mysqli('localhost', 'username', 'password', 'database_name');

  if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
  }

  // Perform user authentication by querying the database
  $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Authentication successful
    session_start(); // Start a session
    $_SESSION['email'] = $email; // Store user information in the session
    header("Location: dashboard.php"); // Redirect to the dashboard
    exit;
  } else {
    // Authentication failed
    $error = "Invalid email or password";
  }

  $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <div class="container">
      <div class="title">Login Form</div>
      <div class="content">
        <form action="login_process.php" method="post">
          <div class="user-details">
            <div class="input-box">
              <span class="details">Email Address</span>
              <input type="text" placeholder="Enter your email" required id="email" name="email">
            </div>
            <div class="input-box">
              <span class="details">Password</span>
              <input type="password" placeholder="Enter your password" required id="password" name="password">
            </div>
          </div>
          <div class="button">
            <button class="submit-info" type="submit">Login</button>
          </div>
          <div class="register-link">
            <p>Don't have an account? <a href="registration.php">Sign Up</a></p>
          </div>
        </form>
       
      </div>
    </div>
  </body>
</html>
