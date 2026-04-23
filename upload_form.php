<?php include "config.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Employee Document</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 60px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            color: #34495e;
        }

        select, input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
        }

        select:focus, input[type="file"]:focus {
            border-color: #3498db;
        }

        .btn {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #2980b9;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .subtext {
            text-align: center;
            color: #7f8c8d;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #3498db;
            text-decoration: none;
            font-size: 14px;
        }

        .back:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h2>📤 Upload Employee Document</h2>
    </div>

    <div class="subtext">
        Select employee and upload their required document
    </div>

    <form action="upload.php" method="POST" enctype="multipart/form-data">

        <label>Select Employee</label>
        <select name="employee_id" required>
            <option value="">-- Select Employee --</option>

            <?php
            $sql = "SELECT employee_id, name FROM employees ORDER BY name ASC";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['employee_id']}'>
                        {$row['employee_id']} - {$row['name']}
                      </option>";
            }
            ?>
        </select>

        <label>Document Type</label>
        <select name="document_type" required>
            <option value="">-- Select Document Type --</option>
            <option value="Resume">Resume</option>
            <option value="Contract">Contract</option>
            <option value="ID">ID</option>
            <option value="Certificate">Certificate</option>
            <option value="Other">Other</option>
        </select>

        <label>Choose File</label>
        <input type="file" name="document" accept=".pdf,.doc,.docx" required>

        <button type="submit" class="btn">⬆ Upload Document</button>

    </form>

    <a href="documents.php" class="back">← Back to Documents</a>

</div>

</body>
</html>