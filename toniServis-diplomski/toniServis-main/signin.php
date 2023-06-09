<?php 
	print '
	<h1>Prijavi se</h1>
	<div id="signin">';
	
	if ($_POST['_action_'] == FALSE) {
		print '
		<form action="" name="myForm" id="myForm" method="POST">
			<input type="hidden" id="_action_" name="_action_" value="TRUE">
			<label for="username">Korisničko ime:*</label>
			<input type="text" id="username" name="username" value="" pattern=".{5,10}" required>
									
			<label for="password">Lozinka:*</label>
			<input type="password" id="password" name="password" value="" pattern=".{4,}" required>
									
			<input type="submit" value="Prijava">
		</form>';
	}
	else if ($_POST['_action_'] == TRUE) {
		
		$query  = "SELECT * FROM users";
		$query .= " WHERE username='" .  $_POST['username'] . "' AND archive='N'";
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result, MYSQLI_ASSOC);
		
		if (password_verify($_POST['password'], $row['password'])) {
			$_SESSION['user']['valid'] = 'true';
			$_SESSION['user']['id'] = $row['id'];

			$_SESSION['user']['role'] = $row['role'];
			$_SESSION['user']['firstname'] = $row['firstname'];
			$_SESSION['user']['lastname'] = $row['lastname'];
			$_SESSION['user']['username'] = $row['username'];
			$_SESSION['message'] = '<p>Dobrodošli, ' . $_SESSION['user']['firstname'] . ' ' . $_SESSION['user']['lastname'] . '</p>';

			switch ($row['role']) {
				case 'admin':
					header("Location: index.php?menu=7");
					break;
				case 'editor':
					header("Location: index.php?menu=8");
					break;
				case 'user':
					header("Location: index.php?menu=9");
					break;
			}
		}
		else {
			unset($_SESSION['user']);
			$_SESSION['message'] = '<p>Upisali ste nevažeće korisničko ime ili lozinku!</p>';
			header("Location: index.php?menu=6");
		}
	}
	print '
	</div>';
	@mysqli_close($MySQL);
?>