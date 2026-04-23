<?php include "config.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Documents</title>

    <style>
       body {
    font-family: 'Segoe UI', Tahoma, sans-serif;

    background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/e/e8/Logo_of_the_Department_of_Environment_and_Natural_Resources.svg/1280px-Logo_of_the_Department_of_Environment_and_Natural_Resources.svg.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;

    margin: 0;
    padding: 20px;
}

.container {
    max-width: 1100px;
    margin: auto;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 8px;
    overflow: hidden;
}

th {
    background: #2d6cdf;
    color: white;
    padding: 12px;
    text-align: left;
    font-size: 14px;
}

td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
}

tr:hover {
    background: #f1f7ff;
}

.badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    background: #e0e0e0;
}

.download-btn {
    background: #28a745;
    color: white;
    padding: 6px 12px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 13px;
}

.download-btn:hover {
    background: #218838;
}

.edit-btn {
    background: #ffc107;
    color: black;
    padding: 6px 12px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 13px;
    margin-left: 5px;
}

.edit-btn:hover {
    background: #e0a800;
}
    </style>
</head>

<body>

<div class="container">

    <h2>📁 Employee Documents</h2>

    <table>
        <tr>
            <th>Employee ID</th>
            <th>Name</th>
            <th>Document Type</th>
            <th>File Name</th>
            <th>Uploaded At</th>
            <th>Action</th>
        </tr>

        <?php
        $sql = "
        SELECT 
            d.id,
            d.employee_id,
            e.name,
            d.document_type,
            d.file_name,
            d.file_path,
            d.uploaded_at
        FROM employee_documents d
        JOIN employees e
        ON d.employee_id = e.employee_id
        ORDER BY d.uploaded_at DESC
        ";

        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td><span class='badge'>{$row['employee_id']}</span></td>
                <td>{$row['name']}</td>
                <td>{$row['document_type']}</td>
                <td>{$row['file_name']}</td>
                <td>{$row['uploaded_at']}</td>
                <td>
                    <a class='download-btn' href='{$row['file_path']}' download>
                        ⬇ Download
                    </a>

                    <a class='edit-btn' href='edit_document.php?id={$row['id']}'>
                        ✏ Edit
                    </a>
                </td>
            </tr>";
        }
        ?>

    </table>

</div>

</body>
</html>