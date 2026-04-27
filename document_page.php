<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Documents Portal</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
    font-family: 'Segoe UI', Tahoma, sans-serif;

    /* 🔥 Background image */
    background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/e/e8/Logo_of_the_Department_of_Environment_and_Natural_Resources.svg/1280px-Logo_of_the_Department_of_Environment_and_Natural_Resources.svg.png'); /* change path here */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    margin: 0;
    padding: 20px;
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
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            text-decoration: none;
            color: #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .card i {
            font-size: 30px;
            margin-bottom: 15px;
        }

        .card:hover {
            transform: translateY(-5px);
            opacity: 0.9;
        }

        /* Different colors per card */
        .pds { background: #4e73df; }
        .saln { background: #1cc88a; }
        .ipc { background: #36b9cc; }
        .opc { background: #f6c23e; }
        .ipcr { background: #e74a3b; }
        .opcr { background: #6f42c1; }
        .special { background: #fd7e14; }
        .duty { background: #20c997; }
        .memo { background: #858796; }
        .idp { background: #17a2b8; }
        .appointment { background: #6610f2; }
        .clearance { background: #28a745; }

    </style>
</head>
<body>

<div class="container">
    <h1>HR Documents Dashboard</h1>

    <div class="grid">
        <a href="pds.html" class="card pds">
            <i class="fas fa-id-card"></i>
            <div>Personal Data Sheet (PDS)</div>
        </a>

        <a href="saln.html" class="card saln">
            <i class="fas fa-file-invoice-dollar"></i>
            <div>SALN</div>
        </a>

        <a href="ipc.html" class="card ipc">
            <i class="fas fa-user-check"></i>
            <div>IPC</div>
        </a>

        <a href="opc.html" class="card opc">
            <i class="fas fa-building"></i>
            <div>OPC</div>
        </a>

        <a href="ipcr.html" class="card ipcr">
            <i class="fas fa-chart-line"></i>
            <div>IPCR</div>
        </a>

        <a href="opcr.html" class="card opcr">
            <i class="fas fa-chart-bar"></i>
            <div>OPCR</div>
        </a>

        <a href="special-order.html" class="card special">
            <i class="fas fa-gavel"></i>
            <div>Special Order</div>
        </a>

        <a href="reporting-duty.html" class="card duty">
            <i class="fas fa-briefcase"></i>
            <div>Reporting for Duty</div>
        </a>

        <a href="memo.html" class="card memo">
            <i class="fas fa-envelope"></i>
            <div>Memorandum Issued</div>
        </a>

        <a href="idp.html" class="card idp">
            <i class="fas fa-lightbulb"></i>
            <div>IDP</div>
        </a>

        <a href="appointment.html" class="card appointment">
            <i class="fas fa-user-tie"></i>
            <div>Appointment</div>
        </a>

        <a href="clearance.html" class="card clearance">
            <i class="fas fa-check-circle"></i>
            <div>Office Clearance</div>
        </a>
    </div>
</div>

</body>
</html>