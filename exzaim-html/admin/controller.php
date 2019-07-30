<?php
	include("./dao.php");
	include("./config.php");
	
	ini_set('post_max_size', FILE_UPLOAD_MAX_SIZE);
	ini_set('upload_max_filesize', FILE_UPLOAD_MAX_SIZE);
	
	header('Content-Type: text/html; charset=utf-8');

	if (isset($_POST['cancel'])) {
		redirect("/admin");
	}

	if (isset($_POST['vis'])) {
		actionChangeVisibility();
	}
	
	if (isset($_POST['edit_form'])) {
		actionShowEditForm();
	}
	
	if (isset($_POST['create'])) {
		actionCreate();
	}

	if (isset($_POST['edit'])) {
		actionEdit();
	}

	if (isset($_POST['delete'])) {
    	actionDelete();
    }
	
	//default: redirect to admin page
	redirect("/admin");
	
	function actionChangeVisibility() {
		$id = $_POST['id'];
		$isVisible = $_POST['visible'];
		$position = $_POST['position'];

		if ($isVisible) {
		    dbSetInvisible($id);
		    dbUpFiles($position);
		} else {
    		dbSetVisible($id);
		}
	}
	
	function actionShowEditForm() {
		$url = "/admin/form_edit.php";
		if (isset($_POST['id'])) {
			$url .= "?id=".$_POST['id'];
		}
		redirect($url);
	}
	
	function actionCreate() {
		$file_upload_result = uploadFileIfNeeded();
		if ($file_upload_result['success']) {
			dbCreateFile($_POST['title'], $file_upload_result['file'], 'doc', $_POST['date'], 'admin');
		} else {
			die($file_upload_result['error_text']);
		}
	}

	function actionEdit() {
		$file_upload_result = uploadFileIfNeeded();
		if ($file_upload_result['success']) {
			print_r($file_upload_result);
			dbUpdateFile($_POST['id'], $_POST['title'], $file_upload_result['file'], 'doc', $_POST['date'], 'admin');
		} else {
			die($file_upload_result['error_text']);
		}
	}

    function actionDelete() {
        $id = $_POST['id'];
        $file = dbFetchFile($id);
        $isVisible = $file["visible"] == 1;
        $pos = $file["position"];
        $filePath = ".." . FILE_UPLOAD_DIR . $file["filename"];

        unlink($filePath);
        clearstatcache();

        dbDeleteFile($id);

        if ($isVisible) {
            dbUpFiles($position);
        }
    }

	function uploadFileIfNeeded() {
		if (!is_uploaded_file($_FILES["file"]["tmp_name"])) {
			return array('error_text' => '', 'file_name' => '', 'success' => true, 'file_uploaded' => false);
		}

		$target_dir = "..".FILE_UPLOAD_DIR;

		$result = array('error_text' => '',
						'file_name' => '',
						'success' => false,
						'file_uploaded' => false);

		if ($_FILES["file"]["error"] != 0) {
			$result['error_text'] = "Файл не может быть загружен";
			return $result;
		}

		$target_file = basename($_FILES["file"]["name"]);
		if (file_exists($target_file)) {
			$prefix = currentTimeInMillis();
			$target_file = $prefix . "-" . basename($_FILES["file"]["name"]);
		}

		if ($_FILES["file"]["size"] > 5000000) {
			$result['error_text'] = "Размер файла превышает 5 Мб";
		}

		if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir . $target_file)) {
			$result['success'] = true;
			$result['file'] = $target_file;
			$result['file_uploaded'] = true;
		} else {
			$result['error_text'] = "Файл не может быть загружен";
		}

		return $result;
	}
	
	function currentTimeInMillis() {
		list($usec, $sec) = explode(" ", microtime());
		return $sec;
	}
	
	function redirect($url) {
		header("Location: $url");
		die(); 
	}
?>