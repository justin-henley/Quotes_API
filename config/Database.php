<?php
class Database
{
    // DB Params
    private $conn;

    // DB Connect
    public function connect()
    {
        $this->conn = null;

        $url = getenv('JAWSDB_URL');
        $dbParts = parse_url($url);

        $host = $dbParts['host'];
        $username = $dbParts['user'];
        $password = $dbParts['pass'];
        $dbName = ltrim($dbParts['path'], '/');

        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
            exit("Unable to commit to the database");
        }

        return $this->conn;
    }
}
