<?php
	$dsn = 'mysql:dbname=link_shortener;host=localhost';
	$user = 'public';
	$password = 'password';
	
	//echo "Before db connect";
	
	try {
		$dbh = new PDO($dsn, $user, $password);
	//	echo "Connected to db!";
//		echo $_GET['q'];
		
		$stmt = $dbh->prepare("SELECT url FROM link WHERE access_code = ?");
		if ($stmt->execute(array($_GET['q']))) {
			//echo "Successful execution of query";
			while ($row = $stmt->fetch()) {
				//print_r($row);
				$redirect = $row['url'];
			} // end if
		} // end if
		
		//echo "Redirect code: " . $redirect;
		
		$dbh = null;
		//echo "Db connection closed!";
		
		// Redirect
		//echo "About to redirect!";
		header('Location: ' . $redirect);
		die();
	} catch (PDOException $e) {
		//print "Error!: " . $e->getMessage() . "<br>";
		die();
	}
	
?>