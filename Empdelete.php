<?php
include "db.php";
session_start(); 


if (isset($_POST["delete"])) {
    $id = $_POST["EmployeeID"]; 

    if (empty($id)) {
        $_SESSION['msg'] = "<p style='color: red; font-weight: bold;'>Error: No employee selected.</p>";
    } else {
        $stmt = mysqli_prepare($conn, "DELETE FROM Employee WHERE EmployeeID = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['msg'] = "<p style='color: #121121; background-color: white; display: inline-block; padding: 10px; font-weight: bold; border-radius: 5px;'>Deleted Successfully</p>";
        } else {
            $_SESSION['msg'] = "<p style='color: red; font-weight: bold;'>Failed to Delete: " . mysqli_error($conn) . "</p>";
        }
        mysqli_stmt_close($stmt);
    }

    // Redirect clears the POST state so refreshing the page won't cause duplicate actions
    header("Location: Empdelete.php");
    exit();
}


$sql = "SELECT * FROM Employee";
$result = mysqli_query($conn, $sql);
?>

<link rel="stylesheet" href="style.css">
<div class="updateformat">
    <h1>Employee Information</h1>
    
    <?php 
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <table border="1" cellpadding="10">
        <tr>
            <th>Employee ID</th>
            <th>FirstName</th>
            <th>MiddleName</th>
            <th>LastName</th>
            <th>Sex</th>
            <th>BirthDate</th>
            <th>ContactNumber</th>
            <th>EmailAddress</th>
            <th>Street</th>
            <th>City</th>
            <th>Province</th>
            <th>DepartmentID</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['EmployeeID']); ?></td>
            <td><?php echo htmlspecialchars($row['FirstName']); ?></td>
            <td><?php echo htmlspecialchars($row['MiddleName']); ?></td>
            <td><?php echo htmlspecialchars($row['LastName']); ?></td>
            <td><?php echo htmlspecialchars($row['Gender']); ?></td> <td><?php echo htmlspecialchars($row['BirthDate']); ?></td>
            <td><?php echo htmlspecialchars($row['ContactNumber']); ?></td>
            <td><?php echo htmlspecialchars($row['EmailAddress']); ?></td>
            <td><?php echo htmlspecialchars($row['Street']); ?></td>
            <td><?php echo htmlspecialchars($row['City']); ?></td>
            <td><?php echo htmlspecialchars($row['Province']); ?></td>
            <td><?php echo htmlspecialchars($row['DepartmentID']); ?></td>
        </tr>
        <?php } ?>
    </table>

    <br>

    <form method="POST">
        <label class="lab">Select Employee to Delete:</label><br>
        
        <select name="EmployeeID" required style="padding: 3px; width: 250px;">
            <option value="">-- Select Employee --</option>
            <?php
                // Running a fresh query for the select menu to sync with table updates
                $select_query = "SELECT EmployeeID, FirstName, LastName FROM Employee";
                $select_result = mysqli_query($conn, $select_query);
                
                if ($select_result && mysqli_num_rows($select_result) > 0) {
                    while ($dept_row = mysqli_fetch_assoc($select_result)) {
                        $selected = (isset($_POST['EmployeeID']) && $_POST['EmployeeID'] == $dept_row['EmployeeID']) ? 'selected' : '';
                        $displayName = "ID: " . $dept_row['EmployeeID'] . " - " . $dept_row['FirstName'] . " " . $dept_row['LastName'];
                        
                        echo "<option value='" . $dept_row['EmployeeID'] . "' $selected>" . htmlspecialchars($displayName) . "</option>";
                    }
                }
            ?>
        </select> <br><br>
        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this item? This action cannot be undone.')">Delete Employee</button>
        <br><br>
        <a href="Empdelete.php">Refresh Table</a><br>
        <a href="index.php">Back to Main Page</a>
    </form> 
</div>