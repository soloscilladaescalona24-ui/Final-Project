<link rel="stylesheet" href="style.css">
<?php
include "db.php";

$sql = "SELECT UserID, Username, Role, EmployeeID FROM useraccount";
$result = mysqli_query($conn, $sql);
?>

<div class="updateformat">
<table border="1" cellpadding="10">
    <tr>
        <th>User ID</th>
        <th>Username</th>
        <th>Role</th>
        <th>Employee ID</th>
    </tr>

    <h1>User Information</h1>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?php echo $row['UserID']; ?></td>
        <td><?php echo $row['Username']; ?></td>
        <td><?php echo $row['Role']; ?></td>
        <td><?php echo $row['EmployeeID']; ?></td>
    </tr>
    <?php } ?>
</table>
 <a href="index.php">Back to Main Page</a>
</div>