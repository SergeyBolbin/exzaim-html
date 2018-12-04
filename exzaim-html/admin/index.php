<?php
	include("./dao.php");
	include("./config.php");
?>
<html>
<head>
	<title>ООО МКК "ЭкспрессЗайм", р.п. Вознесенское. Информация</title>
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
<body>
	<h2>Видимые документы</h2>
	<?php 
		renderFilesTable(dbFetchPrimaryFiles(1)); 
	?>
	<h2>Архив (скрытые документы)</h2>
	<?php 
		renderFilesTable(dbFetchPrimaryFiles(0)); 
	?>
	
	<form method="POST" action="controller.php">
		<input type="submit" name="edit_form" value="Добавить документ">
	</form>
</body>
<?php 
	function renderFilesTable($primaryFiles) {
		echo "<table border=1>
				<tr>
					<td>&nbsp;</td>
					<td>Документ</td>
					<td>Ссылка</td>
					<td>Дата</td>
					<td>Дата создания</td>
					<td>Автор</td>
					<td>Действия</td>
				</tr>";
		
		$last_index_of_primary = count($primaryFiles) - 1;
		foreach ($primaryFiles as  $idx => $primary) {
			$visible = $primary['visible'] > 0;
			$visible_action_text = $visible ? 'Скрыть на сайте' : 'Отобразить на сайте';
			$btn_up_text = ($idx != 0) ? "<input name=\"up\" type=\"submit\" value=\"Выше\">&nbsp;" : "";
			$btn_down_text = ($idx != $last_index_of_primary) ? "<input name=\"down\" type=\"submit\" value=\"Ниже\">&nbsp;" : "";
			
			echo "<tr>
					<td><input type=\"checkbox\"></td>
					<td>".$primary['title']."</td>
					<td><a href=\"".FILE_UPLOAD_DIR.$primary['filename']."\" target='_blank'>".$primary['filename']."</a></td>
					<td>".$primary['doc_date']."</td>
					<td>".$primary['creation_date']."</td>
					<td>".$primary['created_by']."</td>
					<td>
						<form method='POST' action='./controller.php'>
							<input name=\"edit_form\" type=\"submit\" value=\"Изменить\">&nbsp;
							<input name=\"vis\" type=\"submit\" value=\"$visible_action_text\">&nbsp;
							$btn_up_text 
							$btn_down_text
							<input type=\"hidden\" name=\"id\" value=".$primary['id'].">
							<input type=\"hidden\" name=\"visible\" value=".$primary['visible'].">
						</form>
					</td>
				</tr>";	
		}
		
		echo "</table>";
	}
?>