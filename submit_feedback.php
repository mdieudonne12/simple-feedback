<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "feedback_db";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$table="CREATE TABLE feedback (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  rating INT NOT NULL,
  comments TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";

$sql=mysqli_query($conn,$table);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = htmlspecialchars(trim($_POST['name']));
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $rating = (int)$_POST['rating'];
  $comments = htmlspecialchars(trim($_POST['comments']));

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format");
  }

  $sql = "INSERT INTO feedback (name, email, rating, comments) VALUES (?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssis", $name, $email, $rating, $comments);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    echo "<p>Thank you for your feedback!</p>";
    echo '<p><a href="feedback_form.html">Go Back</a></p>';
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}

mysqli_close($conn);
?>
