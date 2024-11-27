<?php
require_once '../config/db.php';
require_once '../libs/tcpdf/tcpdf.php'; // Adjust path to the TCPDF library
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$current_month = date("Y-m");

// Fetch expenses for the current month
$query = "
    SELECT expense_date, category, amount, description
    FROM expenses
    WHERE user_id = ? AND DATE_FORMAT(expense_date, '%Y-%m') = ?
    ORDER BY expense_date ASC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $current_month);
$stmt->execute();
$result = $stmt->get_result();

// Initialize PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Personal Finance App');
$pdf->SetTitle('Expense Report');
$pdf->SetHeaderData('', '', 'Expense Report', 'Month: ' . $current_month);
$pdf->AddPage();

// Add content
$pdf->SetFont('helvetica', '', 12);
$html = '<h2>Expense Report</h2>';
$html .= '<table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>';
while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
                <td>' . htmlspecialchars($row['expense_date']) . '</td>
                <td>' . htmlspecialchars($row['category']) . '</td>
                <td>' . htmlspecialchars($row['amount']) . '</td>
                <td>' . htmlspecialchars($row['description']) . '</td>
              </tr>';
}
$html .= '</tbody></table>';
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF
$pdf->Output('expenses_' . $current_month . '.pdf', 'D');

$stmt->close();
$conn->close();
?>
