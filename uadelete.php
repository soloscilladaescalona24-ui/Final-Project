<?php
include "db.php";
session_start();

if (isset($_POST["delete"])) {
    $id = $_POST["UserID"];

    if (empty($id)) {
        $_SESSION['msg'] = "<p style='color: red; font-weight: bold;'>Error: Please select a valid user.</p>";
    } else {
        $stmt = mysqli_prepare($conn, "DELETE FROM useraccount WHERE UserID = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['msg'] = "<div style='color: #121121; background-color: white; display: inline-block; padding: 10px; font-weight: bold; border-radius: 5px; margin-top: 10px;'>Deleted Successfully</div>";
        } else {
            $_SESSION['msg'] = "<p style='color: red; font-weight: bold;'>Failed to Delete: " . mysqli_error($conn) . "</p>";
        }
        mysqli_stmt_close($stmt);
    }

    header("Location: uadelete.php");
    exit();
}

$sql = "SELECT UserID, Username, Role, EmployeeID FROM useraccount";
$result = mysqli_query($conn, $sql);
?>

<link rel="stylesheet" href="style.css">
<div class="updateformat">
    <h1>User Account Information</h1>

    <?php 
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <table border="1" cellpadding="10">
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Employee ID</th>
        </tr>

        <?php 
        $useraccounts = [];
        while($row = mysqli_fetch_assoc($result)) { 
            $useraccounts[] = $row;
        ?>
        <tr>
            <td><?php echo htmlspecialchars($row['UserID']); ?></td>
            <td><?php echo htmlspecialchars($row['Username']); ?></td>
            <td><?php echo htmlspecialchars($row['Role']); ?></td>
            <td><?php echo htmlspecialchars($row['EmployeeID']); ?></td>
        </tr>
        <?php } ?>
    </table>

    <form method="POST">
        <br>
        <select name="UserID" required>
            <option value="" disabled selected>-- Select User to Delete --</option>
            <?php
            if (count($useraccounts) > 0) {
                foreach ($useraccounts as $user_row) {
                    $selected = (isset($_POST['UserID']) && $_POST['UserID'] == $user_row['UserID']) ? 'selected' : '';
                    $displayName = "ID: " . $user_row['UserID'] . " - " . $user_row['Username'];
                    
                    echo "<option value='" . $user_row['UserID'] . "' $selected>" . htmlspecialchars($displayName) . "</option>";
                }
            }
            ?>
        </select>
        
        <br><br>
        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this item? This action cannot be undone.')">Delete User Account</button><br><br>
        <a href="uadelete.php">Refresh Table</a><br>
        <a href="index.php">Back to Main Page</a>
    </form>
</div>