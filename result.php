<?php
	/* Connect to an ODBC database using driver invocation */
	define("DOMAIN", "localhost:8888/link-shortener/a");
	define("PROTOCOL", "http://");
	$dsn = 'mysql:dbname=link_shortener;host=localhost';
	$user = 'public';
	$password = 'password';
	
	try {
		$dbh = new PDO($dsn, $user, $password);
	//	foreach($dbh->query('SELECT * from link') as $row) {
//			print_r($row);
//		}
		//print "Searching for a new access_code...";
		
		// find a unique access_code
		$unique = false;
		for ($i = 0; $i < 5; $i++) {
			$uniqueKey = substr(md5(rand(0, 1000000)), 0, 6);
			
			$stmt = $dbh->prepare("SELECT COUNT(access_code) FROM link WHERE access_code = ?");
			if ($stmt->execute(array($uniqueKey))) {
				while ($row = $stmt->fetch()) {
					//print_r($row);
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
				$message = "Whoops! Something went ka-plooey... Please try again!";
			} else {
				$message = "Phew! I think that worked!";
				$shortLink = DOMAIN . $uniqueKey;
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
	<link type="text/css" rel="stylesheet" href="style.css" />
</head>

<body>
    <div id="wrapper">
        <h1>Link Shortener</h1>
        
        <p><?php echo $message; ?></p>
        <p>
        	Use &rarr;
            <a href="<?php echo PROTOCOL . $shortLink; ?>"><?php echo $shortLink; ?></a><br>
            <input id="shortLink" name="shortLink" type="text" class="url" value="<?php echo $shortLink; ?>" onfocus="this.select();" onmouseup="return false;" />
        </p>
        <p>
            To go &darr;<br>
        	<a href="<?php echo $_REQUEST['url']; ?>"><?php echo $_REQUEST['url']; ?></a>
        </p>        
    </div><!-- #wrapper -->
</body>
</html>