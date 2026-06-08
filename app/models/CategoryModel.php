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

    // Lấy 1 danh mục theo ID
    public function getCategoryById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Cập nhật danh mục
    public function updateCategory($id, $name, $description)
    {
        // Kiểm tra trùng tên với ID khác
        $check = $this->conn->prepare(
            "SELECT id FROM " . $this->table_name . " WHERE name = :name AND id != :id LIMIT 1"
        );
        $check->bindParam(':name', $name);
        $check->bindParam(':id',   $id);
        $check->execute();
        if ($check->rowCount() > 0) return 'duplicate';

        $stmt = $this->conn->prepare(
            "UPDATE " . $this->table_name . " SET name=:name, description=:description WHERE id=:id"
        );
        $stmt->bindParam(':name',        $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id',          $id);
        return $stmt->execute();
    }

    // Xóa danh mục
    public function deleteCategory($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
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