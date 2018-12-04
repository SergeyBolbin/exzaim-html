<?php
	include("./config.php");
	
	function mysqlConnect() {
		$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
		$opt = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
		];
		try {
			$pdo = new PDO($dsn, DB_USER, DB_PASS, $opt);
		} catch (PDOException $e) {
			die($e -> getMessage());
		}
		
		return $pdo;
	}
	
	function dbFetchPrimaryFiles($visibility) {
		$pdo = mysqlConnect();
		$stmt = $pdo -> prepare('SELECT * FROM ez_document WHERE parent_id IS NULL AND visible = :visibility  ORDER BY position');
		$stmt -> bindParam(':visibility', $visibility, PDO::PARAM_INT);
		$stmt -> execute();
		$res = $stmt -> fetchAll();
		$pdo = null;
		return $res;
	}
	
	function dbFetchAllAttachmentFiles() {
		$pdo = mysqlConnect();
		$stmt = $pdo->prepare('SELECT * FROM ez_document WHERE parent_id IS NOT NULL ORDER BY position');
		$res = $stmt->fetchAll();
		$pdo = null;
		return $res;
	}
	
	function dbFetchFile($id) {
		$pdo = mysqlConnect();
		$stmt = $pdo -> prepare('SELECT * FROM ez_document WHERE id = :id');
		$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
		$stmt -> execute();
		return $stmt -> fetch();
	}
	
	function dbCountFiles() {
		$pdo = mysqlConnect();
		$stmt = $pdo -> prepare('SELECT COUNT(*) as `count` FROM ez_document');
		$stmt -> execute();
		$res = $stmt -> fetch();
		$pdo = null;
		return $res['count'];
	}
	
	
	function dfCreateFile($title, $filename, $type, $doc_date, $created_by, $parent_id = null) {
		$pdo = mysqlConnect();
		$position = dbCountFiles() + 1;
		
		$sql = "INSERT INTO ez_document (`title`, `filename`, `type`, `doc_date`, `created_by`, `parent_id`, `position`) 
					VALUES (:title, :filename, :type, :doc_date, :created_by, :parent_id, :position) ";
		
		$stm = $pdo -> prepare($sql);
		$stm -> bindParam(':title', $title, PDO::PARAM_STR);
		$stm -> bindParam(':filename', $filename, PDO::PARAM_STR);
		$stm -> bindParam(':type', $type, PDO::PARAM_STR);
		$stm -> bindParam(':doc_date', $doc_date, PDO::PARAM_STR);
		$stm -> bindParam(':created_by', $created_by, PDO::PARAM_STR);
		$stm -> bindParam(':parent_id', $parent_id, PDO::PARAM_INT);
		$stm -> bindParam(':position', $position, PDO::PARAM_INT);
		$stm -> execute();
		$pdo = null;
	}
	
	function dbUpdateFile($id, $title, $filename, $type, $doc_date, $created_by, $parent_id = null) {
		$pdo = mysqlConnect();
		$values = array($title, $filename, $type, $doc_date, $created_by, $parent_id, $position);
		
		$sql_file_name = $filename != "" ? "filename=:filename," : "";
		
		$sql = "UPDATE ez_document 
				   SET 
						title=:title, 
						$sql_file_name 
						type=:type, 
						doc_date=:doc_date, 
						created_by=:created_by, 
						parent_id=:parent_id
				WHERE id = :id";
		
		$stm = $pdo->prepare($sql);
		$stm -> bindParam(':title', $title, PDO::PARAM_STR);
		if ($filename != "") $stm -> bindParam(':filename', $filename, PDO::PARAM_STR);
		$stm -> bindParam(':type', $type, PDO::PARAM_STR);
		$stm -> bindParam(':doc_date', $doc_date, PDO::PARAM_STR);
		$stm -> bindParam(':created_by', $created_by, PDO::PARAM_STR);
		$stm -> bindParam(':parent_id', $parent_id, PDO::PARAM_INT);
		$stm -> bindParam(':id', $id, PDO::PARAM_INT); 
		
		$stm->execute();
		$pdo = null;
	}
	
	
	function dbDeleteFile($id) {
		$pdo = mysqlConnect();
		$values = array($title, $filename, $type, $doc_date, $created_by, $parent_id, $position);
		
		$sql = "DELETE ez_document WHERE id = :id OR parent_id = :id";
		$stm = $pdo -> prepare($sql);
		$stm -> bindParam(':id', $id, PDO::PARAM_INT);
		$stm -> execute();
		$pdo = null;
	}
	
	function dbChangeVisibility($id, $visibility) {
		$pdo = mysqlConnect();
		$sql = "UPDATE ez_document SET visible = :visibility WHERE id = :id";
		$stm = $pdo -> prepare($sql);
		$stm -> bindParam(':visibility', $visibility, PDO::PARAM_INT); 
		$stm -> bindParam(':id', $id, PDO::PARAM_INT); 
		$stm -> execute();
		$pdo = null;
	}
?>