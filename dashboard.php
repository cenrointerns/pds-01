<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <title>DENR Dashboard</title>

    <!-- Modal Styles -->
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }

        .modal-content {
            background: #fff;
            width: 320px;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        .modal-content h3 {
            margin-bottom: 10px;
        }

        .modal-actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .modal-actions button {
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        #confirmLogout {
            background: #e74c3c;
            color: white;
        }

        #cancelLogout {
            background: #7f8c8d;
            color: white;
        }
    </style>
</head>

<body>

<div class="dashboard">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>DENR</h2>
        <ul>
            <li>Dashboard</li>
            <li><a href="employee_list.php">Employees</a></li>
            <li><a href="view_documents.php">Documents</a></li>
            <li>Projects</li>
            <li>Settings</li>
            <li><a href="#" id="logoutBtn">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Top Bar -->
        <div class="topbar">
            <h1>Dashboard</h1>
            <span>Welcome, Admin</span>
        </div>

        <!-- Cards -->
        <div class="cards">
            <div class="card">
                <h3>120</h3>
                <p>Total Users</p>
            </div>

            <div class="card">
                <h3>75</h3>
                <p>Active Projects</p>
            </div>

            <div class="card">
                <h3>34</h3>
                <p>Reports Submitted</p>
            </div>

            <div class="card">
                <h3>12</h3>
                <p>Pending Requests</p>
            </div>
        </div>

    </div>
</div>

<!-- Logout Modal -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <h3>Confirm Logout</h3>
        <p>Are you sure you want to logout?</p>

        <div class="modal-actions">
            <button id="cancelLogout">Cancel</button>
            <button id="confirmLogout">Logout</button>
        </div>
    </div>
</div>

<!-- Script -->
<script>
const logoutBtn = document.getElementById("logoutBtn");
const modal = document.getElementById("logoutModal");
const cancelLogout = document.getElementById("cancelLogout");
const confirmLogout = document.getElementById("confirmLogout");

// Open modal
logoutBtn.addEventListener("click", function(e) {
    e.preventDefault();
    modal.style.display = "block";
});

// Cancel logout
cancelLogout.addEventListener("click", function() {
    modal.style.display = "none";
});

// Confirm logout -> redirect
confirmLogout.addEventListener("click", function() {
    window.location.href = "index.php";
});

// Close when clicking outside modal
window.addEventListener("click", function(e) {
    if (e.target === modal) {
        modal.style.display = "none";
    }
});
</script>

</body>
</html> 