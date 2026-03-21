<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{ config('app.name') }}</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-gray-100">

<div class="flex h-screen">

<!-- SIDEBAR -->

<div class="w-64 bg-gray-900 text-white flex flex-col">

<div class="p-5 text-xl font-bold border-b border-gray-700">
Invoice System
</div>

<nav class="flex-1 p-4 space-y-2">

<a href="/dashboard"
class="block px-4 py-2 rounded hover:bg-gray-700">

Dashboard

</a>

<div class="text-gray-400 text-sm mt-4 mb-2">
MASTER DATA
</div>

<a href="{{ route('companies.index') }}"
class="block px-4 py-2 rounded hover:bg-gray-700
{{ request()->routeIs('companies.*') ? 'bg-gray-700' : '' }}">
Companies
</a>

<a href="{{ route('customers.index') }}"
class="block px-4 py-2 rounded hover:bg-gray-700">

Customers

</a>

<a href="{{ route('products.index') }}"
class="block px-4 py-2 rounded hover:bg-gray-700">

Products

</a>

<div class="text-gray-400 text-sm mt-4 mb-2">
TRANSAKSI
</div>

<a href="{{ route('sales.index') }}"
class="block px-4 py-2 rounded hover:bg-gray-700">

Invoice

</a>


<a href="{{ route('reports.piutang') }}"
class="block px-4 py-2 hover:bg-gray-700">

Laporan Piutang

</a>
<a href="{{ route('reports.dashboard.piutang') }}"
class="block px-4 py-2 hover:bg-gray-700">

Dashboard Piutang

</a>

<div class="text-gray-400 text-sm mt-4 mb-2">
SYSTEM
</div>

<a href="{{ route('system.backup.page') }}"
class="block px-4 py-2 rounded hover:bg-gray-700
{{ request()->routeIs('system.*') ? 'bg-gray-700' : '' }}">
Backup Sistem
</a>
</nav>

</div>

<!-- CONTENT -->

<div class="flex-1 flex flex-col">

<!-- TOP NAVBAR -->

<div class="bg-white shadow p-4 flex justify-between">

<div>
Dashboard
</div>

<div>

<form method="POST" action="{{ route('logout') }}">
@csrf

<button class="text-red-500">
Logout
</button>

</form>

</div>

</div>

<!-- PAGE CONTENT -->

<div class="p-6 overflow-y-auto">

{{ $slot }}

</div>

</div>

</div>

</body>
</html>