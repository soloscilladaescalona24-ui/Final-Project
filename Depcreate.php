<?php
include "db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $DepartmentName = trim($_POST["DepartmentName"]);
    $DepartmentName = htmlspecialchars($DepartmentName);
    
    if (empty($DepartmentName)) {
        $_SESSION['msg'] = "<p style='color: red; font-weight: bold;'>Department name cannot be empty or just spaces.</p>";
    } else {
        $sql = "INSERT INTO Department(DepartmentName) Values (?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $DepartmentName);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['msg'] = "<div style='color: #121121; background-color: white; display: inline-block; padding: 10px; font-weight: bold; border-radius: 5px; margin-top: 10px;'>Department Added Successfully!</div>";
        } else {
            $_SESSION['msg'] = "<p style='color: red; font-weight: bold;'>Failed to Add Department. It might already exist.</p>";
        } 
        mysqli_stmt_close($stmt);
    }

    header("Location: Depcreate.php");
    exit();
}

$sql = "SELECT * FROM Department";
$result = mysqli_query($conn, $sql);
?>

<link rel="stylesheet" href="style.css">

<div class="updateformat">
    <h1>Department Information</h1>
    
    <?php 
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <table border="1" cellpadding="10">
        <tr>
            <th>Department ID</th>
            <th>Department Name</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['DepartmentID']; ?></td>
            <td><?php echo htmlspecialchars($row['DepartmentName']); ?></td>
        </tr>
        <?php } ?>
    </table>

    <br>

    <form method="POST">
        <input type="text" 
               name="DepartmentName" 
               placeholder="Department Name" 
               required 
               pattern=".*\S+.*" 
               title="Department name cannot be empty or just spaces.">
        <br><br>
        <button type="submit">Create Department</button>
        <br><br>
        <a href="Depcreate.php">Refresh Table</a><br>
        <a href="index.php">Back to Main Page</a>
    </form>
</div>
