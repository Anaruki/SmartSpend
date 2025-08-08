<?php
session_start();

$conn = require '../database.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../login_page.html");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch expenses for the logged-in user
$stmt = $conn->prepare("SELECT expense_name, amount, category, date FROM expense WHERE customer_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Expenses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4">Your Expenses</h2>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Expense Name</th>
        <th>Amount (RM)</th>
        <th>Category</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['expense_name']) ?></td>
          <td><?= number_format($row['amount'], 2) ?></td>
          <td><?= htmlspecialchars($row['category']) ?></td>
          <td><?= htmlspecialchars($row['date']) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<form action="delete_expense.php"><button type="submit" class="btn btn-primary">Delete Expense</button></form>
</body>
</html>
