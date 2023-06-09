
<?php 
	if ($_SESSION['user']['valid'] == 'true') {
		if (!isset($action)) { $action = 1; }
		print '
		<h1>Korisnik</h1>
            <div id="admin">
                <nav>
                    <ul class="nav_bg_color">
                        <li><a href="index.php?menu=9">Vijesti</a></li>
                    </ul>
                </nav>
            </div>
        <div class="div_admin">'; 
        
        if ($action == 1) { include("user/news.php"); }
        print '</div>';
	}
	else {
		$_SESSION['message'] = '<p>Molim da se registrirate ili prijavite sa svojim podacima!</p>';
		header("Location: index.php?menu=6");
	}
?>