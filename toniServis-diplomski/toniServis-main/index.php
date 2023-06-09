<?php
	define('__APP__', TRUE);
	
	session_start();

	include("dbconn.php");

	if(isset($_GET['menu'])) { $menu = (int)$_GET['menu']; }
	if(isset($_GET['action'])) { $action   = (int)$_GET['action']; }
	if(!isset($_POST['_action_']))  { $_POST['_action_'] = FALSE;  }
	if (!isset($menu)) { $menu = 1; }

	include_once("functions.php");
	
print '
<!DOCTYPE html>
<html lang="hr">
	<head>
		<link rel="stylesheet" href="style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta name="description" content="some description">
		<meta name="keywords" content="servis, računala, održavanje">
		
		<meta itemprop="name" content="Hello Example">
		<meta itemprop="description" content="Some description">
		<meta itemprop="image" content="Your URL"> 
		
		<meta property="og:title" content="Hello Example">
		<meta property="og:type" content="article">
		<meta property="og:url" content="Your URL">
		<meta property="og:image" content="Your URL">
		<meta property="og:description" content="Some description">
		<meta property="article:tag" content="servis, računala, održavanje">
		<meta name="author" content="antonijo.ruska@gmail.com">
		
		<link rel="icon" href="images/favicon.ico" type="image/x-icon"/>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
		
		<title>ToniiServis</title>
	</head>
<body>
	<header>
		<div'; if ($menu > 1) { print ' class="hero-subimage"'; } else { print ' class="hero-image"'; }  print '></div>
		<nav>';
			include("menu.php");
		print '</nav>
	</header>
	<main>';
		if(!isset($menu) || $menu == 1){include("home.php");}
		else if ($menu == 2) { include("news.php"); }
		else if ($menu == 3) { include("contact.php"); }
		else if ($menu == 4) { include("about.php"); }
		else if ($menu == 5) { include("register.php"); }
		else if ($menu == 6) { include("signin.php"); }
		else if ($menu == 7) { include("admin.php"); }
		else if ($menu == 8) { include("editor.php"); }
		else if ($menu == 9) { include("user.php"); }
		else if ($menu == 10) { include("omdb.php"); }
		else if ($menu == 11) { include("football.php"); }
	print '</main>
	<footer>
		<p >Copyright &copy; '. date("Y").' Antonijo Ruškovački. <a href="https://github.com/ruska77/toniServis" target="_blank"><img src="images/github.svg" title="Github" alt="Github"></a></p>
	</footer>
</body>
</html>';
?>
