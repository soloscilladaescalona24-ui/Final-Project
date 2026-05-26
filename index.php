<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
   <div class="main">
        <div class="lefthand">
            <h1>Dashboard</h1><hr class="lhr"><br>

            <div>
                <h2> Employee Functions</h3>
                <div class="Empfunctions">
                <a class="func"href="Empcreate.php">Add Employee</a><br>
                <a class="func" href="Empupdate.php">Modify Employee</a><br>
                <a class="func" href="Empdelete.php">Delete Employee</a><br>
                </div>

                <h2> Employee Account Functions</h3>
                <div class="Empfunctions">
                <a class="func"href="uacreate.php">Add Employee Account</a><br>
                <a class="func" href="uaupdate.php">Modify Employee Account</a><br>
                <a class="func" href="uadelete.php">Delete Employee Account</a><br>
                </div>

                <h2> Department Functions</h3>
                <div class="Empfunctions">
                <a class="func"href="Depcreate.php">Add Department</a><br>
                <a class="func" href="Depupdate.php">Modify Department</a><br>
                <a class="func" href="Depdelete.php">Delete Department</a><br><br>

                <button onclick="return confirm('Do you want to sign out of the employee management system?')"><a href="login.php">Sign Out</a></button>
                </div>
            </div>

        </div>

        <div class="righthand">
            <h1>Employee Management System</h1><hr class="rhr">
        
        <div class="wrap">
            <div class="cards">
                <h2>View Employee Database</h2>
                <p class="pstyle">Click here to view employee information, this page contains
                   all of the information of the employees (EmployeeID, Name, Address, and more!)</p><br><br>
                <a class="sout" href="Empread.php">View</a>
            </div>

            <div class="cards">
                <h2>View Employee Account Database</h2>
                <p class="pstyle">Click here to view employee account information, this page contains all
                    the information regarding account made and assigned by Admin for employee (UserID, Username,
                    Password, Role and EmployeeID)
                </p>
                <a class="sout" href="uaread.php">View</a>
            </div>

            <div class="cards">
                <h2>View Department Database</h2>
                <p class="pstyle">Click here to view departments assigned to employees, this page contains (Department Name and Department ID)</p><br><br><br>
                <a class="sout" href="Depread.php">View</a>
            </div>

        </div>

        </div>
   </div>

<script src="script.js"></script>
</body>

</html>