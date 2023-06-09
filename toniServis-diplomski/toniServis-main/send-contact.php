<!DOCTYPE html>
<html lang="hr">
	<head>
        <link rel="stylesheet" href="style.css">

		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta name="description" content="some description">
		<meta name="keywords" content="servis, računala, održavanje">
		<meta name="author" content="Antonijo Ruškovački">
		
		<link rel="icon" href="images/favicon.ico" type="image/x-icon"/>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
		
		<title>ToniiServis - Kontakt</title>
	</head>
<body>
	<header>
		<div class="hero-image" style="height: 200px;"></div>
		<nav>
			<ul>
                <li><a href="index.php?menu=1">Početna stranica</a></li>
                <li><a href="index.php?menu=2">Vijesti</a></li>
                <li><a href="index.php?menu=3">Kontakt</a></li>
                <li><a href="index.php?menu=4">O nama</a></li>
			</ul>
		</nav>
	</header>
	<main>
		<h1>Kontakt forma</h1>
		<div id="contact">
		<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11145.039595861264!2d16.030453639736393!3d45.70582266567099!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47667e36f23379af%3A0x2600ad5153371002!2zR3JhZGnEh2k!5e0!3m2!1shr!2shr!4v1610191701194!5m2!1shr!2shr" width= 900 height= 400 style="border:0" allowfullscreen></iframe>
			<?php
				print '<p style="text-align:center; padding: 10px; background-color: #d7d6d6;border-radius: 5px;">Zaprimili smo vaše pitanje. Odgovoriti ćemo unutar 24 sata.</p>';
				$EmailHeaders  = "MIME-Version: 1.0\r\n";
				$EmailHeaders .= "Content-type: text/html; charset=utf-8\r\n";
				$EmailHeaders .= "From: <antonijo.ruska@gmail.com>\r\n";
				$EmailHeaders .= "Reply-To:<antonijo.ruska@gmail.com>\r\n";
				$EmailHeaders .= "X-Mailer: PHP/".phpversion();
				$EmailSubject = 'Probna stranica - Kontakt';
				$EmailBody  = '
				<html>
				<head>
				   <title>'.$EmailSubject.'</title>
				   <style>
					body {
					  background-color: #ffffff;
						font-family: Arial, Helvetica, sans-serif;
						font-size: 16px;
						padding: 0px;
						margin: 0px auto;
						width: 500px;
						color: #000000;
					}
					p {
						font-size: 14px;
					}
					a {
						color: #00bad6;
						text-decoration: underline;
						font-size: 14px;
					}
					
				   </style>
				   </head>
				<body>
					<p>Ime: ' . $_POST['firstname'] . '</p>
					<p>Prezime: ' . $_POST['lastname'] . '</p>
					<p>E-mail: <a href="mailto:' . $_POST['email'] . '">' . $_POST['email'] . '</a></p>
					<p>Država: ' . $_POST['country'] . '</p>
					<p>Predmet: ' . $_POST['subject'] . '</p>
				</body>
				</html>';
				print '<p>Ime: ' . $_POST['firstname'] . '</p>
				<p>Prezime: ' . $_POST['lastname'] . '</p>
				<p>E-mail: ' . $_POST['email'] . '</p>
				<p>Država: ' . $_POST['country'] . '</p>
				<p>Predmet: ' . $_POST['subject'] . '</p>';
				mail($_POST['email'], $EmailSubject, $EmailBody, $EmailHeaders);
			?>
		</div>
	</main>
	<footer>
        <p >Copyright &copy; '. date("Y").' Antonijo Ruškovački. <a href="https://github.com/ruska77/toniServis" target="_blank"><img src="images/github.svg" title="Github" alt="Github"></a></p>
	</footer>

	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-98455037-1', 'auto');
	  ga('send', 'pageview');

	</script>
</body>
</html>
