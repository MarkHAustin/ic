<?
//Connect To Database
$hostname='internal-db.s125076.gridserver.com';
$username='db125076_icuser';
$password='-Wc3P^os5r{';
$dbname='db125076_IC';

// Create connection
$conn = new mysqli($hostname, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

?>