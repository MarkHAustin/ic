<?php
session_start();
include('inc/dbconnectuser.php');


if(isset($_SESSION['clientid'])!="")
{
 header("Location: home.php");
}
if(isset($_POST['btn-login']))
{


$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['pass']);
 
$sql = "SELECT * FROM client WHERE email='$email'";
#echo $sql;
$result = $conn->query($sql);
 
 
while($row = $result->fetch_assoc()) {
  
 if(md5($password) == $row["password"])
 {
 
  $_SESSION['clientname'] = $row['clientname'];
  $_SESSION['clientid'] = $row['id'];
  header("Location: home.php");
 }
 else
 {
  ?>
        <script>alert('wrong details');</script>
        <?php
 }
 
}
}
include('inc/style.css');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Inventory Crawler System</title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
<center>
<div id="login-form">
<ul class="input-list style-1 clearfix">
<form method="post" action='login.php'>
<ul class="input-list style-1 clearfix">
<br><br><br><br><br><br><table align="center" width="30%" border="0" class="imagetable">
<tr><td colspan='2' align='center'><h1>Inventory Crawler by Hoot Interactive</td></tr>

<tr>

<td>email:</td><td><input type="text" name="email" placeholder="Your Email" required placeholder=":focus" class="focus" /></td>
</tr>
<tr>
<td>password:</td><td><input type="password" name="pass" placeholder="Your Password" required placeholder=":focus" class="focus"/></td>
</tr>
<tr>
<td></td><td><br><button type="submit" name="btn-login">Sign In</button></td>
</tr>
</table>
</ul>
</form>
</div>
</center>
</body>
</html>