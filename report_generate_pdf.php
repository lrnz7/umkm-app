<?php
require_once __DIR__ . '/config/koneksi.php';

// Include FPDF library
require_once __DIR__ . '/lib/fpdf/fpdf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;

    if (!$start_date || !$end_date) {
        die('Start date and end date are required.');
    }

    // Fetch summary data
    // Total Income In
    $sql_income = "SELECT COALESCE(SUM(total_amount), 0) as total FROM transactions WHERE DATE(transaction_date) BETWEEN ? AND ? AND transaction_type = 'Income'";
    $stmt_income = mysqli_prepare($koneksi, $sql_income);
    mysqli_stmt_bind_param($stmt_income, "ss", $start_date, $end_date);
    mysqli_stmt_execute($stmt_income);
    $result_income = mysqli_stmt_get_result($stmt_income);
    $row_income = mysqli_fetch_assoc($result_income);
    $total_income = $row_income['total'];

    // Total Expenses
    $sql_expenses = "SELECT COALESCE(SUM(amount), 0) as total FROM expenses WHERE DATE(expense_date) BETWEEN ? AND ?";
    $stmt_expenses = mysqli_prepare($koneksi, $sql_expenses);
    mysqli_stmt_bind_param($stmt_expenses, "ss", $start_date, $end_date);
    mysqli_stmt_execute($stmt_expenses);
    $result_expenses = mysqli_stmt_get_result($stmt_expenses);
    $row_expenses = mysqli_fetch_assoc($result_expenses);
    $total_expenses = $row_expenses['total'];

    // Total Transactions
    $sql_transactions = "SELECT COUNT(*) as count FROM transactions WHERE DATE(transaction_date) BETWEEN ? AND ?";
    $stmt_transactions = mysqli_prepare($koneksi, $sql_transactions);
    mysqli_stmt_bind_param($stmt_transactions, "ss", $start_date, $end_date);
    mysqli_stmt_execute($stmt_transactions);
    $result_transactions = mysqli_stmt_get_result($stmt_transactions);
    $row_transactions = mysqli_fetch_assoc($result_transactions);
    $total_transactions = $row_transactions['count'];

    // Total Products Sold
    $sql_products = "SELECT SUM(td.quantity) as total FROM transactions t JOIN transaction_details td ON t.transaction_id = td.transaction_id WHERE DATE(t.transaction_date) BETWEEN ? AND ?";
    $stmt_products = mysqli_prepare($koneksi, $sql_products);
    mysqli_stmt_bind_param($stmt_products, "ss", $start_date, $end_date);
    mysqli_stmt_execute($stmt_products);
    $result_products = mysqli_stmt_get_result($stmt_products);
    $row_products = mysqli_fetch_assoc($result_products);
    $total_products = $row_products['total'] ?? 0;

    // Create PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Title
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Summarize Report', 0, 1, 'C');

    // Date Range
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Period: $start_date to $end_date", 0, 1, 'C');

    $pdf->Ln(10);

    // Summary Table
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(80, 10, 'Metric', 1);
    $pdf->Cell(80, 10, 'Value', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(80, 10, 'Total Income', 1);
    $pdf->Cell(80, 10, 'Rp ' . number_format($total_income, 0, ',', '.'), 1);
    $pdf->Ln();

    $pdf->Cell(80, 10, 'Total Expenses', 1);
    $pdf->Cell(80, 10, 'Rp ' . number_format($total_expenses, 0, ',', '.'), 1);
    $pdf->Ln();

    $pdf->Cell(80, 10, 'Total Transactions', 1);
    $pdf->Cell(80, 10, $total_transactions, 1);
    $pdf->Ln();

    $pdf->Cell(80, 10, 'Products Sold', 1);
    $pdf->Cell(80, 10, $total_products, 1);
    $pdf->Ln();

    // Output PDF
    $pdf->Output('D', 'Summarize_Report_' . $start_date . '_to_' . $end_date . '.pdf');
    exit;
} else {
    die('Invalid request method.');
}
?>
