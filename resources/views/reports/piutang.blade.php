<x-app-layout>

<div class="max-w-7xl mx-auto py-10">

<h2 class="text-xl font-bold mb-6">
Laporan Piutang Customer
</h2>

<div class="bg-white shadow rounded p-6">

<table class="w-full border">

<thead class="bg-gray-100">

<tr>

<th class="p-2">Invoice</th>
<th class="p-2">Customer</th>
<th class="p-2">Total</th>
<th class="p-2">Sudah Dibayar</th>
<th class="p-2">Sisa Hutang</th>
<th class="p-2">Status</th>

</tr>

</thead>

<tbody>

@foreach($sales as $sale)

@php

$paid = $sale->payments->sum('amount');

$remaining = $sale->total - $paid;

@endphp

<tr class="border-t">

<td class="p-2">
{{ $sale->invoice_number }}
</td>

<td class="p-2">
{{ $sale->customer->name }}
</td>

<td class="p-2">
Rp {{ number_format($sale->total) }}
</td>

<td class="p-2 text-green-600">
Rp {{ number_format($paid) }}
</td>

<td class="p-2 text-red-600">
Rp {{ number_format($remaining) }}
</td>

<td class="p-2">
{{ strtoupper($sale->status) }}
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

</x-app-layout>