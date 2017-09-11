<?php session_start(); ?><!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Opret bruger</title>
<link rel="stylesheet" type="text/css" href="style.css"
</head>

<body>
<?php
if (filter_input(INPUT_POST, 'submit')){
	
	$un = filter_input(INPUT_POST, 'un')
		or die('Missing/illegal un parameter');
	$pw = filter_input(INPUT_POST, 'pw')
		or die('Missing/illegal pw parameter');
	
	$pw = password_hash($pw, PASSWORD_DEFAULT);
	
	echo '';
	
	require_once('dbcon.php');
	$sql = 'INSERT INTO bruger (un, pw_hash) VALUES (?, ?)';
	$stmt = $link->prepare($sql);
	$stmt->bind_param('ss', $un, $pw);
	$stmt->execute();
	
	if ($stmt->affected_rows > 0){
		echo 'user '.$un.' added.';
	}
	else {
		echo 'Could not add the user...';
	}
}
?>

<p>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">

	<h1>Dit private album</h1>
	<p>Her på siden har du mulighed for at uploade dine private billeder, så du kan vise dem som et album. Opret en bruger og kom igang!</p>
	<fieldset>
    	<legend>Opret bruger</legend>
    	<input name="un" type="text"     placeholder="Brugernavn" required /><br>
    	<input name="pw" type="password" placeholder="Kodeord"   required /><br>
    	<input name="submit" type="submit" value="Opret bruger" />
	</fieldset>
</form>
</p>
	<p>or <a href="login.php">Log ind</a></p>
</body>
</html>




