<?php
include "db.php";
session_start(); 

if (isset($_POST["update"])) {
    $id = $_POST["id"];
    $firstName = trim($_POST["FirstName"]);
    $middleName = trim($_POST["MiddleName"]);
    $lastName = trim($_POST["LastName"]);
    $gender = $_POST["Gender"];
    $birthDate = $_POST["BirthDate"];
    $contactNumber = trim($_POST["ContactNumber"]);
    $emailAddress = trim($_POST["EmailAddress"]);
    $street = trim($_POST["Street"]);
    $city = trim($_POST["City"]);
    $province = trim($_POST["Province"]);
    $departmentID = $_POST["DepartmentID"];

    if (empty($id) || empty($firstName) || empty($lastName) || empty($gender) || empty($birthDate) || empty($contactNumber) || empty($emailAddress) || empty($street) || empty($city) || empty($province) || empty($departmentID)) {
        $_SESSION['msg'] = "<p style='color: red; font-weight: bold;'>Error: Fields cannot be empty or contain only spaces.</p>";
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE Employee SET FirstName = ?, MiddleName = ?, LastName = ?, Gender = ?, BirthDate = ?, ContactNumber = ?, EmailAddress = ?, Street = ?, City = ?, Province = ?, DepartmentID = ? WHERE EmployeeID = ?"
        );

        mysqli_stmt_bind_param($stmt, "ssssssssssii", $firstName, $middleName, $lastName, $gender, $birthDate, $contactNumber, $emailAddress, $street, $city, $province, $departmentID, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['msg'] = "<p style='color: #121121; background-color: white; display: inline-block; padding: 10px; font-weight: bold; border-radius: 5px;'>Updated successfully</p>";
        } else {
            $_SESSION['msg'] = "<p style='color: red; font-weight: bold;'>Update failed: " . mysqli_error($conn) . "</p>";
        }

        mysqli_stmt_close($stmt);
    }

    
    header("Location: Empupdate.php");
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
            <th>Gender</th>
            <th>BirthDate</th>
            <th>ContactNumber</th>
            <th>EmailAddress</th>
            <th>Street</th>
            <th>City</th>
            <th>Province</th>
            <th>DepartmentID</th>
        </tr>

        <?php 
        $employees = [];
        while($row = mysqli_fetch_assoc($result)) { 
            $employees[] = $row;
        ?>
        <tr>
            <td><?php echo htmlspecialchars($row['EmployeeID']); ?></td>
            <td><?php echo htmlspecialchars($row['FirstName']); ?></td>
            <td><?php echo htmlspecialchars($row['MiddleName']); ?></td>
            <td><?php echo htmlspecialchars($row['LastName']); ?></td>
            <td><?php echo htmlspecialchars($row['Gender']); ?></td>
            <td><?php echo htmlspecialchars($row['BirthDate']); ?></td>
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
        <label class="lab">Employee ID:</label>
        <select name="id" required style="padding: 3px; width: 175px;">
            <option value="" disabled selected>-- Select Employee --</option>
            <?php
            if (count($employees) > 0) {
                foreach ($employees as $emp_row) {
                    $selected = (isset($_POST['id']) && $_POST['id'] == $emp_row['EmployeeID']) ? 'selected' : '';
                    $displayName = "ID: " . $emp_row['EmployeeID'] . " - " . $emp_row['FirstName'] . " " . $emp_row['LastName'];
                    
                    echo "<option value='" . $emp_row['EmployeeID'] . "' $selected>" . htmlspecialchars($displayName) . "</option>";
                }
            }
            ?>
        </select>
        
        <label class="lab">First Name:</label>
        <input type="text" name="FirstName" placeholder="First Name" required value="<?php echo isset($_POST['FirstName']) ? htmlspecialchars($_POST['FirstName']) : ''; ?>">
        
        <label class="lab">Middle Name:</label>
        <input type="text" name="MiddleName" placeholder="Middle Name" value="<?php echo isset($_POST['MiddleName']) ? htmlspecialchars($_POST['MiddleName']) : ''; ?>"><br><br>

        <label class="lab">Last Name:</label>
        <input type="text" name="LastName" placeholder="Last Name" required value="<?php echo isset($_POST['LastName']) ? htmlspecialchars($_POST['LastName']) : ''; ?>">
        
        <label class="lab">Gender:</label>
        <select name="Gender" required style="padding: 3px; width: 175px;">
            <option value="" disabled selected>-- Select Gender --</option>
            <option value="Male" <?php echo (isset($_POST['Gender']) && $_POST['Gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo (isset($_POST['Gender']) && $_POST['Gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
            <option value="Other" <?php echo (isset($_POST['Gender']) && $_POST['Gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
        </select>

        <label class="lab">Birth Date:</label>
        <input type="date" name="BirthDate" placeholder="Birth Date" required value="<?php echo isset($_POST['BirthDate']) ? htmlspecialchars($_POST['BirthDate']) : ''; ?>"><br><br>
        
        <label class="lab">Contact Number:</label>
        <input type="text" name="ContactNumber" placeholder="Contact Number" required value="<?php echo isset($_POST['ContactNumber']) ? htmlspecialchars($_POST['ContactNumber']) : ''; ?>">

        <label class="lab">Email Address:</label>
        <input type="email" name="EmailAddress" placeholder="Email Address" required value="<?php echo isset($_POST['EmailAddress']) ? htmlspecialchars($_POST['EmailAddress']) : ''; ?>">
        
        <label class="lab">Street</label>
        <input type="text" name="Street" placeholder="Street" required value="<?php echo isset($_POST['Street']) ? htmlspecialchars($_POST['Street']) : ''; ?>"><br><br>
        
        <label class="lab">City</label>
        <input type="text" name="City" placeholder="City" required value="<?php echo isset($_POST['City']) ? htmlspecialchars($_POST['City']) : ''; ?>">

        <label class="lab">Province</label>
        <input type="text" name="Province" placeholder="Province" required value="<?php echo isset($_POST['Province']) ? htmlspecialchars($_POST['Province']) : ''; ?>">
        
        <label class="lab">Department</label>
        <select name="DepartmentID" required style="padding: 3px; width: 175px;">
            <option value="">-- Select Department --</option>
            <?php
            $dept_query = "SELECT DepartmentID, DepartmentName FROM department";
            $dept_result = mysqli_query($conn, $dept_query);
            
            if ($dept_result && mysqli_num_rows($dept_result) > 0) {
                while ($dept_row = mysqli_fetch_assoc($dept_result)) {
                    $selected = (isset($_POST['DepartmentID']) && $_POST['DepartmentID'] == $dept_row['DepartmentID']) ? 'selected' : '';
                    echo "<option value='" . $dept_row['DepartmentID'] . "' $selected>" . htmlspecialchars($dept_row['DepartmentName']) . "</option>";
                }
            }
            ?>
        </select>
        
        <br><br>
        <button type="submit" name="update">Update</button>
        <br><br>
        <a href="Empupdate.php">Refresh Table</a><br>
        <a href="index.php">Back to Main Page</a>
    </form>
</div>