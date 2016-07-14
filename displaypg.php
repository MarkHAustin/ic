<form method="post" action="#">
URL to search:<input type="text" name='urlsearch'><br>


<input type='submit'>
</form>


<?

include('inc/functions.php');

if (!$_POST['urlsearch']) {
	die();
}

$web = get_data($_POST['urlsearch']);

if (!$web) {

	$web = file_get_contents($_POST['urlsearch']);

}		

		
$web = str_replace("<","[",$web);
$web = str_replace(">","]<br>",$web);

echo $web;


?>