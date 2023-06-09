<?php 
	print '
	<h1>Registriraj se</h1>
	<div id="register">';
	
	if ($_POST['_action_'] == FALSE) {
		print '
		<form action="" id="registration_form" name="registration_form" method="POST"  enctype="multipart/form-data">
			<input type="hidden" id="_action_" name="_action_" value="TRUE">
			
			<label for="fname">Ime*</label>
			<input type="text" id="fname" name="firstname" placeholder="Vaše ime.." required>
			<label for="lname">Prezime*</label>
			<input type="text" id="lname" name="lastname" placeholder="Vaše prezime.." required>
				
			<label for="email">E-mail adresa*</label>
			<input type="email" id="email" name="email" placeholder="Vaš e-mail.." required>
			
			<label for="username">Korisničko ime:* <small>(Korisničko ime mora imati minimalno 5 i maksimalno 10 znakova.)</small></label>
			<input type="text" id="username" name="username" pattern=".{5,10}" placeholder="Korisničko ime.." required><br>
			
									
			<label for="password">Lozinka:* <small id="small">(Lozinka mora imati minimalno 4 znaka.)</small></label>
			<input type="password" id="password" name="password" placeholder="Lozinka.." pattern=".{4,}" required>
			
			<label for="country">Države:</label>
			<select name="country" id="country">
				<option value="" selected disabled hidden>Odaberi...</option>';
				$query  = "SELECT * FROM countries";
				$result = @mysqli_query($MySQL, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '<option value="' . $row['country_code'] . '">' . $row['country_name'] . '</option>';
				}
			print '
			</select  required>

			<label for="city">Mjesto*</label>
			<input type="text" id="city" name="city" placeholder="Mjesto korinsika.." required>

			<label for="street">Adresa*</label>
			<input type="text" id="street" name="street" placeholder="Adresa korinsika.." required>
			
			<label for="picture">Slika:</label>
			<input type="file" id="picture" name="picture">
			<hr>
			<input type="submit" value="Pošalji">
		</form>';
	}
	else if ($_POST['_action_'] == TRUE) {
		if(!isset($_POST['country'])){
			echo '<p>Niste upisali državu!</p>';
			echo "<a href=\"javascript:history.go(-1)\">Pkušajte ponovno...</a>";
		}
		else{
			$query  = "SELECT * FROM users";
			$query .= " WHERE email='" .  $_POST['email'] . "'";
			$query .= " OR username='" .  $_POST['username'] . "'";
			$result = @mysqli_query($MySQL, $query);
			$row = @mysqli_fetch_array($result, MYSQLI_ASSOC);
			
			$query_country = "SELECT * FROM countries WHERE country_code = '" . $_POST['country'] . "'";
			$result_country = @mysqli_query($MySQL, $query_country);
			$row_country = @mysqli_fetch_array($result_country);

			if (!isset($row['email']) || !isset($row['username'])) {
				$pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost' => 12]);
				$query = "";
				$ID = NULL;
				if($_FILES['picture']['error'] == UPLOAD_ERR_OK && $_FILES['picture']['name'] != "") {
					$ext = strtolower(strrchr($_FILES['picture']['name'], "."));
					$picture_name = $_POST['username'] . $ext;
					$_picture = 'images/' . $picture_name;
					copy($_FILES['picture']['tmp_name'], $_picture);
					if ($ext == '.jpeg' || $ext == '.jpg' || $ext == '.png' || $ext == '.gif') {
						@mysqli_query($MySQL,"INSERT INTO pictures(path) VALUES ('" . $picture_name . "')");
						$ID = mysqli_insert_id($MySQL);
					}
					$query  = "INSERT INTO users (firstname, lastname, email, username, password, country_id, city, street, picture_id, role)";
					$query .= " VALUES ('" . $_POST['firstname'] . "', '" . $_POST['lastname'] . "', '" . $_POST['email'] . "', '" . $_POST['username'] . "', '" . $pass_hash . "', " . $row_country['id'] . ", '". $_POST['city'] . "', '". $_POST['street'] . "', " . $ID . ", 'user')";
				}
				else{
					$query  = "INSERT INTO users (firstname, lastname, email, username, password, country_id, city, street, role)";
					$query .= " VALUES ('" . $_POST['firstname'] . "', '" . $_POST['lastname'] . "', '" . $_POST['email'] . "', '" . $_POST['username'] . "', '" . $pass_hash . "', " . $row_country['id'] . ", '". $_POST['city'] . "', '". $_POST['street'] . "', 'user')";
				}
				$result = @mysqli_query($MySQL, $query);
				
				echo '<p>' . ucfirst(strtolower($_POST['firstname'])) . ' ' .  ucfirst(strtolower($_POST['lastname'])) . ', hvala na registraciji. </p>
				<hr>';
			}
			else {
				echo '<p>Korisnik sa unesenom email adresom ili korisničkim imenom već postoji!</p>';
				echo "<a href=\"javascript:history.go(-1)\">Pkušajte ponovno...</a>";
			}
		}
	}
	print '
	</div>';
	
	@mysqli_close($MySQL);

?>