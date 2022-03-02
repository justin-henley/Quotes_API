<?php
class Category
{
    // DB properties
    private $conn;
    private $table = 'categories';

    // Properties
    public $id;
    public $category;

    // Constructor 
    // param: {PDO} dbConn - An active database connection
    public function __construct($dbConn)
    {
        $this->conn = $dbConn;
    }

    // Read all categories
    public function read()
    {
        // Create query
        $query =
            "SELECT
                id,
                category
            FROM
                {$this->table}";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Execute the statement
        $stmt->execute();

        return $stmt;
    }

    // Read a single category by id
    public function readSingle()
    {
        // TODO
    }

    // Create a new category
    public function create()
    {
        // TODO
    }

    // Update an existing category
    public function update()
    {
        // TODO
    }

    // Delete Category
    public function delete()
    {
        // TODO
    }
}
