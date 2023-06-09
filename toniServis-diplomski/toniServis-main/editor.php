
<?php 
	if ($_SESSION['user']['valid'] == 'true') {
		if (!isset($action)) { $action = 1; }
		print '
		<h1>Urednik</h1>
            <div id="admin">
                <nav>
                    <ul class="nav_bg_color">
                        <li><a href="index.php?menu=8">Vijesti</a></li>
                    </ul>
                </nav>
            </div>
        <div class="div_admin">'; 
        
        if ($action == 1) { include("editor/news.php"); }
        print '</div>';
	}
	else {
		$_SESSION['message'] = '<p>Molim da se registrirate ili prijavite sa svojim podacima!</p>';
		header("Location: index.php?menu=6");
	}
?>