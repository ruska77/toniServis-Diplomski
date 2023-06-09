<?php 
	
	if (isset($_POST['edit']) && $_POST['_action_'] == 'TRUE') {
		$user = @mysqli_query($MySQL,"SELECT * FROM users WHERE id=" . (int)$_POST['edit']);
		$user_result = @mysqli_fetch_array($user);

		$ID = $user_result['picture_id'];
		if(!isset($ID)){
			$ID = 'NULL';
			//header("Location: index.php?menu=7&action=aaaaaaaaaaaaaaaaaaaa");
		}
		if($_FILES['picture']['error'] == UPLOAD_ERR_OK && $_FILES['picture']['name'] != "") {
            $picture = "images/" . $_POST['username'];
			@unlink($picture); 

			$ext = strtolower(strrchr($_FILES['picture']['name'], "."));
			$picture_name = $_POST['username'] . $ext;
            $_picture = 'images/' . $picture_name;
			copy($_FILES['picture']['tmp_name'], $_picture);
			
			if ($ext == '.jpeg' || $ext == '.jpg' || $ext == '.png' || $ext == '.gif') {
				@mysqli_query($MySQL,"INSERT INTO pictures(path) VALUES ('" . $picture_name . "')");
				$ID = mysqli_insert_id($MySQL);
			}
        }
		$query_country = "SELECT * FROM countries WHERE country_code = '" . $_POST['country'] . "'";
		$result_country = @mysqli_query($MySQL, $query_country);
		$row_country = @mysqli_fetch_array($result_country, MYSQLI_ASSOC);

		$query  = "UPDATE users SET firstname='" . $_POST['firstname'] . "', lastname='" . $_POST['lastname'] . "', email='" . $_POST['email'] . "', username='" . $_POST['username'] . "', country_id=" . $row_country['id'] . ", archive=" . $_POST['archive'] . ", city='". $_POST['city'] . "', street='". $_POST['street'] . "', picture_id=". $ID;
		$query .= " WHERE id=" . (int)$_POST['edit'];
		$query .= " LIMIT 1";
		$result = @mysqli_query($MySQL, $query);

		$_SESSION['message'] = '<p>Uspješno ste izmjenili korisnički račun!</p>';
		header("Location: index.php?menu=7&action=1");
	}
	if (isset($_GET['delete']) && $_GET['delete'] != '') {
		$news_query = @mysqli_fetch_array(@mysqli_query($MySQL, "SELECT picture_id, path FROM users LEFT JOIN pictures ON users.picture_id = pictures.id WHERE id=" .(int)$_GET['delete'] . " LIMIT 1"));
		$picture_id = $news_query['pictures_id'];
		$query  = "DELETE FROM users";
		$query .= " WHERE id=".(int)$_GET['delete'];
		$query .= " LIMIT 1";
		@mysqli_query($MySQL, $query);
		@mysqli_query($MySQL, ("DELETE FROM pictures WHERE id = " . $picture_id . ""));

		$picture = "images/" . $news_query['path'];
		@unlink($picture); 

		$_SESSION['message'] = '<p>Uspješno ste uklonili korisnički račun!</p>';		
		header("Location: index.php?menu=7&action=1");
	}
	if (isset($_GET['id']) && $_GET['id'] != '') {
		$query  = "SELECT * FROM users LEFT JOIN pictures ON users.picture_id = pictures.id";
		$query .= " WHERE users.id=".$_GET['id'];
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
		print '
		<h2>Korisnički profil</h2>
		<img class="profile_img" src="images/' . $row['path'] . '" alt="" title="" />
		<p><b>Ime:</b> ' . $row['firstname'] . '</p>
		<p><b>Preizme:</b> ' . $row['lastname'] . '</p>
		<p><b>Korisničko ime:</b> ' . $row['username'] . '</p>';
		$_query  = "SELECT * FROM countries";
		$_query .= " WHERE id='" . $row['country_id'] . "'";
		$_result = @mysqli_query($MySQL, $_query);
		$_row = @mysqli_fetch_array($_result);
		print '
		<p><b>Država:</b> ' .$_row['country_name'] . '</p>
		<p><b>Mjesto:</b> ' .$row['city'] . '</p>
		<p><b>Adresa:</b> ' .$row['street'] . '</p>
		<p><b>Datum:</b> ' . pickerDateToMysql($row['date']) . '</p>';
	}
	else if (isset($_GET['create']) && $_GET['create']==true){
		if ($_SESSION['user']['role'] == 'admin' || $_SESSION['user']['role'] == 1 || $_SESSION['user']['role'] == 2) {
			print '
			<h1>Registriraj korisnika</h1>
			<div id="register">';
			
			if ($_POST['_action_'] == FALSE) {
				print '
				<form action="" id="registration_form" name="registration_form" method="POST" enctype="multipart/form-data">
					<input type="hidden" id="_action_" name="_action_" value="TRUE">
					
					<label for="fname">Ime*</label>
					<input type="text" id="fname" name="firstname" placeholder="Ime korinsika.." required>

					<label for="lname">Prezime*</label>
					<input type="text" id="lname" name="lastname" placeholder="Prezime korinsika.." required>
						
					<label for="email">E-mail adresa*</label>
					<input type="email" id="email" name="email" placeholder="E-mail korinsika.." required>
					
					<label for="username">Korisničko ime:* <small>(Korisničko ime mora imati minimalno 5 i maksimalno 10 znakova.)</small></label>
					<input type="text" id="username" name="username" pattern=".{5,10}" placeholder="Korisničko ime.." required><br>
					
					<label for="role">Rola*</label>
					<select name="role" id="role">
						<option value="user" selected>Korisnik</option>
						<option value="editor">Urednik</option>
					</select  required>

					<label for="password">Lozinka:* <small id="small">(Lozinka mora imati minimalno 4 znaka.)</small></label>
					<input type="password" id="password" name="password" placeholder="Lozinka korinsika.." pattern=".{4,}" required>
					
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

					<label for="archive">Arhiviraj:</label>
					<input type="radio" name="archive" value="2" checked="checked" /> Da &nbsp;&nbsp;
					<input type="radio" name="archive" value="1" /> Ne
					
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
					$row_country = @mysqli_fetch_array($result_country, MYSQLI_ASSOC);
	
					if (!isset($row['email']) || !isset($row['username'])) {
						$pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost' => 12]);
						
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
							$query  = "INSERT INTO users (firstname, lastname, email, username, password, country_id, city, street, picture_id, role, archive)";
							$query .= " VALUES ('" . $_POST['firstname'] . "', '" . $_POST['lastname'] . "', '" . $_POST['email'] . "', '" . $_POST['username'] . "', '" . $pass_hash . "', " . $row_country['id'] . ", '". $_POST['city'] . "', '". $_POST['street'] . "', " . $ID . ", 'user' ". $_POST['archive'] . ")";
						}
						else{
							$query  = "INSERT INTO users (firstname, lastname, email, username, password, country_id, city, street, role, archive)";
							$query .= " VALUES ('" . $_POST['firstname'] . "', '" . $_POST['lastname'] . "', '" . $_POST['email'] . "', '" . $_POST['username'] . "', '" . $pass_hash . "', " . $row_country['id'] . ", '". $_POST['city'] . "', '". $_POST['street'] . "', 'user', ". $_POST['archive'] . ")";
						}
						$result = @mysqli_query($MySQL, $query);
						
						echo '<p>Korisnik ' . ucfirst(strtolower($_POST['firstname'])) . ' ' .  ucfirst(strtolower($_POST['lastname'])) . ' je registriran. </p>
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
		}
		else {
			print '<p>Zabranjeno</p>';
		}
	}
	else if (isset($_GET['edit']) && $_GET['edit'] != '') {
		if ($_SESSION['user']['role'] == 'admin' || $_SESSION['user']['role'] == 1 || $_SESSION['user']['role'] == 2) {
			$query  = "SELECT * FROM users";
			$query .= " WHERE id=".$_GET['edit'];
			$result = @mysqli_query($MySQL, $query);
			$row = @mysqli_fetch_array($result);
			$checked_archive = false;
			
			print '
			<h2>Uredi korisnički profil</h2>
			<form action="" id="registration_form" name="registration_form" method="POST" enctype="multipart/form-data">
				<input type="hidden" id="_action_" name="_action_" value="TRUE">
				<input type="hidden" id="edit" name="edit" value="' . $_GET['edit'] . '">

				<label for="fname">Ime*</label>
				<input type="text" id="fname" name="firstname" value="' . $row['firstname'] . '" placeholder="Vaše ime.." required>
				<label for="lname">Prezime*</label>
				<input type="text" id="lname" name="lastname" value="' . $row['lastname'] . '" placeholder="Vaše prezime.." required>
					
				<label for="email">E-mail*</label>
				<input type="email" id="email" name="email"  value="' . $row['email'] . '" placeholder="Vaš e-mail.." required>
				
				<label for="username">Korisničko ime*<small> (Korisničko ime mora imati minimalno 5 i maksimalno 10 znakova.)</small></label>
				<input type="text" id="username" name="username" value="' . $row['username'] . '" pattern=".{5,10}" placeholder="Korisničko ime.." required><br>
				
				<label for="country">Država</label>
				<select name="country" id="country">
					<option value="">Odaberi..</option>';
					$_query  = "SELECT * FROM countries";
					$_result = @mysqli_query($MySQL, $_query);
					while($_row = @mysqli_fetch_array($_result)) {
						print '<option value="' . $_row['country_code'] . '"';
						if ($row['country_id'] == $_row['id']) { print ' selected'; }
						print '>' . $_row['country_name'] . '</option>';
					}
				print '
				</select>
				<label for="city">Mjesto*</label>
				<input type="text" id="city" name="city"  value="' . $row['city'] . '" placeholder="Vaše mjesto.." required>
				
				<label for="street">Adresa*</label>
				<input type="text" id="street" name="street"  value="' . $row['street'] . '" placeholder="Vaša adresa.." required>

				<label for="archive">Arhiviraj:</label><br />
				<input type="radio" name="archive" value="2"'; if($row['archive'] == 'Y') { echo ' checked="checked"'; $checked_archive = true; } echo ' /> Da &nbsp;&nbsp;
				<input type="radio" name="archive" value="1"'; if($row['archive'] == 'N') { echo ' checked="checked"'; } echo ' /> Ne

				<label for="picture">Slika:</label>
			 	<input type="file" id="picture" name="picture">
				<hr>
				
				<input type="submit" value="Spremi">
			</form>';
		}
		else {
			print '<p>Zabranjeno</p>';
		}
	}
	else {
		print '
		<h2>Lista korisnika</h2>
		<div id="users">
			<table>
				<thead>
					<tr>
						<th width="16"></th>
						<th width="16"></th>
						<th width="16"></th>
						<th>Ime</th>
						<th>Prezime</th>
						<th>Rola</th>
						<th>E mail</th>
						<th>Država</th>
						<th>Mjesto</th>
						<th>Adresa</th>
						<th width="16"></th>
					</tr>
				</thead>
				<tbody>';
				$query  = "SELECT * FROM users";
				$result = @mysqli_query($MySQL, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '
					<tr>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;id=' .$row['id']. '"><img src="images/user.png" alt="user"></a></td>
						<td>';
							if ($_SESSION['user']['role'] == 'admin') {
								print '<a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;edit=' .$row['id']. '"><img src="images/edit.png" alt="uredi"></a></td>';
							}
						print '
						<td>';
							if ($_SESSION['user']['role'] == 'admin') {
								print '<a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;delete=' .$row['id']. '"><img src="images/delete.png" alt="obriši"></a>';
							}
						print '	
						</td>
						<td><strong>' . $row['firstname'] . '</strong></td>
						<td><strong>' . $row['lastname'] . '</strong></td>
						<td>' . $row['role'] . '</td>
						<td>' . $row['email'] . '</td>
						<td>';
							$_query  = "SELECT * FROM countries";
							$_query .= " WHERE id='" . $row['country_id'] . "'";
							$_result = @mysqli_query($MySQL, $_query);
							$_row = @mysqli_fetch_array($_result, MYSQLI_ASSOC);
							print $_row['country_name'] . '
						</td>
						<td>' . $row['city'] . '</td>
						<td>' . $row['street'] . '</td>
						<td>';
						if ($row['archive'] != 'N') { print '<img src="images/inactive.png" alt="" title="" />'; }
						else { print '<img src="images/active.png" alt="" title="" />'; }
						print '
						</td>
					</tr>';
				}
			print '
				</tbody>
			</table>
		</div><p><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;create=true">Dodaj korisnika</a></p>';
	}
	
	@mysqli_close($MySQL);
?>