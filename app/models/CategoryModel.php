<?php

class CategoryModel
{
    private $conn;

    private $table_name = "category";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy danh sách category
    public function getCategories()
{
    $query = "SELECT MIN(id) as id,
                     name,
                     MIN(description) as description
              FROM " . $this->table_name . "
              GROUP BY name";

    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

    // Thêm category và chống trùng
    public function addCategory($name, $description)
    {
        // Kiểm tra category đã tồn tại chưa
        $checkQuery = "SELECT * FROM " . $this->table_name . "
                       WHERE name = :name";

        $checkStmt = $this->conn->prepare($checkQuery);

        $checkStmt->bindParam(':name', $name);

        $checkStmt->execute();

        // Nếu đã tồn tại
        if ($checkStmt->rowCount() > 0) {

            return false;
        }

        // Thêm mới category
        $query = "INSERT INTO " . $this->table_name . "
                  (name, description)
                  VALUES
                  (:name, :description)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }
}

?>