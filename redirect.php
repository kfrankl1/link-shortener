<?php
	//echo "Hello";
	$dsn = 'mysql:dbname=link_shortener;host=localhost';
	$user = 'public';
	$password = 'password';
	
	try {
		//echo "Inside try";
		$dbh = new PDO($dsn, $user, $password);
		//echo "Access code: " . $_GET['q'];
		
		$stmt = $dbh->prepare("SELECT url FROM link WHERE access_code = ?");
		if ($stmt->execute(array($_GET['q']))) {
			while ($row = $stmt->fetch()) {
				$redirect = $row["url"];
			} // end if
		} // end if
		//echo "After query execute";
		
		
		if ($redirect == null)
		{
			$redirect = "Is null :(";
		}
		
		$dbh = null;
		//echo "Db null";
		// Redirect
		header('Location: ' . $redirect);
		die();
	} catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br>";
		die();
	}
?>