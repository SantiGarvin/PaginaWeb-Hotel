<?php
require_once 'Database.php';

class Api
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getUsers()
    {
        $query = "SELECT * FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUser($id)
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($data)
    {
        $query = "INSERT INTO users SET name = :name, email = :email, password = :password, role = :role";
        $stmt = $this->conn->prepare($query);

        $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $data['role']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        } else {
            return false;
        }
    }

    public function updateUser($id, $data)
    {
        $query = "UPDATE users SET name = :name, email = :email, password = :password, role = :role WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function deleteUser($id)
    {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Métodos similares para habitaciones
    public function getRooms()
    {
        $query = "SELECT * FROM rooms";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoom($id)
    {
        $query = "SELECT * FROM rooms WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createRoom($data)
    {
        $query = "INSERT INTO rooms SET number = :number, capacity = :capacity, price = :price, description = :description";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':number', $data['number']);
        $stmt->bindParam(':capacity', $data['capacity']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':description', $data['description']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        } else {
            return false;
        }
    }

    public function updateRoom($id, $data)
    {
        $query = "UPDATE rooms SET number = :number, capacity = :capacity, price = :price, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':number', $data['number']);
        $stmt->bindParam(':capacity', $data['capacity']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function deleteRoom($id)
    {
        $query = "DELETE FROM rooms WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Métodos similares para reservas
    public function getReservations()
    {
        $query = "SELECT * FROM reservations";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservation($id)
    {
        $query = "SELECT * FROM reservations WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createReservation($data)
    {
        $query = "INSERT INTO reservations SET user_id = :user_id, room_id = :room_id, guests = :guests, comments = :comments, check_in = :check_in, check_out = :check_out, status = :status";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':room_id', $data['room_id']);
        $stmt->bindParam(':guests', $data['guests']);
        $stmt->bindParam(':comments', $data['comments']);
        $stmt->bindParam(':check_in', $data['check_in']);
        $stmt->bindParam(':check_out', $data['check_out']);
        $stmt->bindParam(':status', $data['status']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        } else {
            return false;
        }
    }

    public function updateReservation($id, $data)
    {
        $query = "UPDATE reservations SET user_id = :user_id, room_id = :room_id, guests = :guests, comments = :comments, check_in = :check_in, check_out = :check_out, status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':room_id', $data['room_id']);
        $stmt->bindParam(':guests', $data['guests']);
        $stmt->bindParam(':comments', $data['comments']);
        $stmt->bindParam(':check_in', $data['check_in']);
        $stmt->bindParam(':check_out', $data['check_out']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function deleteReservation($id)
    {
        $query = "DELETE FROM reservations WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
