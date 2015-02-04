<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="mycss.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
	<title>The Spooky Monkey</title>

</head>

<body>

<header>
	<img src="images/header.png" height="200" width="960">
</header>


<?php

$connection = mysqli_connect('localhost', 'root', 'root', '');

if (mysqli_connect_errno() > 0)
{

	echo mysqli_connect_errno(). " : ". mysqli_connect_error();
	die ();

}

$message = "";

function validateForm($name, $email, $TextArea)
{
	$validation = true;
	global $message;

	if ($name == "" || $TextArea == "") 
	{	
		$message = ("<p class='felkod'>Fyll i både Namn och Kommentar!</p>");
		$validation = false;
			
	}	

	if (strlen($TextArea) >= 200) 
	{
		$message = ("<p class='felkod'>Inlägg får inte överskrida 200 tecken!</p>");
		$validation = false;

	}
		
	if ($email != "") 
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
		{
			$message = ("<p class='felkod'>Ej korrekt email!</p>");
			$validation = false;
		}


	}

	return $validation;	
}

?>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$name = [];
	$name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS); 

	$email = [];
	$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

	$TextArea = [];
	$TextArea = filter_var($_POST['TextArea'], FILTER_SANITIZE_SPECIAL_CHARS); 


	if (validateForm($name, $email, $TextArea) == true)
	{
		$sql = "INSERT INTO MyGuests (Name, Email, TextArea)
		VALUES ('$name', '$email', '$TextArea')";

	if (mysqli_query($connection, $sql)) 
	{
    	$message = "<p class='Postat'>Ditt inlägg har postat!</p>";

		$TextArea = $_POST["TextArea"] = null;
		$name = $_POST["name"] = null;
		$email = $_POST["email"] = null;
	}  
	}
}	
?>

<div class="row">
	<div class="col-6">
		<div id="container"  class="container">
			<h2 class="LamnaKommentar">LÄMNA EN KOMMENTAR!</h2>
		
			<form action="#container" method="POST">
 				
 				<h5 class="FormTitel">Namn:</h5>
 				<input type="text" name="name" placeholder="Anna Andersson" value="<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>" />
 				
 				<h5 class="FormTitel">Email:</h5>
 				<input type="email" name="email" placeholder="name@host.com" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>" />
				
				<h5 class="FormTitel">Meddelande:</h5>
				<textarea maxlength="200" name="TextArea"><?php if(isset($_POST['TextArea'])) {echo $_POST['TextArea'];}?></textarea>
				
				<input type="submit"  value="SKICKA"><?php print $message ?>
				<?php echo validateForm($name, $email, $TextArea) ?>

			</form> 
		</div>
	</div>

 	<div class="col-6">
		<div class="Kommentarer">
			<h2 class="gastbokTitel">VÅR GÄSTBOK!</h2>
			
			<?php
			$twitterForm = mysqli_query($connection, "SELECT Name, Email, TextArea FROM MyGuests ORDER BY ID DESC");
				
			while ($row = mysqli_fetch_assoc($twitterForm))  
			{
				echo "<div class='Author'>" . $row['Name'] . "</div>";
				
				if (!empty($row['Email']))
				{
					echo "<div class='Mail'><a href='mailto:".$row['Email']."'><i class='fa fa-envelope'></i></a></div>"; 
				}

  	 			echo "<div class='Message'>" . $row['TextArea'] . "</div>";
  	 			echo "<hr class='Avskiljare'>";
			}

  			mysqli_close($connection);

?>

		</div>
	</div>

</div>
</body>
</html>





















