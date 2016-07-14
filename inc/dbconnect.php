<?
//Connect To Database
$hostname='internal-db.s125076.gridserver.com';
$username='db125076_icadmin';
$password='hM1l3m4rk3r@';
$dbname='db125076_IC';


// Create connection
$conn = new mysqli($hostname, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

?>