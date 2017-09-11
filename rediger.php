<?php session_start(); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<?php
require_once('dbcon.php');
?>

<?php
	$rediger = filter_input(INPUT_GET, 'rediger', FILTER_VALIDATE_INT) or die("Ugyldig id");
	$billedetitle = "";
	$billedeid = "";
	if(isset($rediger)){
		$info = 'SELECT id, title FROM image WHERE id=?';
		$stmt = $link->prepare($info);
		$stmt->bind_param('i', $rediger);
		$stmt->execute();
		$stmt->bind_result($id, $title);
		$stmt->store_result();
		
		while($stmt->fetch()){
			$billedetitle .= $title;
			$billedeid .= $id;
		}
	}
	
if($cmd = filter_input(INPUT_POST, 'cmd')){
	
		$cid = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT)
			or die('Missing/illegal idposter parameter');
		$cnam = filter_input(INPUT_POST, 'title')
			or die('Missing/illegal categoryname parameter');
		
		$sql = 'UPDATE image SET title=? WHERE id=?';
		$stmt = $link->prepare($sql);
		$stmt->bind_param('si', $cnam, $cid);
		$stmt->execute();
		
		if($stmt->affected_rows >0){
			echo 'Category name updated to '.$cnam;
		}
		else {
			echo 'Could not change name of category '.$cid;
		}
	
}
	?>
	<form action="<?= $_SERVER['PHP_SELF'] ?>?rediger=<?=$billedeid?>" method="post">
	<fieldset>
    	<legend>Opdater titel</legend>
    	<input type="hidden" name="id" value="<?=$billedeid?>" />
    	<input name="title" type="text" value="<?=$billedetitle?>" placeholder="Title" required />
		<button name="cmd" value="opdater" type="submit">Opdater</button>
	</fieldset>
</form>

<a href="viewimages.php">Gå tilbage</a>
	
	
</body>
</html>