<?php
class Quote
{
    // DB properties
    private $conn;
    private $table = 'quotes';

    // Properties
    public $id;
    public $quote;
    public $categoryId;
    public $authorId;

    // Constructor 
    // param: {PDO} dbConn - An active database connection
    public function __construct($dbConn)
    {
        $this->conn = $dbConn;
    }

    // Read all quotes
    public function read()
    {
        // Create query
        $query =
            "SELECT *
            FROM
                {$this->table}";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Execute the statement
        $stmt->execute();

        return $stmt;
    }

    // Read a single quote by id
    public function readSingle()
    {
        // Create query
        $query =
            "SELECT *
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
        $this->quote = $row['quote'];
        $this->authorId = $row['authorId'];
        $this->categoryId = $row['categoryId'];
    }

    // Create a new quote
    public function create()
    {
        // Create query
        $query =
            "INSERT INTO {$this->table}
            SET
                quote = :quote,
                authorId = :authorId,
                categoryId = :categoryId";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->authorId = htmlspecialchars(strip_tags($this->authorId));
        $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));

        // Bind quote
        $stmt->bindValue(':quote', $this->quote);
        $stmt->bindValue(':authorId', $this->authorId);
        $stmt->bindValue(':categoryId', $this->categoryId);

        // Execute the statement
        if ($stmt->execute()) {
            return true;
        } else {
            // Print an error if something goes wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }

    // Update an existing quote
    public function update()
    {
        // Create query
        $query =
            "UPDATE {$this->table}
            SET
                quote = :quote
            WHERE
                id = :id,
                authorId = :authorId,
                categoryId = :categoryId";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->authorId = htmlspecialchars(strip_tags($this->authorId));
        $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));

        // Bind quote
        $stmt->bindValue(':quote', $this->quote);
        $stmt->bindValue(':id', $this->id);
        $stmt->bindValue(':authorId', $this->authorId);
        $stmt->bindValue(':categoryId', $this->categoryId);

        // Execute the statement
        if ($stmt->execute()) {
            return true;
        } else {
            // Print an error if something goes wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }

    // Delete quote
    public function delete()
    {
        // TODO fails if the quote is a foreign key for a quote. Awaiting feedback on how to proceed.

        // Create query
        $query = "DELETE FROM {$this->table} WHERE id = :id";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind id
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
}
