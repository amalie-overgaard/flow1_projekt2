<?php
session_start();
require_once('dbcon.php');

//delete funktion på billeder
$delete = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
if(isset($delete)){
	$slet = 'DELETE FROM image WHERE id = ?';
	$stmt = $link->prepare($slet);
	$stmt->bind_param('i', $delete);
	$stmt->execute();
	
	if($stmt->affected_rows > 0){
		echo "Billedet er nu slettet";
	}
}
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
	<?php
		if(empty($_SESSION['un'])) {
			echo 'Du skal være logget ind for at se indholdet';
			die();
		}
	?>


		<h1>Dine billeder</h1> 
		
	<?php
		echo 'Du er logget ind som '.$_SESSION['username'].'<br>'; 
	?>
	
	<a href="upload.php">Gå tilbage</a><br>
	
	<?php
		$sql = 'SELECT id, title, imageurl FROM image ORDER BY last_update DESC';
		$stmt = $link->prepare($sql);
		$stmt->execute();
		$stmt->bind_result($id, $title, $url);
	
		while($stmt->fetch()){ ?>
			<h2><?=$id?>: <?=$title?></h2>
			<img src="<?=$url?>" alt="<?=$url?>"  width="200px" /><br>
	<a href="?delete=<?=$id?>">
		<button>Slet</button>
	</a>
	<a href="rediger.php?rediger=<?=$id?>">
		<button>Rediger</button>	
	</a>
			<?php } ?>
	
</body>
</html>