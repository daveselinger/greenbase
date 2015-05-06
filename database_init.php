<?php
namespace greenbase;

//Read the default configuration first
include 'get_config.php';

class Database {
	public static $default_config;

	public static function getDefaultDBConnection() {
		if (is_null(Database::$default_config)) {
			Database::$default_config = [];
			Database::$default_config["url"] = Config::$db_url;
			Database::$default_config["username"] = Config::$db_username;
			Database::$default_config["db"] = Config::$db_name;
			Database::$default_config["password"] = Config::$db_password;
		}
		return Database::getDBConnection(Database::$default_config);
	}

	public static function getDBConnection($db_config)
	{
		$db_url = $db_config["url"];
		$db_username = $db_config["username"];
		$db_name = $db_config["db"];
		$db_password = $db_config["password"];

		if (!isset($db_url) || !isset($db_username) || !isset($db_password) || !isset($db_name) || ($db_url == '')
			|| ($db_username == '') || ($db_password == '') || ($db_name == '')
		) {
			exit('No database configuration in "config.php".' . $db_url . ';' . $db_username . ';' . $db_password . ';' . $db_name);
		}

		// exit ("Url: " . $db_url . "; username:" . $db_username . "; password:". $db_password);
		$con = new \mysqli($db_url, $db_username, $db_password);
		if ($con->connect_error) {
			exit ('Connect error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
		} else {
		}
		$con->select_db($db_name);
		return $con;
	}
}

/*
//Basic test
$con = getDBConnection($db_config);
$results = $con->query("Select count(1) as COUNT from orgs");
if ($row = $results->fetch_assoc()) {
  echo $row["COUNT"] . "rows";
}
$results->close();
$con->close();
*/
?>