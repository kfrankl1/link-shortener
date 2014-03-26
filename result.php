<?php
	//$url = "http://www.wayfair.com/Entrada-Glass-Vase-EN80181-ENTA1109.html";
	
	//$_REQUEST['url'];
	//print ($url . "\n\n");
	
	/* Connect to an ODBC database using driver invocation */
	$dsn = 'mysql:dbname=link_shortener;host=localhost';
	$user = 'public';
	$password = 'password';
	
	try {
		$dbh = new PDO($dsn, $user, $password);
		foreach($dbh->query('SELECT * from link') as $row) {
			print_r($row);
		}
		print "Searching for a new access_code...";
		
		// find a unique access_code
		$unique = false;
		for ($i = 0; $i < 5; $i++) {
			$uniqueKey = substr(md5(rand(0, 1000000)), 0, 6);
			
			$stmt = $dbh->prepare("SELECT COUNT(access_code) FROM link WHERE access_code = ?");
			if ($stmt->execute(array($uniqueKey))) {
				while ($row = $stmt->fetch()) {
					print_r($row);
					if ($row["access_code"] == 0) {
						$unique = true;
						$i = 5;
					} // end if
				} // end while
			} // end if
		} // end for
		
		// if a unique access_code is found, insert it
		if ($unique) {
			print "Code is unique! About to insert access_code " . $uniqueKey;
			$stmt = $dbh->prepare("INSERT INTO link (access_code, url) VALUES (:access_code, :url);");
			$stmt->bindParam(':access_code', $uniqueKey);
			$stmt->bindParam(':url', $_REQUEST['url']);
			//$stmt->bindParam(':date_created', date());
			
			print "Preparing stmt";
	//		$stmt = "INSERT INTO link (access_code, url, date_created) VALUES ('" . $uniqueKey . "', '" . $_REQUEST['url'] . "', getdate());";
//			$dataAffected = $dbh->execute($stmt);
			
//			$stmt = $dbh->prepare("INSERT INTO link (access_code, url, date_created) VALUES (:access_code, :url, getdate())");
	//		$stmt->bindParam(':access_code', $uniqueKey);
//			$stmt->bindParam(':url', $_REQUEST['url']);
	//		$data = array(
//					':access_code' => $uniqueKey,
//					':url' => $_REQUEST['url']
//				);

			//$query = "INSERT INTO link (access_code, url, date_created) VALUES ('".$uniqueKey."', '".$_REQUEST['url']."', getdate())";
			print $query;
			$dataAffected = $stmt->execute(); //$dbh->exec($query);
			
			print "Did I lose you?";
			//$dataAffected = $dbh->execute($stmt);
			
			print "Data affected is " . $dataAffected;
			if ($dataAffected != 1) {
				$message = "Data did not insert! Access code " . $uniqueKey;
			} else {
				$message = "Data inserted! Access code " . $uniqueKey;
				$shortLink = "http://localhost:8888/link-shortener/redirect.php?q=" . $uniqueKey;
			}
		} else {
			$message = "Could not find a unique code :(  Please try again soon!";
		}
		$dbh = null;
	} catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br>";
		die();
	}
	
	//try {
//		$dbh = new PDO($dsn, $user, $password);
//	} catch (PDOException $e) {
//		echo 'Connection failed: ' . $e->getMessage();
//	}
	
	
	
	///* Close db connection */
//	$dbh = null;
//	echo 'Connection closed.';
	
	
	
//	// Create connection
//	$con=mysqli_connect("localhost","public","password","link_shortener");
//	
//	// Check connection
//	if (mysqli_connect_errno()) {
//		echo "Failed to connect to MySQL: " . mysqli_connect_error();
//	} else {
//		echo "Connected to db.\n\n";
//	}
//	
//	$uniqueKey = "a8dje9";
//	echo $uniqueKey . "\n\n";
//	
//	$stmt = $dbh->prepare("SELECT COUNT(access_code) FROM link_shortener WHERE access_code = ?");
//	if ($stmt->execute(array($uniqueKey))) {
//		while ($row = $stmt->fetch()) {
//			print_r($row);
//		}
//	}
	
	
	
	//// Find a unique access_code
//	$unique = false;
//	for ($i = 0; $i < 5; $i++) {
//		$uniqueKey = substr(md5(rand(0, 1000000)), 0, 6);
//		
//		$stmt = $dbh->prepare("SELECT COUNT(access_code) FROM link_shortener WHERE access_code = ?");
//		if ($stmt->execute(array($uniqueKey))) {
//			while ($row = $stmt->fetch()) {
//				print_r($row);
//				if ($row["access_code"] == 0) {
//					$unique = true;
//					$i = 5;
//				}
//			}
//		}
//	}
//		
//	if (!$unique) {
//		printf ("Failed to create a unique key after 5 tries.");
//		trigger_error("Failed to create a unique key after 5 tries.");
//	}
//	
//	$stmt = $dbh->prepare("INSERT INTO link (access_code, url, date_created) VALUES (:access_code, :url, :date_created)");
//	$stmt->bindParam(':access_code', $access_code);
//	$stmt->bindParam(':url', $url);
//	$stmt->bindParam(':date_created', $now);
//	
//	// insert row
//	$access_code = $uniqueKey;
//	$url = $_REQUEST['url'];
//	echo ($url);
//	$now = getdate();
//	$stmt->execute();
	
//	// Close connection
//	mysqli_close($con);
//	if (mysqli_connect_errno()) {
//		echo "Connection closed.";
//	}
?> 
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Link Shortener Result</title>

<style type="text/css">
	#wrapper {
		margin: 0 auto;
		width: 50%;
	}
	
	.textfield {
		width: 50EM;
	}
	
	.button {
		padding: 5EM;
	}
</style>
</head>

<body>
    <div id="wrapper">
        <h1>Link Shortener</h1>
        
        <p><?php echo $message; ?></p>
	    <p><a href="<?php echo $_REQUEST['url']; ?>"><?php echo $_REQUEST['url']; ?></a></p>
        <p>
        	Try your new link!<br><br>
            <a href="<?php echo $shortLink; ?>"><?php echo $shortLink; ?></a>
        </p>
        
        <!--
        <form action="index.php" method="post">
            <label for="url">URL</label><br>
            <input type="text" required="required" class="textfield" name="url" id="url" /><br><br>
            <input type="submit" class="button" value="Create Link" />
        </form>
        -->
    </div><!-- #wrapper -->
</body>
</html>