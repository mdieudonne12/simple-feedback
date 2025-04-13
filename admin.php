<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
      $_POST['username'] === 'admin' &&
      $_POST['password'] === 'password') {
    $_SESSION['admin_logged_in'] = true;
  } else {
    echo '<form method="POST">
            <input name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
          </form>';
    exit;
  }
}

$conn = mysqli_connect('localhost', 'root', '', 'feedback_db');
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$result = mysqli_query($conn, "SELECT * FROM feedback ORDER BY created_at DESC");

echo '<link rel="stylesheet" href="style.css">';
echo '<div class="container">';
echo '<h2>Admin Feedback Dashboard</h2>';
echo '<table border="1" cellpadding="10">';
echo '<tr><th>Name</th><th>Email</th><th>Rating</th><th>Comments</th><th>Date</th></tr>';

while ($row = mysqli_fetch_assoc($result)) {
  $class = $row['rating'] >= 4 ? 'rating-positive' : ($row['rating'] <= 2 ? 'rating-negative' : '');
  echo "<tr class=\"$class\">
          <td>{$row['name']}</td>
          <td>{$row['email']}</td>
          <td>{$row['rating']}</td>
          <td>{$row['comments']}</td>
          <td>{$row['created_at']}</td>
        </tr>";
}
echo '</table></div>';

mysqli_close($conn);
?>
