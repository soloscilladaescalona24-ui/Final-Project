<?php
include "db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create"])) {
    $errors = [];

    $fname    = trim(strip_tags($_POST["FirstName"]));
    $mname    = trim(strip_tags($_POST["MiddleName"])); 
    $lname    = trim(strip_tags($_POST["LastName"]));
    $gender   = $_POST["Gender"]; 
    $bd       = trim($_POST["BirthDate"]);
    $contn    = trim(strip_tags($_POST["ContactNumber"]));
    $email    = filter_var(trim($_POST["EmailAddress"]), FILTER_VALIDATE_EMAIL);
    $street   = trim(strip_tags($_POST["Street"]));
    $city     = trim(strip_tags($_POST["City"]));
    $province = trim(strip_tags($_POST["Province"]));
    $DID      = filter_var($_POST["DepartmentID"], FILTER_VALIDATE_INT);

    if (empty($fname)) {
        $errors[] = "First Name is required.";
    }
    if (empty($lname)) {
        $errors[] = "Last Name is required.";
    }
    if (empty($gender)) {
        $errors[] = "Gender selection is required.";
    }
    if (empty($bd)) {
        $errors[] = "Birth Date is required.";
    }
    if (empty($contn)) {
        $errors[] = "Contact Number is required.";
    }
    if ($email === false) {
        $errors[] = "Please provide a valid email address.";
    }
    if (empty($street) || empty($city) || empty($province)) {
        $errors[] = "Complete address (Street, City, and Province) is required.";
    }
    if ($DID === false) {
        $errors[] = "Please select a valid department.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO Employee(FirstName, MiddleName, LastName, Gender, BirthDate, ContactNumber, EmailAddress, Street, City, Province, DepartmentID) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssssssi", $fname, $mname, $lname, $gender, $bd, $contn, $email, $street, $city, $province, $DID);

        try {
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['msg'] = "<div style='color: #121121; background-color: white; display: inline-block; padding: 10px; font-weight: bold; border-radius: 5px; margin-top: 10px;'>Employee Added Successfully!</div>";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $_SESSION['msg'] = "<div style='color: #121121; background-color: white; display: inline-block; padding: 10px; font-weight: bold; border-radius: 5px; margin-top: 10px;'>Failed to Add Employee, email is already registered to an existing Employee!</div>";
            } else {
                $_SESSION['msg'] = "<div style='color: #121121; background-color: white; display: inline-block; padding: 10px; font-weight: bold; border-radius: 5px; margin-top: 10px;'>Failed to Add Employee: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
        }
        mysqli_stmt_close($stmt);
    } else {
        $errHtml = "<div style='color: red; font-weight: bold; margin-top: 15px;'>Please correct the following tracking issues:<br><ul>";
        foreach ($errors as $error) {
            $errHtml .= "<li>" . htmlspecialchars($error) . "</li>";
        }
        $errHtml .= "</ul></div>";
        $_SESSION['msg'] = $errHtml;
    }

    header("Location: Empcreate.php");
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

        <?php while($row = mysqli_fetch_assoc($result)) { ?>
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

        <button type="submit" name="create">Create Employee</button><br><br>
        <a href="Empcreate.php">Refresh Table</a><br>
        <a href="index.php">Back to Main Page</a>
    </form>
</div>