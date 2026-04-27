<?php
include "config.php";

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$id = $_GET['id'];

/* Get current data */
$stmt = $conn->prepare("SELECT * FROM documents WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Document not found.");
}

$doc = $result->fetch_assoc();

/* Update */
if (isset($_POST['update'])) {

    $new_type = $_POST['document_type'];

    $stmt = $conn->prepare("UPDATE documents SET document_type = ? WHERE id = ?");
    $stmt->bind_param("si", $new_type, $id);

    if ($stmt->execute()) {
        header("Location: documents.php?updated=1");
        exit;
    } else {
        echo "Update failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Document</title>
</head>
<body style="font-family:Arial; padding:40px;">

<h2>Edit Document</h2>

<form method="POST">

    <label>Document Type</label><br>

    <select name="document_type" required>
        <option value="PDS" <?= $doc['document_type']=="PDS"?'selected':'' ?>>PDS</option>
        <option value="SALN" <?= $doc['document_type']=="SALN"?'selected':'' ?>>SALN</option>
        <option value="IPC" <?= $doc['document_type']=="IPC"?'selected':'' ?>>IPC</option>
        <option value="OPC" <?= $doc['document_type']=="OPC"?'selected':'' ?>>OPC</option>
        <option value="IPCR" <?= $doc['document_type']=="IPCR"?'selected':'' ?>>IPCR</option>
        <option value="OPCR" <?= $doc['document_type']=="OPCR"?'selected':'' ?>>OPCR</option>
        <option value="Special Order" <?= $doc['document_type']=="Special Order"?'selected':'' ?>>Special Order</option>
        <option value="Reporting for Duty" <?= $doc['document_type']=="Reporting for Duty"?'selected':'' ?>>Reporting for Duty</option>
        <option value="Memorandum" <?= $doc['document_type']=="Memorandum"?'selected':'' ?>>Memorandum</option>
        <option value="IDP" <?= $doc['document_type']=="IDP"?'selected':'' ?>>IDP</option>
        <option value="Appointment" <?= $doc['document_type']=="Appointment"?'selected':'' ?>>Appointment</option>
        <option value="Office Clearance" <?= $doc['document_type']=="Office Clearance"?'selected':'' ?>>Office Clearance</option>
    </select>

    <br><br>

    <button type="submit" name="update">Update</button>
</form>

</body>
</html>