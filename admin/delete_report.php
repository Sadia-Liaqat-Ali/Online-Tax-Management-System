<?php
session_start();
include('includes/dbconnection.php');

// Check if the admin is logged in
if (strlen($_SESSION['ocasaid'] == 0)) {
    header('location:logout.php');
    exit;
}

// Check if ReportID is set in the URL
if (isset($_GET['ReportID']) && is_numeric($_GET['ReportID'])) {
    $reportid = intval($_GET['ReportID']); // Get ReportID from URL

    // SQL query to delete the report
    $sql = "DELETE FROM tbl_reports WHERE ReportID = :reportid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':reportid', $reportid, PDO::PARAM_INT);
    
    // Execute the query and check if it was successful
    if ($query->execute()) {
        // If deletion is successful, redirect to the dashboard with a success message
        echo "<script>alert('Report deleted successfully'); window.location.href = 'dashboard.php';</script>";
    } else {
        // If deletion failed, show an error message
        echo "<script>alert('Error deleting report'); window.location.href = 'dashboard.php';</script>";
    }
} else {
    // If ReportID is not set or is invalid, redirect to dashboard
    echo "<script>alert('Invalid Report ID'); window.location.href = '_dashboard.php';</script>";
}
?>
