<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductApiController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db           = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    // Đọc body JSON, ưu tiên $GLOBALS nếu router đã đọc trước
    private function getBody()
    {
        if (!empty($GLOBALS['_API_BODY'])) {
            return $GLOBALS['_API_BODY'];
        }
        $raw = file_get_contents('php://input');
        return $raw ? (json_decode($raw, true) ?? []) : [];
    }

    /**
     * Xử lý upload ảnh từ $_FILES['image']
     * Trả về đường dẫn tương đối (uploads/xxx.jpg) hoặc null nếu không có file
     */
    private function handleImageUpload($fieldName = 'image')
    {
        if (empty($_FILES[$fieldName]['tmp_name'])) {
            return null; // Không có file upload
        }

        $file     = $_FILES[$fieldName];
        $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $mimeType = mime_content_type($file['tmp_name']);

        if (!in_array($mimeType, $allowed)) {
            return false; // Loại file không hợp lệ
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            return false; // Vượt 5MB
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'product_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        $destDir  = 'uploads/';
        $destPath = $destDir . $filename;

        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return $destPath; // "uploads/product_xxx.jpg"
        }
        return false;
    }

    // GET /api/product
    public function index()
    {
        header('Content-Type: application/json');
        $products = $this->productModel->getProducts();
        echo json_encode($products);
    }

    // GET /api/product/{id}
    public function show($id)
    {
        header('Content-Type: application/json');
        $product = $this->productModel->getProductById($id);
        if ($product) {
            echo json_encode($product);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Product not found']);
        }
    }

    // POST /api/product  (hỗ trợ cả JSON và multipart/form-data)
    public function store()
    {
        header('Content-Type: application/json');

        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (strpos($contentType, 'multipart/form-data') !== false) {
            // Gửi kèm file -> đọc từ $_POST
            $name        = $_POST['name']        ?? '';
            $description = $_POST['description'] ?? '';
            $price       = $_POST['price']       ?? '';
            $category_id = $_POST['category_id'] ?? null;

            $image = $this->handleImageUpload('image');
            if ($image === false) {
                http_response_code(400);
                echo json_encode(['errors' => ['image' => 'File ảnh không hợp lệ (chỉ jpg/png/gif/webp, tối đa 5MB)']]);
                return;
            }
        } else {
            // Gửi JSON (không có file)
            $data        = $this->getBody();
            $name        = $data['name']        ?? '';
            $description = $data['description'] ?? '';
            $price       = $data['price']       ?? '';
            $category_id = $data['category_id'] ?? null;
            $image       = $data['image']       ?? null;
        }

        $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

        if (is_array($result)) {
            http_response_code(400);
            echo json_encode(['errors' => $result]);
        } else {
            http_response_code(201);
            echo json_encode(['message' => 'Product created successfully']);
        }
    }

    // PUT /api/product/{id}  (hỗ trợ cả JSON và multipart/form-data)
    public function update($id)
    {
        header('Content-Type: application/json');

        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        // Lấy ảnh cũ trước
        $current   = $this->productModel->getProductById($id);
        $oldImage  = $current ? $current->image : null;

        if (strpos($contentType, 'multipart/form-data') !== false) {
            // FormData: jQuery gửi via POST với _method=PUT được xử lý ở đây
            $name        = $_POST['name']        ?? '';
            $description = $_POST['description'] ?? '';
            $price       = $_POST['price']       ?? '';
            $category_id = $_POST['category_id'] ?? null;

            $uploadedImage = $this->handleImageUpload('image');
            if ($uploadedImage === false) {
                http_response_code(400);
                echo json_encode(['errors' => ['image' => 'File ảnh không hợp lệ (chỉ jpg/png/gif/webp, tối đa 5MB)']]);
                return;
            }
            // Dùng ảnh mới nếu có, không thì giữ ảnh cũ
            $image = $uploadedImage !== null ? $uploadedImage : $oldImage;
        } else {
            // JSON
            $data        = $this->getBody();
            $name        = $data['name']        ?? '';
            $description = $data['description'] ?? '';
            $price       = $data['price']       ?? '';
            $category_id = $data['category_id'] ?? null;
            $image       = array_key_exists('image', $data) ? $data['image'] : $oldImage;
        }

        $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);

        if ($result) {
            echo json_encode(['message' => 'Product updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Product update failed']);
        }
    }

    // DELETE /api/product/{id}
    public function destroy($id)
    {
        header('Content-Type: application/json');
        $result = $this->productModel->deleteProduct($id);

        if ($result) {
            echo json_encode(['message' => 'Product deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Product deletion failed']);
        }
    }
}
?>
