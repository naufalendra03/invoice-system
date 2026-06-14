<x-app-layout>

<div class="py-8">
<div class="max-w-7xl mx-auto px-4">

<div class="mb-6">
<h2 class="text-xl font-bold">
Laporan Piutang Customer
</h2>
</div>

<div class="bg-white shadow rounded-lg p-6">

<form method="GET" class="mb-4 flex flex-wrap gap-2 items-end">

<input type="text"
name="search"
value="{{ request('search') }}"
placeholder="Search customer / invoice..."
class="border p-2 rounded w-60">

<select name="status" class="border p-2 rounded">
<option value="">Semua Status</option>
<option value="paid" {{ request('status')=='paid'?'selected':'' }}>PAID</option>
<option value="partial" {{ request('status')=='partial'?'selected':'' }}>PARTIAL</option>
<option value="unpaid" {{ request('status')=='unpaid'?'selected':'' }}>UNPAID</option>
<option value="overdue" {{ request('status')=='overdue'?'selected':'' }}>OVERDUE</option>
</select>

<input type="date" name="date_from" value="{{ request('date_from') }}" class="border p-2 rounded">
<input type="date" name="date_to" value="{{ request('date_to') }}" class="border p-2 rounded">

<button class="bg-gray-800 text-white px-4 py-2 rounded">
Filter
</button>

<a href="{{ route('reports.piutang') }}"
class="bg-gray-300 px-4 py-2 rounded">
Reset
</a>

<a href="{{ route('reports.piutang.export', request()->query()) }}"
class="bg-green-600 text-white px-4 py-2 rounded">
Export Excel
</a>

</form>

@if(request()->anyFilled(['search','status','date_from','date_to']))
<div class="mb-3 text-sm text-gray-500">
Filter aktif:
@if(request('search')) Nama/Invoice: "{{ request('search') }}" @endif
@if(request('status')) | Status: {{ strtoupper(request('status')) }} @endif
@if(request('date_from')) | Dari: {{ request('date_from') }} @endif
@if(request('date_to')) | Sampai: {{ request('date_to') }} @endif
</div>
@endif

<div class="overflow-x-auto">

<table class="w-full border">

<thead class="bg-gray-100">
<tr>
<th class="p-3 text-left">Tanggal</th>
<th class="p-3 text-left">Invoice</th>
<th class="p-3 text-left">Customer</th>
<th class="p-3 text-left">Total</th>
<th class="p-3 text-left">Sudah Dibayar</th>
<th class="p-3 text-left">Sisa Hutang</th>
<th class="p-3 text-left">Rata-rata Bayar</th>
<th class="p-3 text-left">Status</th>
<th class="p-3 text-center">Aksi</th>
</tr>
</thead>

<tbody>

@php
$grandTotal = 0;
$grandRemaining = 0;
@endphp

@forelse($sales as $sale)

@php
$paid = $sale->payments->sum('amount');
$remaining = $sale->total - $paid;

$grandTotal += $sale->total;
$grandRemaining += $remaining;

$avgDay = $customerAverageDays[$sale->customer_id] ?? null;
@endphp

<tr class="border-t hover:bg-gray-50">

<td class="p-3 text-gray-500 text-sm">
{{ \Carbon\Carbon::parse($sale->date)->format('d M Y') }}
</td>

<td class="p-3 font-semibold">
{{ $sale->invoice_number }}
</td>

<td class="p-3">
{{ $sale->customer->name }}
</td>

<td class="p-3">
Rp {{ number_format($sale->total) }}
</td>

<td class="p-3 text-green-600 font-semibold">
Rp {{ number_format($paid) }}
</td>

<td class="p-3 text-red-600 font-semibold">
Rp {{ number_format($remaining) }}
</td>

<td class="p-3">
@if($avgDay !== null)
<span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm">
{{ $avgDay }} Hari
</span>
@else
<span class="text-gray-400 text-sm">Belum ada data</span>
@endif
</td>

<td class="p-3">
@if($sale->effective_status == 'paid')
<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">PAID</span>
@elseif($sale->effective_status == 'partial')
<span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-sm">PARTIAL</span>
@elseif($sale->effective_status == 'overdue')
<span class="bg-red-500 text-white px-2 py-1 rounded text-sm">OVERDUE</span>
@else
<span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-sm">UNPAID</span>
@endif
</td>

<td class="p-3 text-center">
<a href="{{ route('reports.piutang.detail',$sale->id) }}"
class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
Detail
</a>
</td>

</tr>

@empty

<tr>
<td colspan="9" class="text-center p-6 text-gray-500">
Tidak ada data piutang
</td>
</tr>

@endforelse

@if($sales->count())
<tr class="border-t bg-gray-100 font-bold">
<td colspan="3" class="p-3 text-right">TOTAL</td>

<td class="p-3 text-blue-700">
Rp {{ number_format($grandTotal) }}
</td>

<td></td>

<td class="p-3 text-red-700">
Rp {{ number_format($grandRemaining) }}
</td>

<td colspan="3"></td>
</tr>
@endif

</tbody>

</table>

</div>

<div class="flex justify-between items-center mt-4">

<div class="text-sm text-gray-600">
Showing {{ $sales->firstItem() ?? 0 }} to {{ $sales->lastItem() ?? 0 }} of {{ $sales->total() }} results
</div>

<form method="GET" class="flex items-center gap-2">

<input type="hidden" name="search" value="{{ request('search') }}">
<input type="hidden" name="status" value="{{ request('status') }}">
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