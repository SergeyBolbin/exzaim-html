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
		$stmt = $pdo -> prepare('SELECT * FROM ez_document WHERE parent_id IS NULL AND visible = :visibility  ORDER BY position, title');
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

	function dbCreateFile($title, $filename, $type, $doc_date, $created_by, $parent_id = null) {
		$pdo = mysqlConnect();
		$position = _dbGetMaxPos() + 1;

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

		$sql = "DELETE FROM ez_document WHERE id = :id OR parent_id = :pid";
		$stm = $pdo -> prepare($sql);
		$stm -> bindParam(':id', $id, PDO::PARAM_INT);
		$stm -> bindParam(':pid', $id, PDO::PARAM_INT);

		$stm -> execute();
		$pdo = null;
	}

	function dbSetVisible($id) {
	    $pos = _dbGetMaxPos() + 1;
	    _dbChangeVisibility($id, 1, $pos);
	}

	function dbSetInvisible($id) {
    	_dbChangeVisibility($id, 0, 0);
    }

    function dbUpFiles($pos) {
        $pdo = mysqlConnect();
        $sql = "UPDATE ez_document SET position = position - 1 WHERE position > :pos";
        $stm = $pdo -> prepare($sql);
        $stm -> bindParam(':pos', $pos, PDO::PARAM_INT);
        $stm -> execute();
        $pdo = null;
    }

	function _dbGetMaxPos() {
	    $pdo = mysqlConnect();
        $sql = "SELECT MAX(position) AS `pos` FROM ez_document";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute();
	    $res = $stmt -> fetch();
        $pdo = null;

	    return $res["pos"];
	}

	function _dbChangeVisibility($id, $visibility, $pos) {
		$pdo = mysqlConnect();
		$sql = "UPDATE ez_document SET visible = :visibility, position = :pos WHERE id = :id";
		$stm = $pdo -> prepare($sql);
		$stm -> bindParam(':visibility', $visibility, PDO::PARAM_INT);
		$stm -> bindParam(':id', $id, PDO::PARAM_INT);
		$stm -> bindParam(':pos', $pos, PDO::PARAM_INT);
		$stm -> execute();
		$pdo = null;
	}
?>