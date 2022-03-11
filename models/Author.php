<?php
class Author
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

    // Read a single author by id
    public function readSingle()
    {
        // Create query
        $query =
            "SELECT
                id,
                author
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
        $this->author = $row['author'];
    }

    // Create a new author
    public function create()
    {
        // Create query
        $query =
            "INSERT INTO {$this->table}
            SET
                author = :author";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->author = htmlspecialchars(strip_tags($this->author), ENT_NOQUOTES);

        // Bind author
        $stmt->bindValue(':author', $this->author);

        // Execute the statement and check row count to see if any rows affected
        // If the statement executes and affected a row, return true for success
        return ($stmt->execute() && $stmt->rowCount());
    }

    // Update an existing author
    public function update()
    {
        // Create query
        $query =
            "UPDATE {$this->table}
            SET
                author = :author
            WHERE
                id = :id";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->author = htmlspecialchars(strip_tags($this->author), ENT_NOQUOTES);

        // Bind author
        $stmt->bindValue(':author', $this->author);
        $stmt->bindValue(':id', $this->id);

        // Execute the statement and check row count to see if any rows affected
        // If the statement executes and affected a row, return true for success
        return ($stmt->execute() && $stmt->rowCount());
    }

    // Delete author
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
        try {
            return ($stmt->execute() && $stmt->rowCount());
        } catch (Exception $e) {
            // Null signifies failure of the delete operation
            return null;
        }
    }
}
