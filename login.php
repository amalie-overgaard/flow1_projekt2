<?php session_start(); ?><!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" type="text/css" href="style.css"
</head>

<body>

<?php
if (filter_input(INPUT_POST, 'submit')){
	$un = filter_input(INPUT_POST, 'un')
		or die('Missing/illegal un parameter');
	$pw = filter_input(INPUT_POST, 'pw')
		or die('Missing/illegal pw parameter');
	
	// $pwhash = hent fra db;
	require_once('dbcon.php');
	$sql = 'SELECT id, un, pw_hash FROM bruger WHERE un=?';
	$stmt = $link->prepare($sql);
	$stmt->bind_param('s', $un);
	$stmt->execute();
	$stmt->bind_result($id, $uid, $pwhash);
	
	
	
	while($stmt->fetch()) {  }
//	echo 'uid=', $uid, ". ";
//	echo 'pwhash=', $pwhash, '. ';
//	echo 'pw=', $pw, '. ';
	
	if (password_verify($pw, $pwhash)){
		echo '';
		$_SESSION['un'] = $uid;
		$_SESSION['username'] = $un;
		$_SESSION['id'] = $id;
		
	}
	else {
		echo 'Forkert kombination af brugernavn/kodeord';
	}
}
?>

<p>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">

	<h1>Dit private album</h1>
	<p>Her på siden har du mulighed for at uploade dine private billeder, så du kan vise dem som et album. Log ind og kom igang!</p>
	<p><a href="index.php">Gå tilbage og opret en bruger</a></p>
	
	<fieldset>
    	<legend>Login</legend>
    	<input name="un" type="text"     placeholder="Brugernavn" required /><br>
    	<input name="pw" type="password" placeholder="Kodeord"   required /><br><br>
    	<input name="submit" type="submit" value="Log ind" />
	</fieldset>
</form>
</p>

<hr>

<?php
	if(empty($_SESSION['un'])) {
		echo 'Du skal være logget ind for at se indholdet';
	}
	else {
		echo 'Hej '.$_SESSION['username'].'<br>';
		echo 'Du er nu logget ind'.'<br>';
		echo '<a href="upload.php">Upload dit eget billede</a>'.''.'<br>';
		echo '<a href="viewimages.php">Se allerede uploadede billeder</a>';
		echo '<form class="logud" id=login action="logout.php"><p>Husk at logge ud når du er færdig</p><button class="logud-button">Log ud</button> </form>';
	}
?>


</body>
</html>