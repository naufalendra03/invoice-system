<x-app-layout>

<div class="py-8">
<div class="max-w-7xl mx-auto">

<div class="bg-white shadow rounded-lg p-6">

<div class="flex justify-between mb-6">

<h2 class="text-xl font-bold">
Invoices
</h2>

<a href="{{ route('sales.create') }}"
class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">

+ Buat Invoice

</a>

</div>

<table class="w-full border border-gray-200">

<thead class="bg-gray-100">

<tr>

<th class="p-3 text-left">Invoice</th>
<th class="p-3 text-left">Customer</th>
<th class="p-3 text-left">Company</th>
<th class="p-3 text-left">Total</th>
<th class="p-3 text-left">Status</th>
<th class="p-3 text-left">No Invoice</th>
<th class="p-3 text-left">Surat Jalan</th>
<th class="p-3 text-center">Action</th>

</tr>

</thead>

<tbody>

@foreach($sales as $sale)

<tr class="border-t hover:bg-gray-50">

<td class="p-3">
{{ $sale->invoice_number ?? '-' }}
</td>

<td class="p-3">
{{ $sale->customer->name ?? '-' }}
</td>

<td class="p-3">
{{ $sale->company->name ?? '-' }}
</td>

<td class="p-3">
Rp {{ number_format($sale->total ?? 0) }}
</td>


<!-- STATUS -->
<td class="p-3">

@if($sale->status == 'paid')

<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">
PAID
</span>

@elseif($sale->status == 'partial')

<span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-sm">
PARTIAL
</span>

@elseif($sale->status == 'overdue')

<span class="bg-red-500 text-white px-2 py-1 rounded text-sm">
OVERDUE
</span>

@else

<span class="bg-gray-200 px-2 py-1 rounded text-sm">
UNPAID
</span>

@endif

</td>


<td class="p-3">
{{ $sale->invoice_number }}
</td>

<td class="p-3">
{{ $sale->surat_jalan_number }}
</td>


<!-- ACTION -->
<td class="p-3 flex gap-2 justify-center">

@if($sale->status != 'paid')

<a href="{{ route('sales.payment',$sale->id) }}"
class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">

Bayar

</a>

@endif

<a href="{{ route('sales.print',$sale->id) }}"
class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">

Print

</a>

</td>

</tr>

@endforeach

</tbody>

</table>

<div class="mt-6">

{{ $sales->links() }}

</div>

</div>

</div>
</div>

</x-app-layout>