<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
class ProductController {
private $productModel;
private $db;
public function __construct() {
$this->db = (new Database())->getConnection();
$this->productModel = new ProductModel($this->db);
}
public function index() {
    // 1. Lấy danh sách toàn bộ sản phẩm
    $products = $this->productModel->getProducts();
    
    // 2. BỔ SUNG: Khởi tạo model và lấy danh sách danh mục cho bộ lọc
    $categoryModel = new CategoryModel($this->db);
    $categories = $categoryModel->getCategories();

    // 3. Include view hiển thị
    include 'app/views/product/list.php';
}
public function show($id) {
$product = $this->productModel->getProductById($id);
if ($product) {
include 'app/views/product/show.php';
} else {
echo "Không thấy sản phẩm.";
}
}
public function add() {
$categories = (new CategoryModel($this->db))->getCategories();
include_once 'app/views/product/add.php';
}
public function save() {
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$price = $_POST['price'] ?? '';
$category_id = $_POST['category_id'] ?? null;
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
$image = $this->uploadImage($_FILES['image']);
} else {
$image = "";
}
$result = $this->productModel->addProduct($name, $description, $price,

$category_id, $image);

if (is_array($result)) {
$errors = $result;
$categories = (new CategoryModel($this->db))->getCategories();
include 'app/views/product/add.php';
} else {
header('Location: /project1/Product');
}
}
}
public function edit($id) {
$product = $this->productModel->getProductById($id);
$categories = (new CategoryModel($this->db))->getCategories();
if ($product) {
include 'app/views/product/edit.php';
} else {
echo "Không thấy sản phẩm.";
}
}
public function update() {
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$id = $_POST['id'];
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$category_id = $_POST['category_id'];
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
$image = $this->uploadImage($_FILES['image']);
} else {
$image = $_POST['existing_image'];
}
$edit = $this->productModel->updateProduct($id, $name, $description,

$price, $category_id, $image);
if ($edit) {
header('Location: /project1/Product');
} else {
echo "Đã xảy ra lỗi khi lưu sản phẩm.";
}
}
}
public function delete($id) {
    if ($this->productModel->deleteProduct($id)) {
    header('Location: /project1/Product');
    } else {
    echo "Đã xảy ra lỗi khi xóa sản phẩm.";
    }
    }
    private function uploadImage($file) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
    throw new Exception("File không phải là hình ảnh.");
    }
    if ($file["size"] > 10 * 1024 * 1024) {
    throw new Exception("Hình ảnh có kích thước quá lớn.");
    }
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
    throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
    }
    if (!move_uploaded_file($file["tmp_name"], $target_file)) {
    throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
    }
    return $target_file;
    }
    public function addToCart($id) {
    $product = $this->productModel->getProductById($id);
    if (!$product) {
    echo "Không tìm thấy sản phẩm.";
    return;
    }
    if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (isset($_SESSION['cart'][$id])) {
$_SESSION['cart'][$id]['quantity']++;
} else {
$_SESSION['cart'][$id] = [
'name' => $product->name,
'price' => $product->price,
'quantity' => 1,
'image' => $product->image
];
}
header('Location: /project1/Product/cart');
}
public function list()
{
    $keyword = $_GET['keyword'] ?? '';
    $category_id = $_GET['category_id'] ?? '';

    if ($keyword || $category_id) {
        $products = $this->productModel->filterProducts($keyword, $category_id);
    } else {
        $products = $this->productModel->getProducts();
    }

    $categoryModel = new CategoryModel($this->db);
    $categories = $categoryModel->getCategories();

    include 'app/views/product/list.php';
}
}
?>