<?php 
	
	if (isset($_POST['_action_']) && $_POST['_action_'] == 'add_news') {
		$_SESSION['message'] = '';

		$query  = "INSERT INTO news (title, description, picture_id, archive)";
		$query .= " VALUES ('" . htmlspecialchars($_POST['title'], ENT_QUOTES) . "', '" . htmlspecialchars($_POST['description'], ENT_QUOTES) . "', 1, 2)";
		$result = @mysqli_query($MySQL, $query);
		
		$news_ID = mysqli_insert_id($MySQL);
		
        if($_FILES['picture']['error'] == UPLOAD_ERR_OK && $_FILES['picture']['name'] != "") {
                
			$ext = strtolower(strrchr($_FILES['picture']['name'], "."));

			$picture_name = 'news-' . $news_ID . $ext;
            $_picture = 'news/' . $picture_name;
			copy($_FILES['picture']['tmp_name'], $_picture);
			
			if ($ext == '.jpeg' || $ext == '.jpg' || $ext == '.png' || $ext == '.gif') {
				@mysqli_query($MySQL,"INSERT INTO pictures(path) VALUES ('" . $picture_name . "')");
				$picture_ID = mysqli_insert_id($MySQL);
				$_query  = "UPDATE news SET picture_id=" . $picture_ID . "";
				$_query .= " WHERE id=" . $news_ID . " LIMIT 1";
				$_result = @mysqli_query($MySQL, $_query);
				$_SESSION['message'] .= '<p>Uspješno ste dodali sliku.</p>';
			}
        }
		
		$_SESSION['message'] .= '<p>Uspješno ste dodali vijesti!</p>';		
		header("Location: index.php?menu=9&action=1");
	}
	
	if (isset($_POST['_action_']) && $_POST['_action_'] == 'edit_news') {
		$query  = "UPDATE news SET title='" . htmlspecialchars($_POST['title'], ENT_QUOTES) . "', description='" . htmlspecialchars($_POST['description'], ENT_QUOTES) . "'";
        $query .= " WHERE id=" . (int)$_POST['edit'];
        $query .= " LIMIT 1";
		@mysqli_query($MySQL, $query);
        $news_ID = mysqli_insert_id($MySQL);

        if($_FILES['picture']['error'] == UPLOAD_ERR_OK && $_FILES['picture']['name'] != "") {
                
			$ext = strtolower(strrchr($_FILES['picture']['name'], "."));

			$picture_name = 'news-' . $news_ID . $ext;
            $_picture = 'news/' . $picture_name;
			@unlink($_picture);
			@mysqli_query($MySQL, "DELETE FROM pictures WHERE path='" . $picture_name . "'");
			copy($_FILES['picture']['tmp_name'], $_picture);
			
			if ($ext == '.jpeg' || $ext == '.jpg' || $ext == '.png' || $ext == '.gif') {
				@mysqli_query($MySQL,"INSERT INTO pictures(id, path) VALUES (NULL, '" . $picture_name . "')");
				$picture_ID = mysqli_insert_id($MySQL);
				$_query  = "UPDATE news SET picture_id='" . $picture_ID . "'";
				$_query .= " WHERE id=" . $news_ID . " LIMIT 1";
				$_result = @mysqli_query($MySQL, $_query);
				$_SESSION['message'] .= '<p>Uspješno ste dodali sliku.</p>';
			}
        }
		
		$_SESSION['message'] = '<p>Uspješno ste izmjenili vijesti!</p>';		
		header("Location: index.php?menu=9&action=1");
	}
	
	if (isset($_GET['id']) && $_GET['id'] != '') {
		$query  = "SELECT * FROM news LEFT JOIN pictures ON news.picture_id = pictures.id";
		$query .= " WHERE news.id=".$_GET['id'];
		$query .= " ORDER BY date DESC";
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
		print '
		<h2>Pregled vijesti</h2>
		<div class="news">
			<img src="news/' . $row['path'] . '" alt="' . $row['title'] . '" title="' . $row['title'] . '">
			<h2>' . $row['title'] . '</h2>
			' . $row['description'] . '
			<time datetime="' . $row['date'] . '">' . pickerDateToMysql($row['date']) . '</time>
			<hr>
			</div><p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Nazad</a></p>';
		}
	
	#Add news 
	else if (isset($_GET['add']) && $_GET['add'] != '') {
		
		print '
		<h2>Dodaj vijest</h2>
		<form action="" id="news_form" name="news_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="_action_" name="_action_" value="add_news">
			
			<label for="title">Naslov*:</label>
			<input type="text" id="title" name="title" placeholder="Naslov vijesti.." required>
			<label for="description">Opis*:</label>
			<textarea id="description" name="description" placeholder="Opis vijesti.." required></textarea>
				
			<label for="picture">Slika:</label>
			<input type="file" id="picture" name="picture" required>
			<hr>
			
			<input type="submit" value="Spremi">
		</form>';
	}
	#Edit news
	else if (isset($_GET['edit']) && $_GET['edit'] != '') {
		$query  = "SELECT * FROM news LEFT JOIN pictures ON news.picture_id = pictures.id";
		$query .= " WHERE news.id=".$_GET['edit'];
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
		$checked_archive = false;

		print '
		<h2>Uredi vijest</h2>
		<form action="" id="news_form_edit" name="news_form_edit" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="_action_" name="_action_" value="edit_news">
			<input type="hidden" id="edit" name="edit" value="' . $_GET['edit'] . '">
			
			<label for="title">Naslov*:</label>
			<input type="text" id="title" name="title" value="' . $row['title'] . '" placeholder="Naslov vijesti.." required>
			<label for="description">Opis*:</label>
			<textarea id="description" name="description" placeholder="Opis vijesti.." required>' . $row['description'] . '</textarea>
				
			<label for="picture">Slika: (' . $row['path'] . ')</label>
			<input type="file" id="picture" name="picture">
			<hr>
			
			<input type="submit" value="Spremi">
		</form>';
	}
	else {
		print '
		<h2>Vijesti</h2>
		<div id="news">
			<table>
				<thead>
					<tr>
						<th width="16"></th>
						<th width="16"></th>
						<th>Naslov</th>
						<th>Opis</th>
						<th>Datum</th>
						<th width="16"></th>
					</tr>
				</thead>
				<tbody>';
				$query  = "SELECT * FROM news";
				$query .= " ORDER BY date DESC";
				$result = @mysqli_query($MySQL, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '
					<tr>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;id=' .$row['id']. '"><img src="images/user.png" alt="user"></a></td>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;edit=' .$row['id']. '"><img src="images/edit.png" alt="uredi"></a></td>
						<td>' . $row['title'] . '</td>
						<td>';
						if(strlen($row['description']) > 160) {
                            echo substr(strip_tags($row['description']), 0, 160).'...';
                        } else {
                            echo strip_tags($row['description']);
                        }
						print '
						</td>
						<td>' . pickerDateToMysql($row['date']) . '</td>
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
			<a href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;add=true" class="AddLink">Dodaj vijest</a>
		</div>';
	}
	
	@mysqli_close($MySQL);
?>