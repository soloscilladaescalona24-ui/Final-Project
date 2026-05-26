<?php
include "db.php";
session_start();

if (isset($_POST["delete"])) {
    $id = $_POST["id"];

    if (empty($id)) {
        $_SESSION['msg'] = "<p style='color: red; font-weight: bold;'>Error: No department selected.</p>";
    } else {
        $stmt = mysqli_prepare($conn, "DELETE FROM Department WHERE DepartmentID = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['msg'] = "<div style='color: #121121; background-color: white; display: inline-block; padding: 10px; font-weight: bold; border-radius: 5px; margin-top: 10px;'>Deleted Successfully</div>";
        } else {
            $_SESSION['msg'] = "<p style='color: red; font-weight: bold;'>Failed to Delete: " . mysqli_error($conn) . "</p>";
        }
        mysqli_stmt_close($stmt);
    }

    header("Location: Depdelete.php");
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

        <?php 
        $departments = [];
        while($row = mysqli_fetch_assoc($result)) { 
            $departments[] = $row;
        ?>
        <tr>
            <td><?php echo htmlspecialchars($row['DepartmentID']); ?></td>
            <td><?php echo htmlspecialchars($row['DepartmentName']); ?></td>
        </tr>
        <?php } ?>
    </table>

    <form method="POST">
        <br>
        <select name="id" required>
            <option value="" disabled selected>-- Select Department to Delete --</option>
            <?php
            if (count($departments) > 0) {
                foreach ($departments as $dept_row) {
                    $selected = (isset($_POST['id']) && $_POST['id'] == $dept_row['DepartmentID']) ? 'selected' : '';
                    $displayName = "ID: " . $dept_row['DepartmentID'] . " - " . $dept_row['DepartmentName'];
                    
                    echo "<option value='" . $dept_row['DepartmentID'] . "' $selected>" . htmlspecialchars($displayName) . "</option>";
                }
            }
            ?>
        </select>
        
        <br><br>
        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this item? This action cannot be undone.')">Delete Department</button><br><br>
        <a href="Depdelete.php">Refresh Table</a><br>
        <a href="index.php">Back to Main Page</a>
    </form>
</div>