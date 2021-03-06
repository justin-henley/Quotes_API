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
        // Create query
        $query =
            "SELECT
                id,
                category
            FROM
                {$this->table}
            WHERE
                id = :id
            LIMIT 0,1";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind ID
        $stmt->bindValue(':id', $this->id);

        // Execute the statement
        $stmt->execute();

        // Fetch the single row of data from the db
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Store the fetched data in this object instance
        $this->category = $row['category'];
    }

    // Create a new category
    public function create()
    {
        // Create query
        $query =
            "INSERT INTO {$this->table}
            SET
                category = :category";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->category = htmlspecialchars(strip_tags($this->category), ENT_NOQUOTES);

        // Bind category
        $stmt->bindValue(':category', $this->category);

        // Execute the statement and check row count to see if any rows affected
        // If the statement executes and affected a row, return true for success
        return ($stmt->execute() && $stmt->rowCount());
    }

    // Update an existing category
    public function update()
    {
        // Create query
        $query =
            "UPDATE {$this->table}
            SET
                category = :category
            WHERE
                id = :id";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->category = htmlspecialchars(strip_tags($this->category), ENT_NOQUOTES);

        // Bind category
        $stmt->bindValue(':category', $this->category);
        $stmt->bindValue(':id', $this->id);

        // Execute the statement and check row count to see if any rows affected
        // If the statement executes and affected a row, return true for success
        return ($stmt->execute() && $stmt->rowCount());
    }

    // Delete Category
    public function delete()
    {
        // Create query
        $query = "DELETE FROM {$this->table} WHERE id = :id";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind id
        $stmt->bindValue(':id', $this->id);

        // Execute the statement and check row count to see if any rows affected
        // If the statement executes and affected a row, return true for success
        $result = null;
        try {
            $result = ($stmt->execute() && $stmt->rowCount());
        } catch (Exception $e) {
            // Null signifies failure of the delete operation
            $result = null;
        }

        return $result;
    }
}
