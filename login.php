<link rel="stylesheet" href="style.css?v=refresh_login">

<?php
include "db.php";
session_start();

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"]; 

    if (empty($username) || empty($password)) {
        $error_msg = "Please fill in all fields.";
    } else {
        // Securely checks your exact inputted username and plain text password
        $sql = "SELECT AdminID, Username FROM admin WHERE Username = ? AND Pass = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['admin_id'] = $row['AdminID'];
            $_SESSION['username'] = $row['Username'];

            header("Location: index.php");
            exit();
        } else {
            $error_msg = "Invalid username or password.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<div class="updateformat">
    <h1 class="welcome">W E L C O M E  !</h1>
    <div class="login-box">
        <h1>Account Login</h1>

        <?php if (!empty($error_msg)): ?>
            <p class="error-alert" style="color: red; font-weight: bold;"><?php echo htmlspecialchars($error_msg); ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" 
                   name="username" 
                   placeholder="Username" 
                   required 
                   pattern=".*\S+.*" 
                   title="Username cannot be empty or just spaces."
                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">

            <input type="password" 
                   name="password" 
                   placeholder="Password" 
                   required 
                   title="Password is required.">

            <button type="submit" name="login">Login</button>
        </form>
    </div>
</div>