<x-app-layout>

<div class="py-8">
<div class="max-w-7xl mx-auto px-4">

<!-- ============================= -->
<!-- STATISTIK -->
<!-- ============================= -->

<div class="grid grid-cols-4 gap-6 mb-8">

<div class="bg-white shadow rounded-lg p-6">
<div class="text-gray-500 text-sm">Companies</div>
<div class="text-3xl font-bold">{{ $companies }}</div>
</div>

<div class="bg-white shadow rounded-lg p-6">
<div class="text-gray-500 text-sm">Customers</div>
<div class="text-3xl font-bold">{{ $customers }}</div>
</div>

<div class="bg-white shadow rounded-lg p-6">
<div class="text-gray-500 text-sm">Products</div>
<div class="text-3xl font-bold">{{ $products }}</div>
</div>

<div class="bg-white shadow rounded-lg p-6">
<div class="text-gray-500 text-sm">Invoices</div>
<div class="text-3xl font-bold">{{ $sales }}</div>
</div>

</div>


<!-- ============================= -->
<!-- GRAFIK OMZET -->
<!-- ============================= -->

<div class="bg-white shadow rounded-lg p-6 mb-8">

<h2 class="text-lg font-bold mb-4">
Grafik Omzet
</h2>

<canvas id="salesChart" height="90"></canvas>

</div>



<!-- ============================= -->
<!-- INVOICE TERBARU -->
<!-- ============================= -->

<div class="bg-white shadow rounded-lg p-6 mb-8">

<h2 class="text-lg font-bold mb-4">
Invoice Terbaru
</h2>

<table class="w-full border border-gray-200">

<thead class="bg-gray-100">
<tr>
<th class="text-left p-2">Invoice</th>
<th class="text-left p-2">Customer</th>
<th class="text-left p-2">Total</th>
</tr>
</thead>

<tbody>

@forelse($latestSales as $sale)

<tr class="border-t">

<td class="p-2">
{{ $sale->invoice_number }}
</td>

<td class="p-2">
{{ $sale->customer->name ?? '-' }}
</td>

<td class="p-2">
Rp {{ number_format($sale->total ?? 0) }}
</td>

</tr>

@empty

<tr>
<td colspan="3" class="p-4 text-center text-gray-500">
Belum ada invoice
</td>
</tr>

@endforelse

</tbody>

</table>

</div>



<!-- ============================= -->
<!-- HUTANG AKAN JATUH TEMPO H-3 -->
<!-- ============================= -->

<div class="bg-yellow-50 border border-yellow-300 shadow rounded-lg p-6 mb-8">

<h2 class="text-lg font-bold mb-4 text-yellow-700">
Hutang Akan Jatuh Tempo (H-3)
</h2>

<table class="w-full border border-yellow-200">

<thead class="bg-yellow-100">

<tr>
<th class="p-2 text-left">Invoice</th>
<th class="p-2 text-left">Customer</th>
<th class="p-2 text-left">Jatuh Tempo</th>
<th class="p-2 text-left">Total</th>
</tr>

</thead>

<tbody>

@forelse($dueSoon as $sale)

<tr class="border-t">

<td class="p-2">
{{ $sale->invoice_number }}
</td>

<td class="p-2">
{{ $sale->customer->name }}
</td>

<td class="p-2 text-yellow-700 font-semibold">
{{ \Carbon\Carbon::parse($sale->due_date)->format('d M Y') }}
</td>

<td class="p-2">
Rp {{ number_format($sale->total) }}
</td>

</tr>

@empty

<tr>
<td colspan="4" class="p-4 text-center text-gray-500">
Tidak ada invoice yang akan jatuh tempo
</td>
</tr>

@endforelse

</tbody>

</table>

</div>



<!-- ============================= -->
<!-- HUTANG JATUH TEMPO -->
<!-- ============================= -->

<div class="bg-white shadow rounded-lg p-6">

<h2 class="text-lg font-bold mb-4 text-red-600">
Hutang Jatuh Tempo
</h2>

<table class="w-full border border-gray-200">

<thead class="bg-gray-100">

<tr>
<th class="p-2 text-left">Invoice</th>
<th class="p-2 text-left">Customer</th>
<th class="p-2 text-left">Jatuh Tempo</th>
<th class="p-2 text-left">Total</th>
</tr>

</thead>

<tbody>

@forelse($overdues as $sale)

<tr class="border-t">

<td class="p-2">
{{ $sale->invoice_number }}
</td>

<td class="p-2">
{{ $sale->customer->name }}
</td>

<td class="p-2 text-red-600 font-semibold">
{{ \Carbon\Carbon::parse($sale->due_date)->format('d M Y') }}
</td>

<td class="p-2">
Rp {{ number_format($sale->total) }}
</td>

</tr>

@empty

<tr>
<td colspan="4" class="p-4 text-center text-gray-500">
Tidak ada hutang jatuh tempo
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

</div>
</div>



<!-- ============================= -->
<!-- CHART JS -->
<!-- ============================= -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx = document.getElementById('salesChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($months ?? []) !!},
        datasets: [{
            label: 'Omzet',
            data: {!! json_encode($totals ?? []) !!},
            backgroundColor: '#3b82f6',
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

</script>

</x-app-layout>