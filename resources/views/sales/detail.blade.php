<x-app-layout>

<div class="py-8">
<div class="max-w-7xl mx-auto px-4">

<div class="bg-white shadow rounded-lg p-6">

<!-- HEADER -->
<div class="mb-6 flex justify-between items-center">

<h2 class="text-xl font-bold">
Detail Invoice
</h2>

</div>


<!-- ===================== -->
<!-- INFORMASI INVOICE -->
<!-- ===================== -->

<div class="grid grid-cols-2 gap-6 mb-8">

<div class="space-y-2">
<p><b>No Invoice :</b> {{ $sale->invoice_number }}</p>
<p><b>No PO :</b> {{ $sale->po_number ?? '-' }}</p>
<p><b>Customer :</b> {{ $sale->customer->name }}</p>
<p><b>Tanggal Invoice :</b> {{ $sale->date }}</p>
</div>

<div class="space-y-2">
<p><b>No Surat Jalan :</b> {{ $sale->surat_jalan_number }}</p>

<p>
<b>Status :</b>

@if($sale->status == 'paid')
<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">PAID</span>

@elseif($sale->status == 'partial')
<span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-sm">PARTIAL</span>

@elseif($sale->status == 'overdue')
<span class="bg-red-500 text-white px-2 py-1 rounded text-sm">OVERDUE</span>

@else
<span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-sm">UNPAID</span>
@endif

</p>

<p><b>Company :</b> {{ $sale->company->name }}</p>
<p><b>Jatuh Tempo :</b> {{ $sale->due_date ?? '-' }}</p>
</div>

</div>


<!-- ===================== -->
<!-- DETAIL BARANG -->
<!-- ===================== -->

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
<td class="p-3 font-semibold">Rp {{ number_format($item->subtotal) }}</td>

</tr>

@endforeach

</tbody>

</table>

</div>


<!-- ===================== -->
<!-- RINGKASAN -->
<!-- ===================== -->

<h3 class="font-semibold mb-3">
Ringkasan Invoice
</h3>

<div class="grid grid-cols-3 gap-6 mb-8">

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

</div>


<!-- ===================== -->
<!-- RIWAYAT PEMBAYARAN -->
<!-- ===================== -->

<h3 class="font-semibold mb-3">
Riwayat Pembayaran
</h3>

<div class="overflow-x-auto mb-8">

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


<!-- ===================== -->
<!-- ACTION -->
<!-- ===================== -->

<div class="flex flex-wrap gap-3">

@if($sale->status != 'paid')

<a href="{{ route('sales.payment',$sale->id) }}"
class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
Bayar
</a>

@endif

<a href="{{ route('sales.print.invoice',$sale->id) }}"
class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
Print Nota
</a>

<a href="{{ route('sales.print.surat.jalan',$sale->id) }}"
class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded">
Print Surat Jalan
</a>

<a href="{{ route('sales.send.wa',$sale->id) }}"
class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded">
Kirim WA
</a>

</div>

</div>
</div>
</div>

</x-app-layout>