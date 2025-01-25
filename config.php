<?php
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "@L03e1t3");
define("DB_NAME", "setup");

class db_connect
{
	public $host = DB_HOST;
	public $user = DB_USER;
	public $pass = DB_PASS;
	public $name = DB_NAME;
	public $conn;
	public $error;

	public function connect()
	{
		$this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);

		if ($this->conn->connect_error) {
			$this->error = "Fatal Error: Can't connect to database. " . $this->conn->connect_error;
			return false;
		}
		return true;
	}
}
