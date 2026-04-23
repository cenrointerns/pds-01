<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $employee_id = $_POST['employee_id'];
    $file = $_FILES['document'];

    $fileName = $file['name'];
    $fileTmp  = $file['tmp_name'];
    $fileSize = $file['size'];

    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['pdf', 'doc', 'docx'];

    // Validate file type
    if (!in_array($ext, $allowed)) {
        die("Invalid file type. Only PDF, DOC, DOCX allowed.");
    }

    // Validate size (5MB max)
    if ($fileSize > 5 * 1024 * 1024) {
        die("File too large. Max 5MB allowed.");
    }

    // Create uploads folder if not exists
    if (!file_exists("uploads")) {
        mkdir("uploads", 0777, true);
    }

    // Safe file name
    $safeName = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", $fileName);
    $destination = "uploads/" . $safeName;

    if (move_uploaded_file($fileTmp, $destination)) {

        $stmt = $conn->prepare("
            INSERT INTO employee_documents 
            (employee_id, file_name, file_path, file_type)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param("isss", $employee_id, $fileName, $destination, $ext);

        if ($stmt->execute()) {
            echo "✅ Upload successful!";
        } else {
            echo "❌ Database insert error.";
        }

        $stmt->close();

    } else {
        echo "❌ File upload failed.";
    }
}
?>