<?php
$conn = new mysqli("localhost", "root", "", "cenro");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$selected_filter = $_GET['filter'] ?? 'ALL';

$sections = [
    "PDS",
    "SALN",
    "IPC",
    "OPC",
    "IPCR",
    "OPCR",
    "Special Order",
    "Reporting for Duty",
    "Memorandum",
    "IDP",
    "Appointment",
    "Office Clearance"
];

function safe_class($text) {
    return str_replace(' ', '-', $text);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Employee Documents</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body {
  font-family: Arial;
  margin:40px;
  background:#f5f6f8;
}

.container {
  max-width:1100px;
  margin:auto;
  background:white;
  padding:25px;
  border-radius:12px;
  box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

.header {
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:20px;
}

form {
  display:flex;
  gap:10px;
}

select, button {
  padding:8px;
  border-radius:6px;
}

button {
  background:#4e73df;
  color:white;
  border:none;
  cursor:pointer;
}

.section {
  margin-bottom:30px;
}

h3 {
  padding:10px;
  border-radius:6px;
  color:white;
  text-align:center;
}

/* SECTION COLORS */
.PDS { background:#4e73df; }
.SALN { background:#1cc88a; }
.IPC { background:#36b9cc; }
.OPC { background:#f6c23e; }
.IPCR { background:#e74a3b; }
.OPCR { background:#6f42c1; }
.Special-Order { background:#fd7e14; }
.Reporting-for-Duty { background:#20c997; }
.Memorandum { background:#858796; }
.IDP { background:#17a2b8; }
.Appointment { background:#6610f2; }
.Office-Clearance { background:#28a745; }

/* TABLE */
table {
  width:100%;
  border-collapse:collapse;
}

th {
  background:#2c3e50;
  color:white;
  padding:12px;
  text-align:left;
}

td {
  padding:10px;
  border-bottom:1px solid #ddd;
}

tr:nth-child(even) {
  background:#fafafa;
}

tr:hover {
  background:#f0f6ff;
}

a {
  text-decoration:none;
  color:#2980b9;
  margin:0 3px;
}

/* MODAL */
.modal {
  display:none;
  position:fixed;
  top:0;
  left:0;
  width:100%;
  height:100%;
  background:rgba(0,0,0,0.7);
  z-index:999;
}

.modal-content {
  margin:3% auto;
  width:85%;
  height:85%;
  background:white;
  border-radius:10px;
  overflow:hidden;
}

.modal-header {
  display:flex;
  justify-content:space-between;
  padding:10px;
  background:#2c3e50;
  color:white;
}

.modal-header button {
  background:#4e73df;
  border:none;
  color:white;
  padding:5px 10px;
  border-radius:5px;
  cursor:pointer;
}

iframe {
  width:100%;
  height:calc(100% - 50px);
  border:none;
}
</style>
</head>

<body>

<div class="container">

<div class="header">
  <h2>📁 Employee Documents</h2>

  <form method="GET">
    <select name="filter" onchange="this.form.submit()">
      <option value="ALL">All</option>
      <?php foreach ($sections as $sec) { ?>
        <option value="<?= $sec ?>" <?= $selected_filter == $sec ? 'selected' : '' ?>>
          <?= $sec ?>
        </option>
      <?php } ?>
    </select>

    <button type="button" onclick="window.location='upload_form.php'">
      + Upload
    </button>
  </form>
</div>

<?php foreach ($sections as $section) {

if ($selected_filter != 'ALL' && $selected_filter != $section) continue;

$class = safe_class($section);
?>

<div class="section">
<h3 class="<?= $class ?>"><i class="fas fa-folder"></i> <?= $section ?></h3>

<table>
<tr>
<th>Employee ID</th>
<th>File</th>
<th>Uploaded</th>
<th>Actions</th>
</tr>

<?php
$stmt = $conn->prepare("SELECT * FROM employee_documents WHERE document_type=? ORDER BY uploaded_at DESC");
$stmt->bind_param("s", $section);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
  echo "<tr><td colspan='4'>No documents</td></tr>";
}

while ($row = $result->fetch_assoc()) {

$fileType = strtolower(pathinfo($row["file_name"], PATHINFO_EXTENSION));

if ($fileType == "pdf") {
  $icon = "fa-file-pdf"; $color="red";
} elseif (in_array($fileType, ["doc","docx"])) {
  $icon = "fa-file-word"; $color="blue";
} else {
  $icon = "fa-file"; $color="gray";
}
?>

<tr>
<td><?= $row["employee_id"] ?></td>

<td>
  <i class="fas <?= $icon ?>" style="color:<?= $color ?>"></i>
  <?= htmlspecialchars($row["file_name"]) ?>
</td>

<td><?= $row["uploaded_at"] ?></td>

<td>
  <a href="#" onclick="openModal('<?= $row['file_path'] ?>')">View</a> |
  <a href="<?= $row['file_path'] ?>" download>Download</a> |
  <a href="edit_document.php?id=<?= $row['id'] ?>">Edit</a> |
  <a href="delete_document.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
</td>
</tr>

<?php } ?>

</table>
</div>

<?php } ?>

</div>

<!-- MODAL -->
<div id="fileModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <span onclick="closeModal()" style="cursor:pointer;">✖</span>

      <div>
        <button onclick="downloadFile()">Download</button>
        <button onclick="openFull()">Full View</button>
      </div>
    </div>

    <iframe id="fileFrame"></iframe>
  </div>
</div>

<script>
let currentFile = "";

function openModal(file) {
  currentFile = file;
  document.getElementById("fileFrame").src = file;
  document.getElementById("fileModal").style.display = "block";
}

function closeModal() {
  document.getElementById("fileModal").style.display = "none";
  document.getElementById("fileFrame").src = "";
}

function openFull() {
  window.open("view_file.php?file=" + encodeURIComponent(currentFile));
}

function downloadFile() {
  let a = document.createElement("a");
  a.href = currentFile;
  a.download = "";
  a.click();
}
</script>

</body>
</html>