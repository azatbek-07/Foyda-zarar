<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ./login/");
    exit;
}

include './db.php';
$db = new DB();

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $purchase_price = $_POST['purchase_price'];
    $sale_price = $_POST['sale_price'];
    $quantity = $_POST['quantity'];

    try {
        // Insert the new product into the database
        $db->insert('products', [
            'name' => $name,
            'description' => $description,
            'purchase_price' => $purchase_price,
            'sale_price' => $sale_price,
            'quantity' => $quantity
        ]);

        $success_message = "Mahsulot muvaffaqiyatli qo'shildi!";
        header("Location: ./");
        exit;
        // Clear form after success
        $_POST = array();
    } catch (Exception $e) {
        $error_message = "Xatolik yuz berdi: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahsulot qo'shish - Ombor boshqaruvi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body
    class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 min-h-screen flex items-center justify-center p-4">
    <div
        class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all hover:scale-[1.02]">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-10 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white bg-opacity-20 rounded-full mb-4">
                <i class="fas fa-box-open text-3xl text-white"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Yangi Mahsulot Qo'shish</h1>
            <p class="text-indigo-100 text-sm">Omborga yangi mahsulot qo'shish uchun ma'lumotlarni to'ldiring</p>
        </div>

        <!-- Form -->
        <div class="px-8 py-10">
            <!-- Success Message -->
            <?php if ($success_message): ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg animate-pulse">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        <p class="text-green-700 font-medium"><?php echo htmlspecialchars($success_message); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Error Message -->
            <?php if ($error_message): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        <p class="text-red-700 font-medium"><?php echo htmlspecialchars($error_message); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <form action="add_product.php" method="post" class="space-y-6">
                <!-- Product Name -->
                <div>
                    <label for="name" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tag text-indigo-500"></i>
                        Mahsulot Nomi
                    </label>
                    <div class="relative">
                        <i class="fas fa-cube absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="name" id="name"
                            value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none"
                            placeholder="Mahsulot nomini kiriting" required>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-align-left text-indigo-500"></i>
                        Tavsif
                    </label>
                    <div class="relative">
                        <i class="fas fa-pen absolute left-3 top-4 text-gray-400"></i>
                        <textarea name="description" id="description" rows="4"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none resize-none"
                            placeholder="Mahsulot tavsifini kiriting"
                            required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>
                </div>

                <!-- Purchase Price & Sale Price -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="purchase_price"
                            class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-shopping-cart text-indigo-500"></i>
                            Sotib Olish Narxi
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">$</span>
                            <input type="number" name="purchase_price" id="purchase_price" step="0.01" min="0"
                                value="<?php echo isset($_POST['purchase_price']) ? htmlspecialchars($_POST['purchase_price']) : ''; ?>"
                                class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none"
                                placeholder="0.00" required>
                        </div>
                    </div>

                    <div>
                        <label for="sale_price"
                            class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign text-indigo-500"></i>
                            Sotish Narxi
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">$</span>
                            <input type="number" name="sale_price" id="sale_price" step="0.01" min="0"
                                value="<?php echo isset($_POST['sale_price']) ? htmlspecialchars($_POST['sale_price']) : ''; ?>"
                                class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none"
                                placeholder="0.00" required>
                        </div>
                    </div>
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-cubes text-indigo-500"></i>
                        Miqdor
                    </label>
                    <div class="relative">
                        <i class="fas fa-hashtag absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="number" name="quantity" id="quantity" min="0"
                            value="<?php echo isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : ''; ?>"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none"
                            placeholder="Mahsulot miqdorini kiriting" required>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <i class="fas fa-plus-circle"></i>
                        Mahsulot Qo'shish
                    </button>
                    <a href="./"
                        class="flex-1 bg-gray-500 text-white py-3 px-6 rounded-lg font-semibold hover:bg-gray-600 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2 text-center">
                        <i class="fas fa-arrow-left"></i>
                        Orqaga Qaytish
                    </a>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-8 py-4 text-center border-t border-gray-200">
            <p class="text-xs text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Barcha maydonlar to'ldirilishi shart
            </p>
        </div>
    </div>

    <script>
        // Optional: Add form validation or animations
        document.getElementById('productForm').addEventListener('submit', function (e) {
            const button = this.querySelector('button[type="submit"]');
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saqlanmoqda...';
            button.disabled = true;
        });
    </script>
</body>

</html>