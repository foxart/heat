<?php

/*
  version 1.4
  06.03.2011, 07.08.2011, 15.12.2011, 25.12.2011, 06.01.2012
  version 2.0
  18.02.2014
 */
/*
  ['hostname'] The hostname of your database server.
  ['username'] The username used to connect to the database
  ['password'] The password used to connect to the database
  ['database'] The name of the database you want to connect to
  ['dbdriver'] The database type. ie: mysql.  Currently supported: mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
  ['dbprefix'] You can add an optional prefix, which will be added to the table name when using the  Active Record class
  ['pconnect'] TRUE/FALSE - Whether to use a persistent connection
  ['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
  ['cache_on'] TRUE/FALSE - Enables/disables query caching
  ['cachedir'] The path to the folder where cache files should be stored
  ['char_set'] The character set used in communicating with the database
  ['dbcollat'] The character collation used in communicating with the database
  The $active_group variable lets you choose which connection group to make active.  By default there is only one group (the "default" group).
  The $active_record variables lets you determine whether or not to load the active record class
 */

class faSql {

	private $query_separator = ';';

	public function __construct() {
		// parent::__construct();
		$this->hostname = 'host';
		$this->username = 'user';
		$this->password = 'password';
		$this->database = 'database';
		$this->char_set = "utf8";
		$this->dbcollat = "utf8_bin";
	}

	/* DATABASE */

	public function set_connection($hostname, $username, $password, $database) {
		$this->hostname = $hostname;
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;
	}

	public function connect() {
		global $db_time;
		$timeStart = microtime(true);

		$result = false;
		$this->connection = @mysql_connect($this->hostname, $this->username, $this->password);
		if ($this->connection == true) {
			$this->db = @mysql_select_db($this->database, $this->connection);
			if ($this->db) {
				$result = true;
				mysql_query('set character_set_client = "' . $this->char_set . '"');
				mysql_query('set character_set_results = "' . $this->char_set . '"');
				mysql_query("set collation_connection = '" . $this->dbcollat . "'");
			} else {
				raise_error('database error: <q>' . mysql_error() . '</q>', 2);
			}
		} else {
			raise_error('connection error: <q>' . mysql_error() . '</q>', 2);
		}

		$timeEnd = microtime(true);
		$db_time = $db_time + ($timeEnd - $timeStart);

		return $result;
	}

	/* SQL */

	private function __check_variables($variables = array()) {
		foreach ($variables as $key => $value) {
			if (strpos($this->template, $key) == false) {
				raise_error("pattern not found: <q>$key</q>", 3);
			};
		}
	}

	private function __set_variables($variables = array()) {
		$result = array();
		foreach ($variables as $key => $value) {
			$newKey = '|' . strtoupper($key) . '|';
			if (get_magic_quotes_gpc() == true) {
				$newValue = $value;
			} else {
				$newValue = $value;
				// $newValue = mysql_escape_string($value);
				// $newValue = mysql_real_escape_string($newValue);
			};
			$result[$newKey] = $newValue;
		};
		return $result;
	}

	public function set($variables) {
		$variables = $this->__set_variables($variables);
		$this->__check_variables($variables);
		foreach ($this->queries as $key => $value) {
			$this->queries[$key] = str_replace(array_keys($variables), array_values($variables), $value);
		};
		$this->query = implode(';', $this->queries);
	}

	public function reset() {
		$this->sql_query = $this->sql_template;
	}

	public function load($query) {
		$this->template = $query;
		$this->queries = explode($this->query_separator, $this->template);
	}

	public function fetch_object() {
		// if (is_resource($this->result) == true and $this->num_rows > 0)
		if (is_resource($this->result) == true) {
			return mysql_fetch_object($this->result);
		} else {
			raise_error('undefined query result: <q>' . mysql_error() . '</q>', 2);
		};
	}

	public function fetch_array() {
		if (is_resource($this->result) == true) {
			return mysql_fetch_array($this->result);
		} else {
			raise_error('undefined query result: <q>' . mysql_error() . '</q>', 2);
		}
	}

	public function fetch_assoc() {
		if (is_resource($this->result) == true) {
			return mysql_fetch_assoc($this->result);
		} else {
			raise_error('undefined query result: <q>' . mysql_error() . '</q>', 2);
		}
	}

	public function to_assoc() {
		$result = false;
		if (is_resource($this->result) == true) {
			while ($assoc = mysql_fetch_assoc($this->result)) {
				$result[] = $assoc;
			};
			return $result;
		} else {
			raise_error('undefined query result: <q>' . mysql_error() . '</q>', 2);
		}
	}

	public function fetch_row() {
		if (is_resource($this->result) == true) {
			return mysql_fetch_row($this->result);
		} else {
			raise_error('undefined query result: <q>' . mysql_error() . '</q>', 2);
		}
	}

	public function seek($row_number = 0) {
		if (is_resource($this->result) and $this->num_rows > 0) {
			mysql_data_seek($this->result, $row_number);
		};
	}

	public function run($param = false) {
		global $db_time;
		$timeStart = microtime(true);

		foreach ($this->queries as $key => $value) {
			if (empty($value) == false) {
				$this->result = mysql_query($value);
				if (is_resource($this->result) == true) {
					$this->num_rows = mysql_num_rows($this->result); //if DELETE INSERT UPDATE no rows returned
				}
				if (mysql_error()) {
					raise_error('mysql error: <q>' . mysql_error() . '</q>', 2);
				}
			}
		}

		$timeEnd = microtime(true);
		$db_time = $db_time + ($timeEnd - $timeStart);
	}

	public function free() {
		mysql_free_result($this->result);
	}

}
