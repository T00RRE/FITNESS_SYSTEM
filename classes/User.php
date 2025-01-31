<?php
class User {
    private $conn;
    private $table = 'users';


    public $id;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $phone;
    public $role;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }


    public function register() {
        $query = "INSERT INTO " . $this->table . "
                (email, password, first_name, last_name, phone, role, created_at, updated_at)
                VALUES
                (:email, :password, :first_name, :last_name, :phone, :role, GETDATE(), GETDATE())";

        $stmt = $this->conn->prepare($query);


        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);


        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->role = htmlspecialchars(strip_tags($this->role));


        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':role', $this->role);

        try {
            if($stmt->execute()) {
                return true;
            }
        } catch(PDOException $e) {
            printf("Error: %s.\n", $e->getMessage());
            return false;
        }

        return false;
    }


    public function login($email, $password) {
        $query = "SELECT id, email, password, role, first_name, last_name 
                FROM " . $this->table . " 
                WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if(password_verify($password, $row['password'])) {
                $update_query = "UPDATE " . $this->table . " 
                               SET updated_at = GETDATE() 
                               WHERE id = :id";
                $update_stmt = $this->conn->prepare($update_query);
                $update_stmt->bindParam(':id', $row['id']);
                $update_stmt->execute();

                $this->id = $row['id'];
                $this->email = $row['email'];
                $this->first_name = $row['first_name'];
                $this->last_name = $row['last_name'];
                $this->role = $row['role'];
                
                return true;
            }
        }

        return false;
    }

    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    public function update() {
        $query = "UPDATE " . $this->table . "
                SET
                    first_name = :first_name,
                    last_name = :last_name,
                    phone = :phone,
                    updated_at = GETDATE()
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':id', $this->id);

        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            printf("Error: %s.\n", $e->getMessage());
            return false;
        }
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $this->id = $row['id'];
            $this->email = $row['email'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->phone = $row['phone'];
            $this->role = $row['role'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }
        return false;
    }
}
?>