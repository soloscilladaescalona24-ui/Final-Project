<link rel="stylesheet" href="style.css">
<?php
include "db.php";

$sql = "SELECT * FROM Employee";
$result = mysqli_query($conn, $sql);
?>

<div class="updateformat">
    <h1>Employee Information</h1>

    <table border="1" cellpadding="10">
        <tr>
            <th>Employee ID</th>
            <th>FirstName</th>
            <th>MiddleName</th>
            <th>LastName</th>
            <th>Sex</th> <th>BirthDate</th>
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
    <a href="index.php">Back to Main Page</a>
</div>