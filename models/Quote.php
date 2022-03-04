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
        // Build a WHERE statement if a category or author id is set
        $where = "";

        if ($this->categoryId || $this->authorId) {
            $args = [];
            if ($this->categoryId) {
                array_push($args, "quotes.categoryId = {$this->categoryId}");
            }
            if ($this->authorId) {
                array_push($args, "quotes.authorId = {$this->authorId}");
            }
            $where = "WHERE " . implode(" AND ", $args);
        }


        // Create query
        $query =
            "SELECT quotes.id, quotes.quote, quotes.authorId, quotes.categoryId, authors.author, categories.category 
            FROM ((quotes
            INNER JOIN authors ON quotes.authorId = authors.id)
            INNER JOIN categories ON quotes.categoryId = categories.id)
            {$where}";

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
            "SELECT quotes.id, quotes.quote, quotes.authorId, quotes.categoryId, authors.author, categories.category 
            FROM ((quotes
            INNER JOIN authors ON quotes.authorId = authors.id)
            INNER JOIN categories ON quotes.categoryId = categories.id)
            WHERE
                quotes.id = :id
            LIMIT 0,1";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind ID
        $stmt->bindValue(':id', $this->id);

        // Execute the statement
        $stmt->execute();

        // Return statement
        return $stmt;
        /* 
        // Fetch the single row of data from the db
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Store the fetched data in this object instance
        $this->quote = $row['quote'];
        $this->authorId = $row['authorId'];
        $this->categoryId = $row['categoryId']; */
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
        $this->quote = htmlspecialchars(strip_tags($this->quote), ENT_NOQUOTES);
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
                id = :id";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->quote = htmlspecialchars(strip_tags($this->quote), ENT_NOQUOTES);

        // Bind quote
        $stmt->bindValue(':quote', $this->quote);
        $stmt->bindValue(':id', $this->id);

        // TODO check row count to see if any rows affected
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
