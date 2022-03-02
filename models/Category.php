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
        // Return early if there is no ID
        if (!$this->id) {
            return;
        }

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
        // Clean data
        $this->category = htmlspecialchars(strip_tags($this->category));

        // Return early if no category provided
        if (!$this->category || $this->category === "") {
            return false;
        }

        // Create query
        $query =
            "INSERT INTO {$this->table}
            SET
                category = :category";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Bind category
        $stmt->bindValue(':category', $this->category);

        // Execute the statement
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
        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->category = htmlspecialchars(strip_tags($this->category));

        // Return early if no id or category provided
        if (!$this->id || !$this->category || $this->category === "") {
            return false;
        }

        // Create query
        $query =
            "UPDATE {$this->table}
            SET
                category = :category
            WHERE
                id = :id";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Bind category
        $stmt->bindValue(':category', $this->category);
        $stmt->bindValue(':id', $this->id);

        // Execute the statement
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
        /* echo "in\n";
        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        echo "cleaned\n";
        // Return early if no id provided
        if (!$this->id) {
            return false;
        }

        // Create query
        $query = "DELETE FROM {$this->table} WHERE id = :id";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);
        echo "prep\n";
        // Bind id
        $stmt->bindValue(':id', $this->id);
        echo "bound\n";
        // Execute the statement        
        if ($stmt->execute()) {
            return true;
        } else {
            // Print an error if something goes wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        } */

        // Create query
        $query = "DELETE FROM {$this->table} WHERE id= :id";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean id value
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind id
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            // Print error if something goes wrong
            printf("Error");
            return false;
        }
    }
}
