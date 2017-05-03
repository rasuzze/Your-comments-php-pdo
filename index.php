
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
?> 

<!DOCTYPE html>
<html>
<head>
	<title></title>

</head>
<h2 style="text-align: center">Your comments:</h2>

<?php 
$host = 'localhost';
$db   = 'scotchbox';
$user = 'root';
$pass = 'root';
$charset = 'utf8';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new PDO($dsn, $user, $pass);
$query = $pdo->query("SELECT * FROM `posts`");
$result = $query->fetchAll();
// echo '<pre>';
// var_dump($result);
// echo '</pre>';

if (array_key_exists('op', $_GET)) {
	$op = $_GET['op'];


	if($op == 'delete' )	{
  		$delete = $_GET["id"];
  		$sql = $pdo->prepare("DELETE FROM `posts` WHERE `id`=:delete"); 
  		$deleteresult=$sql->execute([
  				'delete'=>$delete
  			]);
  		echo "<p>Deleted</p>";
  		echo '<a href="?">Back to list</a>';
	}
	
	if ($op == 'edit') {
		$edit = $_GET["id"];
		if (isset($_POST['submit'])) {
			$name = $_POST['name'];
			$content = $_POST['content'];
			$update = $pdo->prepare("UPDATE `posts` SET `name`=:name, `content`=:content WHERE `id`=:edit");
			$updateresult = $update->execute([
				'name'=> $name,
				'content'=> $content,
				'edit'=>$edit
			]);
}
		
		$update = $pdo->prepare("SELECT * FROM `posts` WHERE `id`=:edit");
		$editresult = $update -> execute ([
				'edit'=> $edit
			]);
		$result = $update->fetchAll();
		
		foreach ($result as $v):
			$name = $v['name'];
			$content = $v['content'];

?>
<form  action="index.php?op=edit&id=<?php echo $edit; ?>" method="post">
	<label>Name </label>
	<input type="text" name="name" value =" <?php echo $name; ?> " style="margin: 20px; padding: 4px; width: 20%"><br>
	<label>Comment </label>
	<textarea type="text" name="content" value ="$content" cols=45 rows=10 style="margin: 10px"> <?php echo $content; ?> </textarea><br>
	<input type="submit" name="submit" value="SUBMIT" style="margin: 10px; padding: 4px; background-color: GhostWhite ;">
</form>
<?php

		echo "<h3>".$name. '</h3><p> ' .$content."</p>"; 
		endforeach;
		echo "<a href='?'>Back</a>";

	} 
	
	if ($op == 'insert' && isset($_POST['submit'])) {
		
			$name = $_POST['name'];
			$content = $_POST['content'];
			$insert = $pdo->prepare("INSERT INTO `posts` SET `name`=:name, `content`=:content");
			$insertresult = $insert->execute([
				'name'=> $name,
				'content'=> $content
			]);
		echo "<a href='?'>Back</a>";
	}

} else {

?>
<form  action="index.php?op=insert" method="post">
	<label>Name </label>
	<input type="text" name="name" style="margin: 20px; padding: 4px; width: 20%"><br>
	<label>Comment </label>
	<textarea type="text" name="content" cols=45 rows=10 style="margin: 10px"></textarea><br>
	<a href="index.php?op=insert"><input type="submit" name="submit" value="SUBMIT" style="margin: 10px; padding: 4px; background-color: GhostWhite ;"></a>
</form>

<?php


	foreach ($result as $i => $value) :
		echo "<h3>".$value["name"]. '</h3><p> ' .$value["content"]."</p>"; 
		echo '<a href="index.php"></a>';
		echo '<a href="index.php?op=delete&id='.$value["id"].'">Delete </a></br>';
		echo '<a href="index.php?op=edit&id='.$value["id"].'">Edit </a>';
		echo '<a href="index.php?op=insert&id='.$value["id"].'"></a>';
	endforeach;

} 







 ?>
 <body style="text-align: center;">
</body>
</html>

