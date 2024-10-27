<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rent Service</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Floating animation for the car image */
        @keyframes float {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0);
            }
        }
        /* Apply the animation to the car image */
        .animate-float {
            animation: float 9s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-white text-gray-800 font-sans">
    <!-- Header -->
    <header class="flex items-center justify-between p-6">
        <div class="flex items-center space-x-2">
            <!-- Logo Image -->
            <img src="img/logo.png" alt="logo" class="w-11 h-11">
            <span class="text-2xl font-bold text-purple-700">Car Rent</span>
        </div>
        <nav class="space-x-4">
            <a href="akun/login.php" class="px-4 py-2 text-white bg-purple-700 rounded-full">Get Started</a>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-16 flex flex-col md:flex-row items-center">
        <!-- Text Section -->
        <div class="md:w-1/2 mb-10 md:mb-0">
            <h1 class="text-5xl font-bold leading-tight text-gray-800 mb-6">
               Sewa Mobil Mudah dan Terjangkau
            </h1>
            <p class="text-gray-500 text-lg mb-8">
                 Temukan mobil yang sempurna untuk perjalanan Anda. Harga terjangkau, lokasi yang mudah dijangkau, dan berbagai pilihan mobil yang bisa Anda pilih!
            </p>
            <div class="space-x-4">
                <a href="akun/login.php" class="px-6 py-3 bg-purple-700 text-white rounded-full font-semibold hover:bg-purple-800">Get started</a>
            
            </div>
        </div>

        <!-- Image Section with Animation -->
        <div class="md:w-1/2 flex justify-center relative">
            <img src="img/car.jpg" alt="Car Dashboard" class="animate-float w-full max-w-md">
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-12 bg-gray-100 mt-auto">
        <div class="container mx-auto text-center">
            <p class="text-gray-500 text-sm mb-6">Car Rent &copy; 2024 - All Rights Reserved</p>
        </div>
    </footer>
</body>
</html>
