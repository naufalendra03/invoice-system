<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - Invoice System</title>
@vite('resources/css/app.css')
</head>

<body class="bg-gray-900">

<div class="min-h-screen flex items-center justify-center px-4">

<div class="w-full max-w-5xl grid md:grid-cols-2 bg-gray-800 rounded-xl shadow-lg overflow-hidden">

<!-- LEFT SIDE -->
<div class="p-10 text-white flex flex-col justify-center bg-gradient-to-b from-gray-800 to-gray-900">

<h1 class="text-3xl font-bold mb-4">
Buat Akun
</h1>

<p class="text-gray-300 mb-6">
Mulai gunakan sistem invoice untuk mengelola bisnis Anda secara profesional.
</p>

<ul class="space-y-2 text-sm text-gray-400">
<li>✔ Kelola Invoice</li>
<li>✔ Laporan Piutang</li>
<li>✔ Monitoring Omset</li>
<li>✔ Kirim Laporan WhatsApp</li>
</ul>

</div>

<!-- RIGHT SIDE -->
<div class="p-10 bg-white">

<h2 class="text-2xl font-bold mb-6">
Register
</h2>

<form method="POST" action="{{ route('register') }}">
@csrf

<!-- NAME -->
<div class="mb-4">
<label class="block mb-1">Nama</label>
<input type="text" name="name"
value="{{ old('name') }}"
class="w-full border p-2 rounded"
required>
</div>

<!-- EMAIL -->
<div class="mb-4">
<label class="block mb-1">Email</label>
<input type="email" name="email"
value="{{ old('email') }}"
class="w-full border p-2 rounded"
required>
</div>

<!-- PASSWORD -->
<div class="mb-4">
<label class="block mb-1">Password</label>
<input type="password" name="password"
class="w-full border p-2 rounded"
required>
</div>

<!-- CONFIRM PASSWORD -->
<div class="mb-4">
<label class="block mb-1">Konfirmasi Password</label>
<input type="password" name="password_confirmation"
class="w-full border p-2 rounded"
required>
</div>

<!-- ERROR VALIDATION -->
@if($errors->any())
<div class="mb-4 text-red-600 text-sm">
<ul>
@foreach($errors->all() as $error)
<li>- {{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<!-- BUTTON -->
<button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
Register
</button>

<!-- LOGIN LINK -->
<p class="text-sm text-center mt-4">
Sudah punya akun?
<a href="{{ route('login') }}" class="text-blue-600 hover:underline">
Login
</a>
</p>

</form>

</div>

</div>

</div>

</body>
</html>