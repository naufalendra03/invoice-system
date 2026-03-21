<x-app-layout>

<div class="py-8">
<div class="max-w-7xl mx-auto px-4">

<div class="bg-white shadow rounded-lg p-6">

<!-- HEADER -->
<div class="flex justify-between items-center mb-6">

<h2 class="text-xl font-bold">
Invoices
</h2>

<a href="{{ route('sales.create') }}"
class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
+ Buat Invoice
</a>

</div>

<!-- SEARCH -->
<form method="GET" class="mb-4 flex gap-2">

<input type="text"
name="search"
value="{{ $search }}"
placeholder="Search invoice / customer / company..."
class="border p-2 rounded w-72">

<button class="bg-gray-800 text-white px-4 py-2 rounded">
Search
</button>

</form>

<!-- TABLE -->
<div class="overflow-x-auto">

<table class="w-full border">

<thead class="bg-gray-100">
<tr>
<th class="p-3 text-left">Invoice</th>
<th class="p-3 text-left">Customer</th>
<th class="p-3 text-left">Company</th>
<th class="p-3 text-left">Total</th>
<th class="p-3 text-left">Status</th>
<th class="p-3 text-left">Surat Jalan</th>
<th class="p-3 text-center">Action</th>
</tr>
</thead>

<tbody>

@forelse($sales as $sale)

<tr class="border-t hover:bg-gray-50">

<td class="p-3 font-semibold">
{{ $sale->invoice_number ?? '-' }}
</td>

<td class="p-3">
{{ $sale->customer->name ?? '-' }}
</td>

<td class="p-3">
{{ $sale->company->name ?? '-' }}
</td>

<td class="p-3 font-semibold text-green-600">
Rp {{ number_format($sale->total ?? 0) }}
</td>

<td class="p-3">

@if($sale->status == 'paid')
<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">PAID</span>

@elseif($sale->status == 'partial')
<span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-sm">PARTIAL</span>

@elseif($sale->status == 'overdue')
<span class="bg-red-500 text-white px-2 py-1 rounded text-sm">OVERDUE</span>

@else
<span class="bg-gray-200 px-2 py-1 rounded text-sm">UNPAID</span>
@endif

</td>

<td class="p-3">
{{ $sale->surat_jalan_number ?? '-' }}
</td>

<td class="p-3 text-center">
<a href="{{ route('sales.detail',$sale->id) }}"
class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-1 rounded text-sm">
Detail
</a>
</td>

</tr>

@empty

<tr>
<td colspan="7" class="p-6 text-center text-gray-500">
Belum ada invoice
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

<!-- FOOTER (INFO + PER PAGE) -->
<div class="flex justify-between items-center mt-4">

<!-- INFO -->
<div class="text-sm text-gray-600">
Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }} of {{ $sales->total() }} results
</div>

<!-- PER PAGE -->
<form method="GET" class="flex items-center gap-2">

<input type="hidden" name="search" value="{{ $search }}">

<span class="text-sm">Per page</span>

<select name="per_page"
onchange="this.form.submit()"
class="border rounded-lg px-4 py-2 w-28 focus:ring-2 focus:ring-blue-500">

<option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
<option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
<option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
<option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>

</select>

</form>

</div>

<!-- PAGINATION -->
<div class="mt-2">
{{ $sales->links() }}
</div>

</div>
</div>
</div>

</x-app-layout>