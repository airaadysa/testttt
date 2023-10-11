<?php
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['code'])) {
    $verificationCode = $_GET['code'];
  
    // Connect to your database
    $conn = new mysqli('localhost', 'root', '', 'test');
    if ($conn->connect_error) {
      echo "$conn->connect_error";
      die("Connection Failed: " . $conn->connect_error);
    }
  
    // Check if the verification code exists in the database
    $stmt = $conn->prepare("SELECT email FROM registration WHERE verification_code = ?");
    $stmt->bind_param("s", $verificationCode);
    $stmt->execute();
    $stmt->store_result();
  
    if ($stmt->num_rows > 0) {
      // Verification successful, update the user status or perform any other actions
      $stmt->bind_result($email);
      $stmt->fetch();
  
      // Update the user status as verified in your database
      $updateStmt = $conn->prepare("UPDATE registration SET status = 'verified' WHERE email = ?");
      $updateStmt->bind_param("s", $email);
      $updateStmt->execute();
  
      echo "Your email has been verified. You can now <a href='login.php'>log in</a>.";
    } else {
      echo "Invalid verification code.";
    }
  
    $stmt->close();
    $conn->close();
  } else {
    echo "Invalid request.";
  }
  
?>
