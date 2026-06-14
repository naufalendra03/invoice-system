<x-app-layout>

<div class="py-8">
<div class="max-w-7xl mx-auto px-4">

<h2 class="text-xl font-bold mb-6">
Kartu Piutang / History Barang Customer
</h2>

<div class="bg-white shadow rounded-lg p-6">

<form method="GET" class="mb-4 flex flex-wrap gap-2 items-end">

<input type="text"
name="search"
value="{{ request('search') }}"
placeholder="Search customer / invoice / barang..."
class="border p-2 rounded w-72">

<input type="date"
name="date_from"
value="{{ request('date_from') }}"
class="border p-2 rounded">

<input type="date"
name="date_to"
value="{{ request('date_to') }}"
class="border p-2 rounded">

<button class="bg-gray-800 text-white px-4 py-2 rounded">
Filter
</button>

<a href="{{ route('reports.kartu-piutang') }}"
class="bg-gray-300 px-4 py-2 rounded">
Reset
</a>

<a href="{{ route('reports.kartu-piutang.export', request()->query()) }}"
class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
Export Excel
</a>

</form>

@if(request()->anyFilled(['search','date_from','date_to']))
<div class="mb-3 text-sm text-gray-500">
Filter aktif:
@if(request('search')) Search: "{{ request('search') }}" @endif
@if(request('date_from')) | Dari: {{ request('date_from') }} @endif
@if(request('date_to')) | Sampai: {{ request('date_to') }} @endif
</div>
@endif

<div class="overflow-x-auto">

<table class="w-full border text-sm">

<thead class="bg-gray-100">
<tr>
<th class="p-2 border">No</th>
<th class="p-2 border">Tanggal</th>
<th class="p-2 border">No Invoice</th>
<th class="p-2 border">No SJ</th>
<th class="p-2 border">No PO</th>
<th class="p-2 border">Customer</th>
<th class="p-2 border">Nama & Uraian Barang</th>
<th class="p-2 border">Satuan</th>
<th class="p-2 border">Banyaknya</th>
<th class="p-2 border">Harga @Rp</th>
<th class="p-2 border">Jumlah</th>
<th class="p-2 border">Total</th>
<th class="p-2 border">Ongkir</th>
</tr>
</thead>

<tbody>

@php $no = $sales->firstItem() ?? 1; @endphp

@forelse($sales as $sale)

@foreach($sale->items as $index => $item)

<tr class="hover:bg-gray-50">

@if($index == 0)
<td class="p-2 border text-center" rowspan="{{ $sale->items->count() }}">
{{ $no++ }}
</td>

<td class="p-2 border" rowspan="{{ $sale->items->count() }}">
{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}
</td>

<td class="p-2 border text-center" rowspan="{{ $sale->items->count() }}">
{{ str_replace('INV-', '', $sale->invoice_number) }}
</td>

<td class="p-2 border" rowspan="{{ $sale->items->count() }}">
{{ $sale->surat_jalan_number ?? '-' }}
</td>

<td class="p-2 border" rowspan="{{ $sale->items->count() }}">
{{ $sale->po_number ?? '-' }}
</td>

<td class="p-2 border" rowspan="{{ $sale->items->count() }}">
{{ $sale->customer->name ?? '-' }}
</td>
@endif

<td class="p-2 border">
{{ $item->product->name ?? '-' }}
</td>

<td class="p-2 border text-center">
{{ $item->product->unit ?? '-' }}
</td>

<td class="p-2 border text-center">
{{ rtrim(rtrim(number_format($item->qty, 3, ',', ''), '0'), ',') }}
</td>

<td class="p-2 border text-right">
{{ number_format($item->price) }}
</td>

<td class="p-2 border text-right">
{{ number_format($item->subtotal) }}
</td>

@if($index == 0)
<td class="p-2 border text-right font-bold" rowspan="{{ $sale->items->count() }}">
{{ number_format($sale->items->sum('subtotal')) }}
</td>

<td class="p-2 border text-right font-bold" rowspan="{{ $sale->items->count() }}">
{{ number_format($sale->ongkir ?? 0) }}
</td>
@endif

</tr>

@endforeach

@empty

<tr>
<td colspan="13" class="p-6 text-center text-gray-500">
Belum ada data history barang
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

<div class="flex justify-between items-center mt-4">

<div class="text-sm text-gray-600">
Showing {{ $sales->firstItem() ?? 0 }} to {{ $sales->lastItem() ?? 0 }} of {{ $sales->total() }} results
</div>

<form method="GET" class="flex items-center gap-2">

<input type="hidden" name="search" value="{{ request('search') }}">
<input type="hidden" name="date_from" value="{{ request('date_from') }}">
<input type="hidden" name="date_to" value="{{ request('date_to') }}">

<span class="text-sm">Per page</span>

<select name="per_page"
onchange="this.form.submit()"
class="border rounded-lg px-4 py-2 w-28">

<option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
<option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
<option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
<option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>

</select>

</form>

</div>

<div class="mt-2">
{{ $sales->withQueryString()->links() }}
</div>

</div>
</div>
</div>

</x-app-layout>