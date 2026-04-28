<?php
$conn = new mysqli("localhost","root","","cenro");
if ($conn->connect_error) die("Connection failed");

$image_folder = "assets/image/employee/";
if (!file_exists($image_folder)) mkdir($image_folder,0777,true);

/* FILTER */
$statusFilter = $_GET['status'] ?? "";

/* CREATE */
if(isset($_POST['add'])){
    $data=$_POST;

    $image_name=null;
    if(!empty($_FILES['image']['name'])){
        $ext=pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION);
        $image_name=uniqid().".".$ext;
        move_uploaded_file($_FILES['image']['tmp_name'],$image_folder.$image_name);
    }

    $stmt=$conn->prepare("INSERT INTO employees 
    (name,status,image,gender,date_of_birth,nosca_item_number,place_of_assignment,
    position_title,salary_grade,civil_service_eligibility,education,date_of_appointment)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");

    $stmt->bind_param("ssssssssssss",
    $data['name'],$data['status'],$image_name,
    $data['gender'],$data['date_of_birth'],$data['nosca_item_number'],
    $data['place_of_assignment'],$data['position_title'],$data['salary_grade'],
    $data['civil_service_eligibility'],$data['education'],$data['date_of_appointment']);

    $stmt->execute();
}

/* DELETE */
if(isset($_GET['delete'])){
    $id=(int)$_GET['delete'];

    $img=$conn->query("SELECT image FROM employees WHERE employee_id=$id")->fetch_assoc();
    if($img && $img['image'] && file_exists($image_folder.$img['image'])){
        unlink($image_folder.$img['image']);
    }

    $conn->query("DELETE FROM employees WHERE employee_id=$id");
}

/* UPDATE */
if(isset($_POST['update'])){
    $data=$_POST;
    $id=$data['id'];

    $image_name=$data['old_image'];

    if(!empty($_FILES['image']['name'])){
        if($image_name && file_exists($image_folder.$image_name)){
            unlink($image_folder.$image_name);
        }
        $ext=pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION);
        $image_name=uniqid().".".$ext;
        move_uploaded_file($_FILES['image']['tmp_name'],$image_folder.$image_name);
    }

    $stmt=$conn->prepare("UPDATE employees SET 
    name=?,status=?,image=?,gender=?,date_of_birth=?,nosca_item_number=?,
    place_of_assignment=?,position_title=?,salary_grade=?,
    civil_service_eligibility=?,education=?,date_of_appointment=?
    WHERE employee_id=?");

    $stmt->bind_param("ssssssssssssi",
    $data['name'],$data['status'],$image_name,
    $data['gender'],$data['date_of_birth'],$data['nosca_item_number'],
    $data['place_of_assignment'],$data['position_title'],$data['salary_grade'],
    $data['civil_service_eligibility'],$data['education'],$data['date_of_appointment'],
    $id);

    $stmt->execute();
}

/* PAGINATION */
$limit=10;
$page=max(1,(int)($_GET['page']??1));
$offset=($page-1)*$limit;

$where="";
if($statusFilter){
    $where="WHERE status='".$conn->real_escape_string($statusFilter)."'";
}

$total=$conn->query("SELECT COUNT(*) total FROM employees $where")->fetch_assoc()['total'];
$totalPages=ceil($total/$limit);

$result=$conn->query("SELECT * FROM employees $where ORDER BY employee_id DESC LIMIT $limit OFFSET $offset");

/* EDIT */
$edit=false;
if(isset($_GET['edit'])){
    $id=(int)$_GET['edit'];
    $editData=$conn->query("SELECT * FROM employees WHERE employee_id=$id")->fetch_assoc();
    $edit=true;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Employee Dashboard</title>

<style>
body{font-family:Segoe UI;background:#eef2f7}
.container{max-width:1100px;margin:auto;background:#fff;padding:20px;border-radius:10px}

/* FORM GRID FIX */
.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
    margin-top:10px;
}
.full{grid-column:span 2}

input,select{
    width:100%;
    padding:10px 14px;
    border-radius:25px;
    border:1px solid #ccc;
    box-sizing:border-box;
    font-size:14px;
}

/* BUTTON */
button{
    padding:10px;
    border:none;
    border-radius:25px;
    background:#4facfe;
    color:#fff;
    cursor:pointer;
}

/* TABLE */
table{width:100%;margin-top:20px}
th,td{padding:10px;text-align:center}
tr:hover{background:#f1f5f9;cursor:pointer}

img{width:50px;height:50px;border-radius:50%}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    top:0;left:0;
    width:100%;height:100%;
    background:rgba(0,0,0,.5)
}
.modal-content{
    background:#fff;
    margin:5% auto;
    padding:20px;
    width:700px;
    max-width:95%;
    border-radius:10px;
}

/* PAGINATION FIX */
.pagination{
    margin-top:20px;
    text-align:center;
}
.pagination a{
    display:inline-block;
    margin:4px;
    padding:8px 14px;
    border-radius:20px;
    background:#f1f5f9;
    color:#333;
    text-decoration:none;
}
.pagination a:hover{
    background:#4facfe;
    color:#fff;
}
.pagination a.active{
    background:#4facfe;
    color:#fff;
    font-weight:bold;
}
</style>
</head>

<body>
<div class="container">

<h2>Employee Management</h2>

<input type="text" id="search" placeholder="🔍 Search...">

<button onclick="openModal()">+ Add Employee</button>

<!-- FILTER -->
<form method="GET">
<select name="status" onchange="this.form.submit()">
<option value="">All</option>
<option value="Permanent" <?=($statusFilter=="Permanent")?"selected":""?>>Permanent</option>
<option value="Contract of Service" <?=($statusFilter=="Contract of Service")?"selected":""?>>COS</option>
</select>
</form>

<div id="table-data">
<table>
<tr><th>ID</th><th>Image</th><th>Name</th><th>Status</th><th>Actions</th></tr>

<?php while($row=$result->fetch_assoc()): ?>
<tr onclick='showProfile(<?= json_encode($row) ?>)'>

<td><?= $row['employee_id'] ?></td>

<td>
<?php if($row['image'] && file_exists($image_folder.$row['image'])): ?>
<img src="<?= $image_folder.$row['image'] ?>">
<?php else: ?>
<img src="<?= $image_folder ?>default.png">
<?php endif; ?>
</td>

<td><?= htmlspecialchars($row['name']) ?></td>
<td><?= $row['status'] ?></td>

<td>
<a href="?edit=<?= $row['employee_id'] ?>&status=<?= $statusFilter ?>">Edit</a>
<a href="?delete=<?= $row['employee_id'] ?>&status=<?= $statusFilter ?>">Delete</a>
</td>

</tr>
<?php endwhile; ?>
</table>
</div>

<!-- PAGINATION -->
<div class="pagination">
<?php for($i=1;$i<=$totalPages;$i++): ?>
<a href="?page=<?=$i?>&status=<?=$statusFilter?>" class="<?=($i==$page)?'active':''?>">
<?=$i?>
</a>
<?php endfor; ?>
</div>

</div>

<!-- MODAL FORM -->
<div id="formModal" class="modal">
<div class="modal-content">

<span onclick="closeModal()" style="float:right;cursor:pointer;">&times;</span>

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $edit?$editData['employee_id']:'' ?>">
<input type="hidden" name="old_image" value="<?= $edit?$editData['image']:'' ?>">

<div class="form-grid">

<input name="name" placeholder="Name" class="full" value="<?= $edit?$editData['name']:'' ?>">

<select name="status">
<option value="Permanent" <?=($edit && $editData['status']=="Permanent")?"selected":""?>>Permanent</option>
<option value="Contract of Service" <?=($edit && $editData['status']=="Contract of Service")?"selected":""?>>COS</option>
</select>

<input name="gender" value="<?= $edit?$editData['gender']:'' ?>">
<input type="date" name="date_of_birth" value="<?= $edit?$editData['date_of_birth']:'' ?>">

<input name="nosca_item_number" value="<?= $edit?$editData['nosca_item_number']:'' ?>">
<input name="place_of_assignment" value="<?= $edit?$editData['place_of_assignment']:'' ?>">

<input name="position_title" value="<?= $edit?$editData['position_title']:'' ?>">
<input name="salary_grade" value="<?= $edit?$editData['salary_grade']:'' ?>">

<input name="civil_service_eligibility" value="<?= $edit?$editData['civil_service_eligibility']:'' ?>">
<input name="education" value="<?= $edit?$editData['education']:'' ?>">

<input type="date" name="date_of_appointment" value="<?= $edit?$editData['date_of_appointment']:'' ?>">

<input type="file" name="image" class="full">

<?php if($edit): ?>
<button name="update" class="full">Update</button>
<?php else: ?>
<button name="add" class="full">Add</button>
<?php endif; ?>

</div>
</form>

</div>
</div>

<!-- PROFILE -->
<div id="profileModal" class="modal">
<div class="modal-content" id="profileContent"></div>
</div>

<script>
// AUTO OPEN EDIT
<?php if($edit): ?>
document.getElementById("formModal").style.display="block";
<?php endif; ?>

// SEARCH
document.getElementById("search").addEventListener("keyup",function(){
let v=this.value;
let xhr=new XMLHttpRequest();
xhr.open("GET","search.php?search="+v,true);
xhr.onload=()=>document.getElementById("table-data").innerHTML=xhr.responseText;
xhr.send();
});

// MODAL
function openModal(){formModal.style.display="block"}
function closeModal(){formModal.style.display="none"}

// PROFILE
function showProfile(d){
let img=d.image?"assets/image/employee/"+d.image:"assets/image/employee/default.png";
profileContent.innerHTML=`
<div style="text-align:center">
<img src="${img}" width="100">
<h2>${d.name}</h2>
<p>${d.status}</p>
<p>${d.position_title}</p>
<p>${d.place_of_assignment}</p>
</div>`;
profileModal.style.display="block";
}

window.onclick=e=>{
if(e.target.classList.contains("modal")) e.target.style.display="none";
}
</script>

</body>
</html>