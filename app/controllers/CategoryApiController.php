<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryApiController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db            = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    private function getBody()
    {
        if (!empty($GLOBALS['_API_BODY'])) return $GLOBALS['_API_BODY'];
        $raw = file_get_contents('php://input');
        return $raw ? (json_decode($raw, true) ?? []) : [];
    }

    // GET /api/category
    public function index()
    {
        header('Content-Type: application/json');
        echo json_encode($this->categoryModel->getCategories());
    }

    // GET /api/category/{id}
    public function show($id)
    {
        header('Content-Type: application/json');
        $cat = $this->categoryModel->getCategoryById($id);
        if ($cat) {
            echo json_encode($cat);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Category not found']);
        }
    }

    // POST /api/category
    public function store()
    {
        header('Content-Type: application/json');
        $data = $this->getBody();
        $name        = trim($data['name']        ?? '');
        $description = trim($data['description'] ?? '');

        if (empty($name)) {
            http_response_code(400);
            echo json_encode(['errors' => ['name' => 'Tên danh mục không được để trống']]);
            return;
        }

        $result = $this->categoryModel->addCategory($name, $description);
        if ($result === false) {
            http_response_code(409);
            echo json_encode(['message' => 'Danh mục "' . $name . '" đã tồn tại']);
        } else {
            http_response_code(201);
            echo json_encode(['message' => 'Category created successfully']);
        }
    }

    // PUT /api/category/{id}
    public function update($id)
    {
        header('Content-Type: application/json');
        $data = $this->getBody();
        $name        = trim($data['name']        ?? '');
        $description = trim($data['description'] ?? '');

        if (empty($name)) {
            http_response_code(400);
            echo json_encode(['errors' => ['name' => 'Tên danh mục không được để trống']]);
            return;
        }

        $result = $this->categoryModel->updateCategory($id, $name, $description);
        if ($result === 'duplicate') {
            http_response_code(409);
            echo json_encode(['message' => 'Tên danh mục "' . $name . '" đã tồn tại']);
        } elseif ($result) {
            echo json_encode(['message' => 'Category updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Category update failed']);
        }
    }

    // DELETE /api/category/{id}
    public function destroy($id)
    {
        header('Content-Type: application/json');
        $result = $this->categoryModel->deleteCategory($id);
        if ($result) {
            echo json_encode(['message' => 'Category deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Category deletion failed or not found']);
        }
    }
}
?>
