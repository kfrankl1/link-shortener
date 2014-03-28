<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Link Shortener</title>
	<link type="text/css" rel="stylesheet" href="style.css" />
</head>

<body>
    <div id="wrapper">
        <h1>Link Shortener</h1>
        
	    <p><a href="<?php echo $_REQUEST['url']; ?>"><?php echo $_REQUEST['url']; ?></a></p>
        
        <form action="result.php" method="post">
            <label for="url">URL</label><br>
            <input type="url" required="required" class="url" name="url" id="url"  /><br><br>
            <input type="submit" class="button" value="Create Link" />
        </form>
    </div><!-- #wrapper -->
</body>
</html>