<?php
include "db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $id = $_POST["UserID"];
    $username = trim($_POST["Username"]);
    $pass = $_POST["Pass"]; 
    $role = trim($_POST["Role"]);
    $employee_id = $_POST["EmployeeID"];

    if (empty($username) || empty($pass) || empty($role) || empty($id) || empty($employee_id)) {
        $_SESSION['msg'] = "<p style='color: red; font-weight: bold;'>Error: Fields cannot be empty or contain only spaces.</p>";
    } else {
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE useraccount SET Username = ?, Pass = ?, Role = ?, EmployeeID = ? WHERE UserID = ?"
        );

        mysqli_stmt_bind_param($stmt, "sssii", $username, $hashed_password, $role, $employee_id, $id);

        // Catching duplicate entry exceptions cleanly
        try {
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['msg'] = "<div style='color: #121121; background-color: white; display: inline-block; padding: 10px; font-weight: bold; border-radius: 5px; margin-top: 10px;'>Updated successfully</div>";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $_SESSION['msg'] = "<p style='color: #121121; background-color: white; display: inline-block; padding: 10px; font-weight: bold; border-radius: 5px; margin-top: 10px;'>Error: The username '" . htmlspecialchars($username) . "' is already assigned to another account.</p>";
            } else {
                $_SESSION['msg'] = "<p style='color: #121121; background-color: white; display: inline-block; padding: 10px; font-weight: bold; border-radius: 5px; margin-top: 10px;'>Update failed: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }

        mysqli_stmt_close($stmt);
    }

    header("Location: uaupdate.php");
    exit();
}

$sql = "SELECT UserID, Username, Pass, Role, EmployeeID FROM useraccount";
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

        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['UserID']); ?></td>
            <td><?php echo htmlspecialchars($row['Username']); ?></td>
            <td><?php echo htmlspecialchars($row['Role']); ?></td>
            <td><?php echo htmlspecialchars($row['EmployeeID']); ?></td>
        </tr>
        <?php } ?>
    </table>

    <br>

    <form method="POST">
        <label class="lab">User ID:</label>
        <select name="UserID" required style="padding: 3px; width: 175px;">
            <option value="">-- Select User to Update --</option>
            <?php
            mysqli_data_seek($result, 0);
            while ($user_row = mysqli_fetch_assoc($result)) {
                $selected = (isset($_POST['UserID']) && $_POST['UserID'] == $user_row['UserID']) ? 'selected' : '';
                echo "<option value='" . $user_row['UserID'] . "' $selected>ID: " . htmlspecialchars($user_row['UserID']) . " - " . htmlspecialchars($user_row['Username']) . "</option>";
            }
            ?>
        </select>
        
        <label class="lab">Username:</label>
        <input type="text" name="Username" placeholder="Username" required pattern=".*\S+.*" title="Username cannot be empty or just spaces." value="<?php echo isset($_POST['Username']) ? htmlspecialchars($_POST['Username']) : ''; ?>">
        
        <label class="lab">Password:</label>
        <input type="password" name="Pass" placeholder="New Password" required title="Password is required."><br><br>

        <label class="lab">Role:</label>
        <select name="Role" required style="padding: 3px; width: 175px;">
            <option value="" disabled selected>-- Select a Role --</option>
            <option value="Admin" <?php echo (isset($_POST['Role']) && $_POST['Role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="Manager" <?php echo (isset($_POST['Role']) && $_POST['Role'] == 'Manager') ? 'selected' : ''; ?>>Manager</option>
            <option value="Supervisor" <?php echo (isset($_POST['Role']) && $_POST['Role'] == 'Supervisor') ? 'selected' : ''; ?>>Supervisor</option>
            <option value="Staff" <?php echo (isset($_POST['Role']) && $_POST['Role'] == 'Staff') ? 'selected' : ''; ?>>Staff</option>
            <option value="HR" <?php echo (isset($_POST['Role']) && $_POST['Role'] == 'HR') ? 'selected' : ''; ?>>HR</option>
            <option value="Customer Service" <?php echo (isset($_POST['Role']) && $_POST['Role'] == 'Customer Service') ? 'selected' : ''; ?>>Customer Service</option>
        </select>
        
        <label class="lab">Employee:</label>
        <select name="EmployeeID" required style="padding: 3px; width: 175px;">
            <option value="">-- Select Employee --</option>
            <?php
            $emp_query = "SELECT EmployeeID, FirstName, LastName FROM Employee";
            $emp_result = mysqli_query($conn, $emp_query);
            
            if ($emp_result && mysqli_num_rows($emp_result) > 0) {
                while ($emp_row = mysqli_fetch_assoc($emp_result)) {
                    $selected = (isset($_POST['EmployeeID']) && $_POST['EmployeeID'] == $emp_row['EmployeeID']) ? 'selected' : '';
                    $displayName = "ID: " . $emp_row['EmployeeID'] . " - " . $emp_row['FirstName'] . " " . $emp_row['LastName'];
                    echo "<option value='" . $emp_row['EmployeeID'] . "' $selected>" . htmlspecialchars($displayName) . "</option>";
                }
            }
            ?>
        </select>
        <br><br>

        <button type="submit" name="update">Update User Account</button><br>
    </form>
    <br>
    <a href="uaupdate.php">Refresh Table</a>
    <a href="index.php">Back to Main Page</a>

</div>