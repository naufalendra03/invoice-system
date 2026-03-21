<x-app-layout>

<div class="py-8">
<div class="max-w-7xl mx-auto px-4">

<div class="bg-white shadow rounded-lg p-6">

<!-- HEADER -->
<div class="mb-6">
<h2 class="text-xl font-bold">
Detail Piutang Invoice
</h2>
</div>


<!-- ========================= -->
<!-- INFO INVOICE -->
<!-- ========================= -->

<div class="grid grid-cols-2 gap-6 mb-8">

<div class="space-y-2">
<p><b>Invoice :</b> {{ $sale->invoice_number }}</p>
<p><b>No PO :</b> {{ $sale->po_number ?? '-' }}</p>
<p><b>Customer :</b> {{ $sale->customer->name }}</p>
</div>

<div class="space-y-2">
<p><b>Company :</b> {{ $sale->company->name }}</p>
<p><b>Tanggal Invoice :</b> {{ $sale->date }}</p>
<p><b>Jatuh Tempo :</b> {{ $sale->due_date ?? '-' }}</p>
</div>

</div>


<!-- ========================= -->
<!-- DETAIL BARANG -->
<!-- ========================= -->

<h3 class="font-semibold mb-3">
Detail Barang
</h3>

<div class="overflow-x-auto mb-8">

<table class="w-full border border-gray-200">

<thead class="bg-gray-100">

<tr>
<th class="p-3 text-left">No</th>
<th class="p-3 text-left">Produk</th>
<th class="p-3 text-left">Qty</th>
<th class="p-3 text-left">Harga</th>
<th class="p-3 text-left">Subtotal</th>
</tr>

</thead>

<tbody>

@foreach($sale->items as $item)

<tr class="border-t">

<td class="p-3">{{ $loop->iteration }}</td>
<td class="p-3">{{ $item->product->name }}</td>
<td class="p-3">{{ $item->qty }}</td>
<td class="p-3">Rp {{ number_format($item->price) }}</td>
<td class="p-3 font-semibold">
Rp {{ number_format($item->subtotal) }}
</td>

</tr>

@endforeach

</tbody>

</table>

</div>


<!-- ========================= -->
<!-- RINGKASAN PIUTANG -->
<!-- ========================= -->

<h3 class="font-semibold mb-3">
Ringkasan Piutang
</h3>

<div class="grid grid-cols-4 gap-6 mb-8">

<div>
<p class="text-gray-500">Total Invoice</p>
<p class="font-bold">
Rp {{ number_format($sale->total) }}
</p>
</div>

<div>
<p class="text-green-600">Sudah Dibayar</p>
<p class="font-bold text-green-600">
Rp {{ number_format($paid) }}
</p>
</div>

<div>
<p class="text-red-600">Sisa Hutang</p>
<p class="font-bold text-red-600">
Rp {{ number_format($remaining) }}
</p>
</div>

<div>
<p class="text-gray-500">Umur Piutang</p>

@if($aging > 0)

<p class="text-green-600 font-semibold">
{{ $aging }} Hari Lagi
</p>

@elseif($aging < 0)

<p class="text-red-600 font-semibold">
{{ abs($aging) }} Hari Terlambat
</p>

@else

<p class="text-yellow-600 font-semibold">
Hari Ini Jatuh Tempo
</p>

@endif

</div>

</div>


<!-- ========================= -->
<!-- RIWAYAT PEMBAYARAN -->
<!-- ========================= -->

<h3 class="font-semibold mb-3">
Riwayat Pembayaran
</h3>

<div class="overflow-x-auto">

<table class="w-full border border-gray-200">

<thead class="bg-gray-100">

<tr>
<th class="p-3 text-left">Tanggal</th>
<th class="p-3 text-left">Jumlah</th>
<th class="p-3 text-left">Catatan</th>
</tr>

</thead>

<tbody>

@forelse($sale->payments as $payment)

<tr class="border-t">

<td class="p-3">
{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}
</td>

<td class="p-3 text-green-600 font-semibold">
Rp {{ number_format($payment->amount) }}
</td>

<td class="p-3">
{{ $payment->notes ?: '-' }}
</td>

</tr>

@empty

<tr>
<td colspan="3" class="text-center p-4 text-gray-500">
Belum ada pembayaran
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

</div>
</div>
</div>

</x-app-layout>