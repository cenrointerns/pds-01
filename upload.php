<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $employee_id = $_POST['employee_id'];
    $document_type = $_POST['document_type'];

    $file = $_FILES['document'];

    $fileName = basename($file['name']);
    $fileTmp  = $file['tmp_name'];
    $fileSize = $file['size'];

    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['pdf', 'doc', 'docx'];

    // Validate type
    if (!in_array($ext, $allowed)) {
        die("Invalid file type. Only PDF, DOC, DOCX allowed.");
    }

    // Validate size (5MB max)
    if ($fileSize > 5 * 1024 * 1024) {
        die("File too large. Max 5MB allowed.");
    }

    // Create upload folder
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Safe unique file name
    $safeName = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", $fileName);
    $destination = $uploadDir . $safeName;

    if (move_uploaded_file($fileTmp, $destination)) {

        // ✅ FIXED TABLE NAME: documents
        $stmt = $conn->prepare("
            INSERT INTO documents
            (employee_id, file_name, document_type, file_path, file_type)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "issss",
            $employee_id,
            $fileName,
            $document_type,
            $destination,
            $ext
        );

        if ($stmt->execute()) {
            header("Location: documents.php?success=1");
            exit;
        } else {
            echo "Database error: " . $stmt->error;
        }

        $stmt->close();

    } else {
        echo "File upload failed.";
    }
}
?>