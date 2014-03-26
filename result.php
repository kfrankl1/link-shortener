<?php
	//require('config.php');
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
			$stmt = $dbh->prepare("INSERT INTO link (access_code, url) VALUES (:access_code, :url);");
			$stmt->bindParam(':access_code', $uniqueKey);
			$stmt->bindParam(':url', $_REQUEST['url']);
			$dataAffected = $stmt->execute();
			
			if ($dataAffected != 1) {
				$message = "Data did not insert! Access code " . $uniqueKey;
			} else {
				$message = "Data inserted! Access code " . $uniqueKey;
				$shortLink = "http://localhost:8888/link-shortener/redirect.php?q=" . $uniqueKey;
				//$shortLink = "http://localhost:8888/link-shortener/" . $uniqueKey;
			}
		} else {
			$message = "Could not find a unique code :(  Please try again soon!";
		}
		$dbh = null;
	} catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br>";
		die();
	}
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