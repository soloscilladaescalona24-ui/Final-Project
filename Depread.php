<link rel="stylesheet" href="style.css">
<?php
include "db.php";

$sql = "SELECT * FROM Department";
$result = mysqli_query($conn, $sql);
?>

<div class="updateformat">
<table border="1" cellpadding="10">
    <tr>
        <th>Department ID</th>
        <th>Department Name</th>
    </tr>

    <h1>Department Information</h1>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?php echo $row['DepartmentID']; ?></td>
        <td><?php echo $row['DepartmentName']; ?></td>
    </tr>
    <?php } ?>
</table>
 <a href="index.php">Back to Main Page</a>
</div>