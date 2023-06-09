<?php
	if (isset($action) && $action != '') {
		$query  = "SELECT * FROM news LEFT JOIN pictures ON news.picture_id = pictures.id";
		$query .= " WHERE news.id=" . $_GET['action'];
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
			print '
			<div class="news">
				<img src="news/' . $row['path'] . '" alt="' . $row['title'] . '" title="' . $row['title'] . '">
				<h2>' . $row['title'] . '</h2>
				<p>'  . $row['description'] . '</p>
				<time datetime="' . $row['date'] . '"><i>' . pickerDateToMysql($row['date']) . '</i></time>
				<hr>
			</div>';
	}
	else {
		print '<h1>Vijesti</h1>';
		$query  = "SELECT news.id AS news_id, news.title, news.description, news.date, pictures.path FROM news LEFT JOIN pictures ON news.picture_id = pictures.id";
		$query .= " WHERE archive='N'";
		$query .= " ORDER BY date DESC";
		$result = @mysqli_query($MySQL, $query);
		while($row = @mysqli_fetch_array($result)) {
			print '
			<div class="news">
				<img src="news/' . $row['path'] . '" alt="' . $row['title'] . '" title="' . $row['title'] . '">
				<h2>' . $row['title'] . '</h2>';
				if(strlen($row['description']) > 300) {
					echo substr(strip_tags($row['description']), 0, 300).'... <a href="index.php?menu=' . $menu . '&amp;action=' . $row['news_id'] . '">Više...</a>';
				} else {
					echo strip_tags($row['description']).'... <a href="index.php?menu=' . $menu . '&amp;action=' . $row['news_id'] . '">Više...</a>';
				}
				print '
				<time datetime="' . $row['date'] . '"><i>' . pickerDateToMysql($row['date']) . '</i></time>
				<hr>
			</div>';
		}
	}

	@mysqli_close($MySQL);

?>