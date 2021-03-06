<?php
class Model_Settings extends Engine_Model {
	function getMailbox() {
		$data = array();
		
	    $sql = "SELECT `email`, `server`, `port`, `auth`, `login`, `password`, `ssl`
        FROM otms_mail
        LIMIT 1";
	    
        $res = $this->registry['db']->prepare($sql);
		$res->execute();
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($row) > 0) {
			$data = $row[0];
			if ($row[0]["auth"] == "0") {
				$data["login"] = "";
				$data["password"] = "";
				$data["auth"] = 0;
			} else {
				$data["login"] = $row[0]["login"];
				$data["password"] = $row[0]["password"];
				$data["auth"] = 1;
			}
		}

        return $data;
	}
	
	function editMailbox($post) {
		$sql = "SELECT `email` FROM otms_mail LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$res->execute();
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($row) == 1) {
			if ( (!isset($post["login"])) or ($post["login"] == "") ) { $post["login"] = ""; };
			if ( (!isset($post["password"])) or ($post["password"] == "") ) { $post["password"] = ""; };
		
			$sql = "UPDATE otms_mail SET `email` = :email, `server` = :server, `protocol` = :protocol, `port` = :port, `auth` = :auth, `login` = :login, `password` = :password, `ssl` = :ssl";
			
	        $res = $this->registry['db']->prepare($sql);
			$param = array(":email" => $post["email"], ":server" => $post["server"], ":protocol" => "SMTP", ":port" => $post["port"], ":auth" => $post["auth"], ":login" => $post["login"], ":password" => $post["password"], ":ssl" => $post["ssl"]);
			$res->execute($param);
		} else {
			$sql = "INSERT INTO otms_mail (`email`, `server`,`protocol`, `port`, `auth`, `login`, `password`, `ssl`) VALUES (:email, :server, :protocol, :port, :auth, :login, :password, :ssl)";
			
	        $res = $this->registry['db']->prepare($sql);
			$param = array(":email" => $post["email"], ":server" => $post["server"], ":protocol" => "SMTP", ":port" => $post["port"], ":auth" => $post["auth"], ":login" => $post["login"], ":password" => $post["password"], ":ssl" => $post["ssl"]);
			$res->execute($param);
		}
	}
}
?>