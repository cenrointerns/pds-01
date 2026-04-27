<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Documents Portal</title>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            text-decoration: none;
            color: #333;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .card i {
            font-size: 30px;
            margin-bottom: 15px;
            color: #007BFF;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
            background-color: #007BFF;
            color: #fff;
        }

        .card:hover i {
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>HR Documents Dashboard</h1>

    <div class="grid">
        <a href="pds.html" class="card">
            <i class="fas fa-id-card"></i>
            <div>Personal Data Sheet (PDS)</div>
        </a>

        <a href="saln.html" class="card">
            <i class="fas fa-file-invoice-dollar"></i>
            <div>SALN</div>
        </a>

        <a href="ipc.html" class="card">
            <i class="fas fa-user-check"></i>
            <div>IPC</div>
        </a>

        <a href="opc.html" class="card">
            <i class="fas fa-building"></i>
            <div>OPC</div>
        </a>

        <a href="ipcr.html" class="card">
            <i class="fas fa-chart-line"></i>
            <div>IPCR</div>
        </a>

        <a href="opcr.html" class="card">
            <i class="fas fa-chart-bar"></i>
            <div>OPCR</div>
        </a>

        <a href="special-order.html" class="card">
            <i class="fas fa-gavel"></i>
            <div>Special Order</div>
        </a>

        <a href="reporting-duty.html" class="card">
            <i class="fas fa-briefcase"></i>
            <div>Reporting for Duty</div>
        </a>

        <a href="memo.html" class="card">
            <i class="fas fa-envelope"></i>
            <div>Memorandum Issued</div>
        </a>

        <a href="idp.html" class="card">
            <i class="fas fa-lightbulb"></i>
            <div>IDP</div>
        </a>

        <a href="appointment.html" class="card">
            <i class="fas fa-user-tie"></i>
            <div>Appointment</div>
        </a>

        <a href="clearance.html" class="card">
            <i class="fas fa-check-circle"></i>
            <div>Office Clearance</div>
        </a>
    </div>
</div>

</body>
</html>