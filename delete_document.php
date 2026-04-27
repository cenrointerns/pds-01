<?php
include "config.php";

$id = $_GET['id'];

$res = $conn->query("SELECT file_path FROM employee_documents WHERE id=$id");
$row = $res->fetch_assoc();

if ($row) {
    unlink($row['file_path']);
    $conn->query("DELETE FROM employee_documents WHERE id=$id");
}

header("Location: documents.php");
?>