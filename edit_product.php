<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login/index.php");
    exit;
}

include './db.php';

$db = new DB();

// Get product ID from URL
$product_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$product_id) {
    header("Location: ./");
    exit;
}



// Fetch product data
$product = $db->select('products', '*', ['id' => $product_id]);
if (empty($product)) {
    header("Location: ./");
    exit;
}
$product = $product[0];

$success_message = '';
$error_message = '';

// =======================
// UPDATE
// =======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $purchase_price = $_POST['purchase_price'];
    $sale_price = $_POST['sale_price'];
    $quantity = $_POST['quantity'];

    try {
        $db->update(
            'products',
            [
                'name' => $name,
                'description' => $description,
                'purchase_price' => $purchase_price,
                'sale_price' => $sale_price,
                'quantity' => $quantity
            ],
            [
                'id' => $id
            ]
        );

        $success_message = "Mahsulot muvaffaqiyatli yangilandi!";
        
        // Refresh product data
        $product = $db->select('products', '*', ['id' => $id])[0];
        
        
        header("Location: ./");
        exit;
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
    <title>Mahsulotni Tahrirlash - OmborX</title>
    <link rel="icon" type="image/x-icon" href="https://cdn-icons-png.flaticon.com/512/1159/1159633.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .slide-in {
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        
        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">
    <!-- Top Navigation -->
    <nav class="bg-white/80 backdrop-blur-lg border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Tahrirlash</h1>
                        <p class="text-xs text-gray-500">Mahsulot ma'lumotlarini yangilash</p>
                    </div>
                </div>

                <!-- Back Button -->
                <a href="./" 
                   class="flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-xl font-medium hover:bg-gray-200 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Orqaga
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="slide-in">
            <!-- Success Message -->
            <?php if ($success_message): ?>
                <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-green-800">Muvaffaqiyatli!</h3>
                            <p class="text-green-700"><?php echo htmlspecialchars($success_message); ?></p>
                            <p class="text-green-600 text-sm mt-1 pulse">Bosh sahifaga qaytarilmoqda...</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Error Message -->
            <?php if ($error_message): ?>
                <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-2xl p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-red-800">Xatolik!</h3>
                            <p class="text-red-700"><?php echo htmlspecialchars($error_message); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Edit Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 px-8 py-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">Mahsulotni Tahrirlash</h2>
                            <p class="text-blue-100 text-sm">ID: #<?php echo htmlspecialchars($product['id']); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Form Body -->
                <div class="p-8">
                    <form action="" method="post" id="editForm">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
                        
                        <div class="space-y-6">
                            <!-- Product Name -->
                            <div>
                                <label for="name" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Mahsulot Nomi
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="<?php echo htmlspecialchars($product['name']); ?>"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all duration-200"
                                       placeholder="Mahsulot nomini kiriting"
                                       required>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                    Tavsif
                                </label>
                                <textarea name="description" 
                                          id="description" 
                                          rows="4"
                                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all duration-200 resize-none"
                                          placeholder="Mahsulot tavsifini kiriting"
                                          required><?php echo htmlspecialchars($product['description']); ?></textarea>
                            </div>

                            <!-- Prices Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Purchase Price -->
                                <div>
                                    <label for="purchase_price" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                                        </svg>
                                        Xarid Narxi
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold text-lg">$</span>
                                        <input type="number" 
                                               name="purchase_price" 
                                               id="purchase_price" 
                                               step="0.01"
                                               min="0"
                                               value="<?php echo htmlspecialchars($product['purchase_price']); ?>"
                                               class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all duration-200"
                                               placeholder="0.00"
                                               required>
                                    </div>
                                </div>

                                <!-- Sale Price -->
                                <div>
                                    <label for="sale_price" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Sotish Narxi
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold text-lg">$</span>
                                        <input type="number" 
                                               name="sale_price" 
                                               id="sale_price" 
                                               step="0.01"
                                               min="0"
                                               value="<?php echo htmlspecialchars($product['sale_price']); ?>"
                                               class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all duration-200"
                                               placeholder="0.00"
                                               required>
                                    </div>
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Miqdor
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           name="quantity" 
                                           id="quantity" 
                                           min="0"
                                           value="<?php echo htmlspecialchars($product['quantity']); ?>"
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all duration-200"
                                           placeholder="Mahsulot miqdorini kiriting"
                                           required>
                                    <div class="absolute right-2 top-1/2 transform -translate-y-1/2 flex gap-1">
                                        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-lg">dona</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Profit Preview -->
                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 border border-blue-100">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Narxlar Tahlili</h4>
                                <div class="grid grid-cols-3 gap-4 text-center">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Xarid</p>
                                        <p class="text-lg font-bold text-gray-700" id="previewPurchase">
                                            $<?php echo number_format($product['purchase_price'], 2); ?>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Sotish</p>
                                        <p class="text-lg font-bold text-green-600" id="previewSale">
                                            $<?php echo number_format($product['sale_price'], 2); ?>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Foyda</p>
                                        <p class="text-lg font-bold text-indigo-600" id="previewProfit">
                                            $<?php echo number_format($product['sale_price'] - $product['purchase_price'], 2); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 mt-8">
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 px-6 rounded-xl font-bold hover:shadow-2xl transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                O'zgarishlarni Saqlash
                            </button>
                            <a href="./" 
                               class="flex-1 bg-gray-100 text-gray-700 py-4 px-6 rounded-xl font-bold hover:bg-gray-200 transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center gap-2 text-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Bekor Qilish
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-200 bg-white mt-12">
        <div class="max-w-4xl mx-auto px-4 py-6 text-center">
            <p class="text-gray-500 text-sm">
                &copy; <?php echo date('Y'); ?> OmborX. Barcha huquqlar himoyalangan.
            </p>
        </div>
    </footer>

    <script>
        // Live profit preview
        const purchaseInput = document.getElementById('purchase_price');
        const saleInput = document.getElementById('sale_price');
        const previewPurchase = document.getElementById('previewPurchase');
        const previewSale = document.getElementById('previewSale');
        const previewProfit = document.getElementById('previewProfit');

        function updatePreview() {
            const purchase = parseFloat(purchaseInput.value) || 0;
            const sale = parseFloat(saleInput.value) || 0;
            const profit = sale - purchase;

            previewPurchase.textContent = '$' + purchase.toFixed(2);
            previewSale.textContent = '$' + sale.toFixed(2);
            
            const profitElement = previewProfit;
            profitElement.textContent = '$' + profit.toFixed(2);
            
            if (profit > 0) {
                profitElement.className = 'text-lg font-bold text-green-600';
            } else if (profit < 0) {
                profitElement.className = 'text-lg font-bold text-red-600';
            } else {
                profitElement.className = 'text-lg font-bold text-gray-600';
            }
        }

        purchaseInput.addEventListener('input', updatePreview);
        saleInput.addEventListener('input', updatePreview);
    </script>
</body>
</html>