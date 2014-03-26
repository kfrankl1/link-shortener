<?php
	$dsn = 'mysql:dbname=link_shortener;host=localhost';
	$user = 'public';
	$password = 'password';
	
	try {
		$dbh = new PDO($dsn, $user, $password);
		
		$stmt = $dbh->prepare("SELECT url FROM link WHERE access_code = ?");
		if ($stmt->execute(array($_GET['q']))) {
			while ($row = $stmt->fetch()) {
				$redirect = $row['url'];
			} // end if
		} // end if
		
		$dbh = null;
		
		// Redirect
		header('Location: ' . $redirect);
		die();
	} catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br>";
		die();
	}
	
?>