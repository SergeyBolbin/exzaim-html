<?php
	include("./dao.php");
	$title = $file_name = "";
	$date = date("d.m.Y");
	$id = $_GET['id'];
	$title_text = "Создать документ";
	$action = "create";

	if (isset($id) && $id != "") {
		$file = dbFetchFile($id);
		$title = $file['title'];
		$file_name = $file['filename'];
		$date = $file['doc_date'];
		$title_text = "Изменить документ";
		$action = "edit";
	}
?>
<html>
<head>
	<title>ООО "ЭкспрессЗайм", р.п. Вознесенское. Информация</title>
	<link rel="stylesheet" href="css/main.css" type="text/css">
	<link rel="stylesheet" href="css/clock.css" type="text/css">
	<script type="text/javascript" src="javascript/jquery-1.2.6.min.js"></script>
	<script type="text/javascript" src="javascript/main.js"></script>
	<script type="text/javascript" src="javascript/clock.js"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta name="description" content="Экспресс займ. Быстрые займы до зарплаты в Вознесенском" />
	<meta name="keywords" content="займ, быстрый, деньги, до зарплаты, денежный, срочно, взять, получить, банковский, моментально, займ, интернет, экспресс, вознесенское, экспрессзайм" />
</head>

<form method="POST" action="./controller.php" enctype="multipart/form-data">
	<?php
	echo "<h3>$title_text</h3>";
	echo "<table>
			<tr>
				<td>Название</td>
				<td><input type=\"text\" name=\"title\" value=\"$title\"></td>
			</tr>
			<tr>
				<td>Файл</td>
				<td>
					<input type=\"file\" name=\"file\">
					(Сохраненный файл: <a href=\"".FILE_UPLOAD_DIR.$file_name."\" target='_blank'>".$file_name."</a>)
				</td>
			</tr>
			<tr>
				<td>Отображаемая дата</td>
				<td><input type=\"text\" name=\"date\" value=\"$date\"></td>
			</tr>
			<tr>
				<td colspan=\"2\">
					<input type=\"submit\" name=\"cancel\" value=\"Cancel\"/>
					<input type=\"submit\" value=\"OK\"/>
					<input type=\"hidden\" name=\"id\" value=\"$id\">
					<input type=\"hidden\" name=\"$action\" value=\"1\">
				</td>
			</tr>
		</table>";
	?>
</form>
<body>
</body>