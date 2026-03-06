<x-app-layout>

<div class="max-w-3xl mx-auto py-10">

<h2 class="text-xl font-bold mb-6">
Pelunasan Invoice
</h2>

<div class="bg-white shadow p-6 rounded">

<p><b>Invoice :</b> {{ $sale->invoice_number }}</p>

<p><b>Customer :</b> {{ $sale->customer->name }}</p>

<p><b>Total :</b> Rp {{ number_format($sale->total) }}</p>

<p><b>Sudah Dibayar :</b> Rp {{ number_format($paid) }}</p>

<p class="text-red-600">
<b>Sisa Hutang :</b> Rp {{ number_format($remaining) }}
</p>

<form action="{{ route('sales.payment.store',$sale->id) }}" method="POST">

@csrf

<div class="mt-4">

<label>Jumlah Bayar</label>

<input type="number" name="amount"
class="border p-2 w-full">

</div>

<div class="mt-4">

<label>Catatan</label>

<input type="text" name="notes"
class="border p-2 w-full">

</div>

<button class="mt-4 bg-green-600 text-white px-4 py-2 rounded">

Simpan Pembayaran

</button>

</form>

</div>

</div>

</x-app-layout>