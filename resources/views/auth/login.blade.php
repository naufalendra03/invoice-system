<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - Invoice System</title>
@vite('resources/css/app.css')
</head>

<body class="bg-gray-900">

<div class="min-h-screen flex items-center justify-center px-4">

<div class="w-full max-w-5xl grid md:grid-cols-2 bg-gray-800 rounded-xl shadow-lg overflow-hidden">

<!-- LEFT -->
<div class="p-10 text-white flex flex-col justify-center bg-gradient-to-b from-gray-800 to-gray-900">

<h1 class="text-3xl font-bold mb-4">
Invoice System
</h1>

<p class="text-gray-300 mb-6">
Kelola invoice, pembayaran, dan laporan piutang dengan mudah dan cepat.
</p>

<ul class="space-y-2 text-sm text-gray-400">
<li>✔ Manajemen Invoice</li>
<li>✔ Laporan Piutang</li>
<li>✔ Monitoring Omset</li>
<li>✔ Kirim Laporan WhatsApp</li>
</ul>

</div>

<!-- RIGHT -->
<div class="p-10 bg-white">

<h2 class="text-2xl font-bold mb-6">
Login
</h2>

<form method="POST" action="{{ route('login') }}">
@csrf

<div class="mb-4">
<label>Email</label>
<input type="email" name="email"
class="w-full border p-2 rounded">
</div>

<div class="mb-4">
<label>Password</label>
<input type="password" name="password"
class="w-full border p-2 rounded">
</div>

<div class="flex justify-between items-center mb-4 text-sm">
<label class="flex items-center">
<input type="checkbox" class="mr-2"> Remember me
</label>

<a href="#" class="text-blue-600">Forgot?</a>
</div>

<button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
Login
</button>

<p class="text-sm text-center mt-4">
Belum punya akun?
<a href="{{ route('register') }}" class="text-blue-600">Register</a>
</p>

</form>

</div>

</div>

</div>

</body>
</html>