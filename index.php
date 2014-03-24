<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Link Shortener</title>

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
        
	    <p><a href="<?php echo $_REQUEST['url']; ?>"><?php echo $_REQUEST['url']; ?></a></p>
        
        <form action="result.php" method="post">
            <label for="url">URL</label><br>
            <input type="text" required="required" class="textfield" name="url" id="url" /><br><br>
            <input type="submit" class="button" value="Create Link" />
        </form>
    </div><!-- #wrapper -->
</body>
</html>