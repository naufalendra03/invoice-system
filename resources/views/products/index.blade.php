<x-app-layout>

<div class="py-8">
<div class="max-w-7xl mx-auto px-4">

<div class="bg-white shadow rounded-lg p-6">

<!-- HEADER -->

<div class="flex justify-between items-center mb-6">

<h2 class="text-xl font-bold">
Products
</h2>

<a href="{{ route('products.create') }}"
class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">

+ Tambah Produk

</a>

</div>

<!-- SEARCH -->

<form method="GET" class="mb-4 flex gap-2">

<input type="text"
name="search"
value="{{ $search }}"
placeholder="Search product..."
class="border p-2 rounded w-64">

<button class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded">
Search
</button>

</form>

<!-- ALERT -->

@if(session('success'))
<div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
{{ session('success') }}
</div>
@endif

<!-- TABLE -->

<div class="overflow-x-auto">

<table class="w-full border border-gray-200 rounded">

<thead class="bg-gray-100">

<tr>
<th class="p-3 text-left">Produk</th>
<th class="p-3 text-left">Satuan</th>
<th class="p-3 text-left">Harga</th>
<th class="p-3 text-left">Action</th>
</tr>

</thead>

<tbody>

@forelse($products as $product)

<tr class="border-t hover:bg-gray-50">

<td class="p-3">
{{ $product->name }}
</td>

<td class="p-3">
<span class="bg-gray-200 px-2 py-1 rounded text-sm">
{{ $product->unit }}
</span>
</td>

<td class="p-3 font-semibold text-green-600">
Rp {{ number_format($product->price) }}
</td>

<td class="p-3">

<div class="flex gap-2">

<a href="{{ route('products.edit',$product->id) }}"
class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
Edit
</a>

<form action="{{ route('products.destroy',$product->id) }}" method="POST">
@csrf
@method('DELETE')

<button
onclick="return confirm('Yakin hapus produk ini?')"
class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
Delete
</button>

</form>

</div>

</td>

</tr>

@empty

<tr>
<td colspan="4" class="text-center p-4 text-gray-500">
Belum ada produk
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

<!-- PAGINATION -->

<!-- FOOTER -->
<div class="flex justify-between items-center mt-4">

<!-- INFO -->
<div class="text-sm text-gray-600">
Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results
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
{{ $products->links() }}
</div>

</div>
</div>
</div>

</x-app-layout>