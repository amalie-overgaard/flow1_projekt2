<?php session_start(); ?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>


<?php
	
if(empty($_SESSION['un'])) {
		echo 'Du skal være logget ind for at se indholdet';
		die();
}
?>
<h1>Dit private album</h1>

<?php
	echo 'Du er logget ind som '.$_SESSION['username'].'<br>'; 
		?>

	<p>Her på siden har du mulighed for at uploade dine private billeder, så du kan vise dem som et album.</p>
	
	<h2>Upload et nyt billede</h2>
	<form action="upload.php" method="post" enctype="multipart/form-data">
    Select image to upload:<br>
    	<input type="text" name="title" placeholder="Image title" required />
    	<input type="file" name="fileToUpload" id="fileToUpload"><br>
    	<input type="submit" value="Upload Image" name="submit">
	</form>	
	<br><a href="viewimages.php">Se allerede uploadede billeder</a>
	<form class="logud" id=login action="logout.php"><p>Husk at logge ud når du er færdig</p><button class="logud-button">Log ud</button> </form>
<?php
	
$title = filter_input(INPUT_POST, 'title')
	or die('');
	
$target_dir = "..\\uploads\\";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	
// If you need unique names:
//$target_file = $target_dir . uniqid().'.'.$imageFileType;	
	
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
//        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
//	echo " name=|", $_FILES["fileToUpload"]["name"] , "|";
//	echo " targetfile=|", $target_file, "|";
//	echo " title=|", $title, "|";
//	echo " tmp_name=|", $_FILES["fileToUpload"]["tmp_name"], "|";
//	echo " type=", $_FILES['fileToUpload']['type'];
//	echo " size=", $_FILES['fileToUpload']['size'];
//	echo " uploaded=", is_uploaded_file($_FILES["fileToUpload"]["tmp_name"]);
// 	
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "Filen ". basename( $_FILES["fileToUpload"]["name"]). " er blevet uploaded.";
		
		//Hent databaseforbindelsen
		require_once('dbcon.php');
		
		$sql = 'INSERT INTO image (imageurl, title, bruger_id) VALUES (?, ?, ?)';
		$stmt = $link->prepare($sql);
		$id = $_SESSION['id'];
//		echo " sql=|", $sql, "|";

		$stmt->bind_param('sss', $target_file, $title, $id);
		$stmt->execute();
		if ($stmt->affected_rows > 0) {
			echo 'Data er sat ind i databasen';
		}
		else {
			echo 'Could not add the file to the database :-(';
		}
		
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

?>

</body>
</html>