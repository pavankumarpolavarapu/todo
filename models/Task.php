<?php

class Task
{
    private $conn;
    private $table = 'tasks';

    // Table Schema
    private $id;
    private $user_id;
    private $category_id;
    private $name;
    private $due_date;
    private $remind_date;
    private $created_at;
    private $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    // READ OPERATION
    public function read()
    {
        $query = 'SELECT
			T.id,
			T.name as taskname,
			T.completed as completed,
			C.name as categoryname,
			T.due_date
		FROM ' . $this->table . ' T
		LEFT JOIN categories C ON T.category_id = C.id
		ORDER BY T.created_at ASC';

        //prepare statement
        $stmt = $this->conn->prepare($query);
        //Execute
        $stmt->execute();
        return $stmt;
    }
    // READ OPERATION
    public function read_single($id)
    {
        $query = 'SELECT
			T.id,
			T.name as taskname,
			C.name as categoryname,
			T.due_date
		FROM ' . $this->table . ' T
		LEFT JOIN categories C ON T.category_id = C.id
		WHERE T.id = ?
		LIMIT 0,1';

        try {
            //prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, htmlspecialchars(strip_tags($id)));
            //Execute
            $stmt->execute();
        } catch (Exception $e) {
            die('Failed: ' . $e->getMessage());
        }
        return $stmt;
    }
    // CREATE Operation
    public function insert($task)
    {
        $query = 'INSERT INTO ' . $this->table . ' (name)
		VALUES ( ? )';

        try {
            //prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, htmlspecialchars(strip_tags($task->name)));
            //Execute
            if ($stmt->execute()) {
                $query = 'SELECT MAX(id) as id FROM ' . $this->table;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
            }
            return $stmt;
        } catch (Exception $e) {
            die('Failed: ' . $e->getMessage());
        }
    }

    // DELETE Operation
    public function delete($task)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id= ?';
        try {
			$stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, htmlspecialchars(strip_tags($task->id)));
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            die('Failed: ' . $e->getMessage());
        }
	}
    // UPDATE Operation
    public function update($task)
    {
        $query = 'UPDATE ' . $this->table . ' SET COMPLETED = ? WHERE id= ?';
        try {
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam(1, htmlspecialchars(strip_tags($task->completed)));
			$stmt->bindParam(2, htmlspecialchars(strip_tags($task->id)));
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            die('Failed: ' . $e->getMessage());
        }
    }	
}
