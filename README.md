Employee Management System
Is a secure, lightweight, and highly optimized web-based CRUD (Create, Read, Update, Delete) application built using PHP (Procedural style) and MySQL. This system is specifically
designed to manage internal company directory records, handle organizational departments, and safely provision system authentication accounts while enforcing strict database referential
integrity.

Core System Overview
The Employee Management CRUD System serves as a secure central dashboard for administrative personnel. The application minimizes architectural complexity down to
three core tables to guarantee ease of deployment while dynamically executing database protection protocols. Administrators can seamlessly onboard staff, assign them 
to distinct corporate divisions, manage personal profile attributes, provision unique application logins, and execute safe purges without causing orphan records or application crashes.

Key Features & Capabilities
Full CRUD Life-cycle: Complete, safe implementation of CRUD actions across all operational tables.
Smart Dropdown Select Menus: Form select inputs dynamically retrieve up-to-date data from parent tables (e.g., Department dropdown draws from the department table;
Employee ID dropdown draws from the employee table).
SQL Injection Countermeasures: 100% integration of native MySQL Prepared Statements (mysqli_prepare and mysqli_stmt_bind_param) across all writing operations.
Cross-Site Scripting (XSS) Defenses: Automatic output encoding via htmlspecialchars() to guarantee browser safety.
Post-Redirect-Get (PRG) Workflow Pattern: Redirect structures clear form memories upon transaction completion to prevent duplicate values when refreshing pages (F5).

Database & Architectural Design
The website operates on an three-table relational model designed to prevent data redundancy and separate administrative directory details from security profiles:

1. department Table (Lookup Table)
Manages organizational divisions.
DepartmentID (INT, Primary Key, Auto-Increment)
DepartmentName (VARCHAR(100), Not Null)

2. employee Table (Master Table)
Holds primary demographic profiles and maps them to corporate units.
EmployeeID (INT, Primary Key, Auto-Increment)
FirstName (VARCHAR(50), Not Null)
MiddleName (VARCHAR(50), Nullable)
LastName (VARCHAR(50), Not Null)
Gender (VARCHAR(50), Not Null)
BirthDate (DATE, Not Null)
ContactNumber (VARCHAR(50), Not Null)
EmailAddress (VARCHAR(100), Unique, Nullable)
Street (VARCHAR(50), NOT NULL)
City (VARCHAR(50), NOT Null)
Province (VARCHAR(50), Not Null)
DepartmentID (INT, Foreign Key referencing department(DepartmentID), Nullable)

3. useraccount Table (Security Table)
Isolates sensitive security authentication profiles on a strict one to one matching basis.
UserID (INT, Primary Key, Auto-Increment)
Username (VARCHAR(50), Unique, Null)
Pass (VARCHAR(50), Not Null)
Role (VARCHAR(50), Not Null)
EmployeeID (INT, Unique, Foreign Key referencing employee(EmployeeID))

Local Installation & Setup (XAMPP)
Follow these step-by-step instructions to set up the system locally on your environment:

Step 1: Install XAMPP
Download and install XAMPP (with Apache, PHP, and MySQL) from Apache Friends.

Step 2: Deploy Project Files
Navigate to your XAMPP web root directory and create a new folder named websys (* I named the folder websys, however you can change it to whichever you prefer as long as the folder 
and the files inside it are inside the htdocs folder inside xampp folder ). Place all the project files (.php, .css) directly inside it:
C:\xampp\htdocs\websys\


Step 3: Start Services
Open the XAMPP Control Panel and click the Start buttons next to Apache and MySQL. Both services should show green status.

Step 4: Import Database Schema
Open your web browser and go to: http://localhost/phpmyadmin/
In the left panel, click New to create a database.
Name the database "crud" and select utf8mb4_general_ci sorting representation, then click Create.
Download the crud(1).sql file in this repo, select the newly created crud database, click import and select the downloaded sql file (crud(1).sql),  and click import.
the database and tables will be set automatically.

Step 5: Verify Connection Configuration
Verify that your database configuration file (db.php) has the correct credentials to link with MySQL:
<?php
$conn = mysqli_connect("localhost", "root", "", "crud");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


Step 6: Run the Web Page
Open your web browser and load the application using:
http://localhost/websys/login.php
(Again, if you modified the folder name websys, make sure it matches the changes in the folder name.  For example if you change the folder name to “myfolder” , you should type in
http://localhost/myfolder/login.php   in the web browser.
The username and login password is set to Tenderjuicy (Username) and Hotdog (Password) by default, If you want to directly access the page just type this in the web browser, 
http://localhost/websys/index.php, it will directly lead to the main page.
 )


Security Framework
The application implements rigorous structural checks to maintain system hygiene:
SQL Injection Blockers: Raw input is never directly injected into query statements. Inputs are dynamically bound to safe database parameters using mysqli_stmt_bind_param and designated 
type specifiers (s for string, i for integer).
Defensive Filtering: * strip_tags() strips out script elements injected into username or name forms.
trim() strips blank spacings to prevent white-space bypass validations.
filter_var($email, FILTER_VALIDATE_EMAIL) screens out invalid address strings.
Cross-Site Scripting (XSS) Sanitization: htmlspecialchars() is automatically used across all output variables to convert standard HTML syntax elements like < and > into
harmless textual representations, preventing browser manipulation.

How It Works (Code Basics)
Post-Redirect-Get (PRG) Pattern
To prevent browsers from displaying warning dialogs or duplicating record transactions when a user clicks the reload button (F5), the system processes actions and then redirects:
// After processing logic successfully...
header("Location: Empcreate.php");
exit();


Session Messaging
Since redirects completely reload the page, temporary status notifications are stored in session memory buffers. They are read, displayed, and safely unset once rendered:
if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']); // Cleans memory after rendering
}
