<?php
require_once '../libs/tcpdf/tcpdf.php';

// Create a new PDF document
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('denise');
$pdf->SetTitle('Personal Finance Management App Report');
$pdf->SetSubject('Project Report');

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add content
$html = '
<h1 style="text-align: center;">Personal Finance Management App</h1>
<h3>1. Introduction</h3>
<p>
This project is a Personal Finance Management App designed to help university students manage their finances effectively. It allows users to track expenses, set budgets, and visualize spending patterns through interactive reports.
</p>

<h3>2. System Architecture</h3>
<p>
The app is built using the following technologies:
<ul>
    <li><strong>Frontend:</strong> HTML, CSS, JavaScript (Chart.js)</li>
    <li><strong>Backend:</strong> PHP</li>
    <li><strong>Database:</strong> MySQL</li>
</ul>
</p>

<h3>3. Features</h3>
<p>
The app includes the following features:
<ul>
    <li>User Authentication (Register, Login, Password Recovery)</li>
    <li>Expense Tracking (Log, Edit, Delete Expenses)</li>
    <li>Budget Management (Set and Track Budgets)</li>
    <li>Reporting (Visual Analytics, Export to CSV/PDF)</li>
</ul>
</p>

<h3>4. Challenges</h3>
<p>
During development, challenges such as securing user authentication and implementing responsive design were encountered and resolved successfully.
</p>

<h3>5. Future Improvements</h3>
<p>
Planned improvements include:
<ul>
    <li>Adding AI-based recommendations for budget optimization</li>
    <li>Developing a mobile version using React Native</li>
</ul>
</p>

<h3>6. Conclusion</h3>
<p>
The Personal Finance Management App successfully addresses the financial challenges faced by university students, promoting better financial literacy and management.
</p>
';

// Write the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF (downloadable)
$pdf->Output('project_report.pdf', 'D');
?>
