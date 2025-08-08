<?php
session_start();

$conn = require '../database.php';

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: ../login_page.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Fetch expenses for this user
$stmt = $conn->prepare("SELECT id, expense_name, amount, category, date FROM expense WHERE customer_id = ? ORDER BY date DESC");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Expenses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h2 class="mb-4">My Expenses</h2>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Amount (RM)</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['expense_name']) ?></td>
                    <td><?= number_format($row['amount'], 2) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td>
                        <a href="edit_expense.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_expense.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this expense?')" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No expenses recorded yet.</div>
    <?php endif; ?>

</body>
</html>
