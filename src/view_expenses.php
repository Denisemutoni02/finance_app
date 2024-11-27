<?php
require_once '../config/db.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Retrieve filters and search inputs
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$start_date = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build the query dynamically
$query = "SELECT * FROM expenses WHERE user_id = ?";
$conditions = [];
$params = [$user_id];
$types = "i";

if (!empty($category)) {
    $conditions[] = "category = ?";
    $params[] = $category;
    $types .= "s";
}

if (!empty($start_date)) {
    $conditions[] = "expense_date >= ?";
    $params[] = $start_date;
    $types .= "s";
}

if (!empty($end_date)) {
    $conditions[] = "expense_date <= ?";
    $params[] = $end_date;
    $types .= "s";
}

if (!empty($search_term)) {
    $conditions[] = "(description LIKE ? OR category LIKE ?)";
    $params[] = "%" . $search_term . "%";
    $params[] = "%" . $search_term . "%";
    $types .= "ss";
}

if (!empty($conditions)) {
    $query .= " AND " . implode(" AND ", $conditions);
}

$query .= " ORDER BY expense_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$expenses = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM expenses WHERE user_id = ? ORDER BY expense_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$expenses = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>
