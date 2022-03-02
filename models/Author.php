<?php
class Category
{
    // DB properties
    private $conn;
    private $table = 'authors';

    // Properties
    public $id;
    public $author;

    // Constructor 
    // param: {PDO} dbConn - An active database connection
    public function __construct($dbConn)
    {
        $this->conn = $dbConn;
    }

    // Read all authors
    public function read()
    {
        // Create query
        $query =
            "SELECT
                id,
                author
            FROM
                {$this->table}";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Execute the statement
        $stmt->execute();

        return $stmt;
    }
}
