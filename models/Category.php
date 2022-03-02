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

        // Bind ID
        $stmt->bindValue(':id', $this->id);

        // Execute the statement
        $stmt->execute();

        // Fetch the single row of data from the db
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Store the fetched data in this object instance
        $this->category = $row['category'];

        // TODO how to handle a non-existing id
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
        $this->category = htmlspecialchars(strip_tags($this->category));

        // Bind category
        $stmt->bindValue(':category', $this->category);

        // Execute the statement
        // TODO is this the correct error behavior for this project?
        if ($stmt->execute()) {
            return true;
        } else {
            // Print an error if something goes wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }

    // Update an existing category
    public function update()
    {
        // Create query
        $query =
            "UPDATE {$this->table}
            SET
                category = :category";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->category = htmlspecialchars(strip_tags($this->category));

        // Bind category
        $stmt->bindValue(':category', $this->category);

        // Execute the statement
        // TODO is this the correct error behavior for this project?
        if ($stmt->execute()) {
            return true;
        } else {
            // Print an error if something goes wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }

    // Delete Category
    public function delete()
    {
        // TODO
    }
}
