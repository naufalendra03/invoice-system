<x-app-layout>

<div class="max-w-6xl mx-auto py-10">

<h2 class="text-xl font-bold mb-6">
Pembayaran
</h2>

<div class="bg-white shadow rounded p-6">

<table class="w-full border">

<tr class="bg-gray-100">

<th class="p-2">Invoice</th>
<th class="p-2">Tanggal</th>
<th class="p-2">Jumlah</th>
<th>Aksi</th>

</tr>

@foreach($payments as $payment)

<tr class="border-t">

<td class="p-2">
{{ $payment->sale->invoice_number }}
</td>

<td class="p-2">
{{ $payment->payment_date }}
</td>

<td class="p-2">
Rp {{ number_format($payment->amount) }}
</td>

<td>

<a href="{{ route('payments.detail',$payment->sale_id) }}"
class="bg-blue-500 text-white px-3 py-1 rounded">

Detail

</a>

</td>

</tr>

@endforeach

</table>

</div>

</div>

</x-app-layout>