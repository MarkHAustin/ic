<?


if ($_POST['headline']) {
$myFile = "html/" . $_POST['adname'] . ".html";   
$fh = fopen($myFile, 'w'); // or die("error");  
$stringData = "<html><body><head><title></title></head>


<img src='" . $_POST['productimg'] . "' alt='product'>
<img src='" . $_POST['logoimg'] . "' alt='logo'><br>
<p>" . $_POST['description'] . "</p>
<h1 class='header'>" . $_POST['headline'] . "</h1>";
fwrite($fh, $stringData);

echo "<iframe src='html/" . $_POST['adname'] . ".html' width='800' height='400'></iframe><hr>";
echo "paste this link into adwords img ad builder<br><br><br>";
echo "www.hootinteractive.com/tools/dealer/adbuilder/html/" . $_POST['adname'] . ".html";



}

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>



<body>
<h1>image ad builder</h1>
<table>
<form method = "post" action='#' name="form" id='form'></td></tr>
<tr><td>headline:</td><td><input name = "headline"></td></tr>
<tr><td>logo (image location):</td><td><input name = "logoimg"></td></tr>
<tr><td>product (image location):</td><td><input name = "productimg"></td></tr>
<tr><td>html file name (ex: cadenza_rivergate) (no '.html'):</td><td><input name = 'adname'></td></tr>
<tr><td></td><td><input type='submit'></td></tr>

</form>




</body>
</html>
