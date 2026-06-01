<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/auth_helper.php');

class ProductController {

    private $productModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // ============================
    // CÔNG KHAI (khách + admin)
    // ============================

    public function index() {
        $products = $this->productModel->getProducts();
        $categoryModel = new CategoryModel($this->db);
        $categories = $categoryModel->getCategories();
        include 'app/views/product/list.php';
    }

    public function list() {
        $keyword     = $_GET['keyword'] ?? '';
        $category_id = $_GET['category_id'] ?? '';

        if ($keyword || $category_id) {
            $products = $this->productModel->filterProducts($keyword, $category_id);
        } else {
            $products = $this->productModel->getProducts();
        }

        $categoryModel = new CategoryModel($this->db);
        $categories    = $categoryModel->getCategories();
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

    // ============================
    // CHỈ ADMIN
    // ============================

    public function add() {
        requireAdmin();
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    public function save() {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name        = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price       = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = "";
            }

            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

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
        requireAdmin();
        $product    = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();

        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update() {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id          = $_POST['id'];
            $name        = $_POST['name'];
            $description = $_POST['description'];
            $price       = $_POST['price'];
            $category_id = $_POST['category_id'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = $_POST['existing_image'];
            }

            $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);

            if ($edit) {
                header('Location: /project1/Product');
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    public function delete($id) {
        requireAdmin();

        if ($this->productModel->deleteProduct($id)) {
            header('Location: /project1/Product');
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    // ============================
    // GIỎ HÀNG (mọi người dùng)
    // ============================

    public function addToCart($id) {
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            echo "Không tìm thấy sản phẩm.";
            return;
        }

        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        if ($quantity <= 0) $quantity = 1;

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$id] = [
                'id'       => $id,
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => $quantity,
                'image'    => $product->image
            ];
        }

        header('Location: /project1/Product/cart');
    }

    public function cart() {
        $cart = $_SESSION['cart'];
        include 'app/views/product/cart.php';
    }

    public function updateCart() {
        if (isset($_POST['quantity'])) {
            foreach ($_POST['quantity'] as $id => $qty) {
                if ($qty <= 0) {
                    unset($_SESSION['cart'][$id]);
                } else {
                    $_SESSION['cart'][$id]['quantity'] = $qty;
                }
            }
        }
        header('Location: /project1/Product/cart');
    }

    public function removeFromCart($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: /project1/Product/cart');
    }

    public function checkout() {
        $cart = $_SESSION['cart'];
        include 'app/views/product/checkout.php';
    }

    public function processCheckout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name    = $_POST['name'];
            $phone   = $_POST['phone'];
            $address = $_POST['address'];
            $payment = $_POST['payment_method'];

            $cart  = $_SESSION['cart'] ?? [];
            $total = 0;

            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            $discount       = $_SESSION['discount'] ?? 0;
            $discountAmount = ($total * $discount) / 100;
            $finalTotal     = $total - $discountAmount;

            $order = [
                'order_id'      => rand(1000, 9999),
                'customer_name' => $name,
                'phone'         => $phone,
                'address'       => $address,
                'payment'       => $payment,
                'items'         => $cart,
                'total'         => $finalTotal,
                'status'        => 'Đang xử lý',
                'created_at'    => date('d/m/Y H:i:s')
            ];

            if (!isset($_SESSION['orders'])) {
                $_SESSION['orders'] = [];
            }

            array_unshift($_SESSION['orders'], $order);
            unset($_SESSION['cart']);

            header('Location: /project1/Product/myOrders');
        }
    }

    public function cancelOrder($index) {
        if (isset($_SESSION['orders'][$index])) {
            $payment = $_SESSION['orders'][$index]['payment'];
            $_SESSION['orders'][$index]['status'] = ($payment == 'bank') ? 'Chờ hoàn tiền' : 'Đã hủy';
        }
        header('Location: /project1/Product/myOrders');
    }

    public function deleteOrder($index) {
        if (isset($_SESSION['orders'][$index])) {
            unset($_SESSION['orders'][$index]);
            $_SESSION['orders'] = array_values($_SESSION['orders']);
        }
        header('Location: /project1/Product/myOrders');
    }

    public function myOrders() {
        $orders = $_SESSION['orders'] ?? [];
        include 'app/views/product/my-orders.php';
    }

    public function orderSuccess($id) {
        $query = "SELECT * FROM orders WHERE id = :id";
        $stmt  = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_OBJ);
        include 'app/views/product/orderSuccess.php';
    }

    // ============================
    // PRIVATE HELPERS
    // ============================

    private function uploadImage($file) {
        $target_dir   = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $target_file   = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check         = getimagesize($file["tmp_name"]);

        if ($check === false)                                         throw new Exception("File không phải là hình ảnh.");
        if ($file["size"] > 10 * 1024 * 1024)                        throw new Exception("Hình ảnh có kích thước quá lớn.");
        if (!in_array($imageFileType, ["jpg","jpeg","png","gif"]))    throw new Exception("Chỉ cho phép JPG, JPEG, PNG và GIF.");
        if (!move_uploaded_file($file["tmp_name"], $target_file))     throw new Exception("Có lỗi xảy ra khi tải hình ảnh.");

        return $target_file;
    }
}
?>
