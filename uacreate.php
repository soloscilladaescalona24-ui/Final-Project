<?php
include "db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Username = trim($_POST["Username"]);
    $Pass = $_POST["Pass"]; 
    $Role = trim($_POST["Role"]);
    $EmployeeID = isset($_POST["EmployeeID"]) ? $_POST["EmployeeID"] : "";

    if (empty($Username) || empty($Pass) || empty($Role) || empty($EmployeeID)) {
        $_SESSION['msg'] = "<p style='color: red; font-weight: bold;'>All fields are required and cannot be empty or just spaces.</p>";
    } else {
        $hashed_password = password_hash($Pass, PASSWORD_DEFAULT);

        $sql = "INSERT INTO useraccount (Username, Pass, Role, EmployeeID) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        
        mysqli_stmt_bind_param($stmt, "sssi", $Username, $hashed_password, $Role, $EmployeeID);

        // Wrapped in try-catch to cleanly capture the duplicate entry exception
        try {
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['msg'] = "<div style='color: #121121; background-color: white; display: inline-block; padding: 10px; font-weight: bold; border-radius: 5px; margin-top: 10px;'>User Account Created Successfully!</div>";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $_SESSION['msg'] = "<p style='color: #121121; background-color: white; display: inline-block; padding: 10px; font-weight: bold; border-radius: 5px; margin-top: 10px;'>Error: EmployeeID:  " . htmlspecialchars($EmployeeID) . " already has an assigned user account, choose another employee.</p>";
            } else {
                $_SESSION['msg'] = "<p style='color: #121121; background-color: white; display: inline-block; padding: 10px; font-weight: bold; border-radius: 5px; margin-top: 10px;'>Failed to create user account: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
        mysqli_stmt_close($stmt);
    }

    header("Location: uacreate.php");
    exit();
}

$sql = "SELECT UserID, Username, Role, EmployeeID FROM useraccount";
$result = mysqli_query($conn, $sql);

$emp_query = "SELECT EmployeeID, FirstName, LastName FROM Employee";
$emp_result = mysqli_query($conn, $emp_query);
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
        <h3>Create New User Account</h3>
        
        <label for="EmployeeID">Link to Employee:</label><br>
        <select name="EmployeeID" id="EmployeeID" required>
            <option value="" disabled selected>-- Select an Employee --</option>
            <?php
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

        <input type="text" 
               name="Username" 
               placeholder="Username" 
               required 
               pattern=".*\S+.*" 
               title="Username cannot be empty or just spaces.">
        <br><br>

        <input type="password" 
               name="Pass" 
               placeholder="Password" 
               required 
               title="Password is required.">
        <br><br>

        <label for="Role">Assign System Role:</label><br>
        <select name="Role" id="Role" required style="padding: 3px; width: 175px;">
            <option value="" disabled selected>-- Select a Role --</option>
            <option value="Admin">Admin</option>
            <option value="Manager">Manager</option>
            <option value="Supervisor">Supervisor</option>
            <option value="Staff">Staff</option>
            <option value="HR">HR</option>
            <option value="Customer Service">Customer Service</option>
        </select>
        <br><br>

        <button type="submit">Create User Account</button>
        <br><br>
        <a href="uacreate.php">Refresh Table</a><br>
        <a href="index.php">Back to Main Page</a>
    </form>
</div>
