
<?php 
	if ($_SESSION['user']['valid'] == 'true') {
		if (!isset($action)) { $action = 1; }
		print '
		<h1>Administracija</h1>
            <div id="admin">
                <nav>
                    <ul class="nav_bg_color">
                        <li><a href="index.php?menu=7&amp;action=1">Korisnici</a></li>
                        <li><a href="index.php?menu=7&amp;action=2">Vijesti</a></li>
                    </ul>
                </nav>
            </div>
        <div class="div_admin">'; 
        # Admin Users
        if ($action == 1) { include("admin/users.php"); }
        
        # Admin News
        else if ($action == 2) { include("admin/news.php"); }
        print '</div>';
	}
	else {
		$_SESSION['message'] = '<p>Molim da se registrirate ili prijavite sa svojim podacima!</p>';
		header("Location: index.php?menu=6");
	}
?>