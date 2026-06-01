<?php
class OrderItemModel {
    private $conn;
    private $table = 'orderitem';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($order_id, $product_id, $quantity, $price) {
        $query = "INSERT INTO " . $this->table . " 
                  (order_id, product_id, quantity, price) 
                  VALUES (:order_id, :product_id, :quantity, :price)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $price);
        
        return $stmt->execute();
    }

    public function getByOrderId($order_id) {
        $query = "SELECT oi.*, p.name as product_name, p.image 
                  FROM " . $this->table . " oi
                  JOIN product p ON oi.product_id = p.id
                  WHERE oi.order_id = :order_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteByOrderId($order_id) {
        $query = "DELETE FROM " . $this->table . " WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        return $stmt->execute();
    }
}