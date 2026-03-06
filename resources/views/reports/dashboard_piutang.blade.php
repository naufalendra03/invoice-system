<x-app-layout>

<div class="max-w-7xl mx-auto py-10">

<h2 class="text-xl font-bold mb-6">
Dashboard Piutang
</h2>

<div class="grid grid-cols-4 gap-6">

<!-- Total Piutang -->
<div class="bg-white shadow rounded p-6">

<p class="text-gray-500">
Total Piutang
</p>

<h2 class="text-2xl font-bold text-red-600">

Rp {{ number_format($totalPiutang) }}

</h2>

</div>


<!-- Total Invoice -->
<div class="bg-white shadow rounded p-6">

<p class="text-gray-500">
Invoice Belum Lunas
</p>

<h2 class="text-2xl font-bold">

{{ $totalInvoice }}

</h2>

</div>


<!-- Customer Berhutang -->
<div class="bg-white shadow rounded p-6">

<p class="text-gray-500">
Customer Berhutang
</p>

<h2 class="text-2xl font-bold">

{{ $totalCustomer }}

</h2>

</div>


<!-- Omset Bulan Ini -->
<div class="bg-white shadow rounded p-6">

<p class="text-gray-500">
Omset Bulan Ini
</p>

<h2 class="text-2xl font-bold text-green-600">

Rp {{ number_format($omset) }}

</h2>

</div>

</div>

</div>

</x-app-layout>