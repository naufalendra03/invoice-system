<x-app-layout>

<div class="py-8">
<div class="max-w-7xl mx-auto">

<div class="bg-white shadow rounded-lg p-6">

<div class="flex justify-between items-center mb-6">

<h2 class="text-xl font-bold">
Products
</h2>

<a href="{{ route('products.create') }}"
class="bg-blue-600 text-white px-4 py-2 rounded">

+ Tambah Produk

</a>

</div>

<form method="GET" class="mb-4">

<input type="text"
name="search"
value="{{ $search }}"
placeholder="Search product..."
class="border p-2 rounded w-64">

<button class="bg-gray-800 text-white px-4 py-2 rounded">
Search
</button>

</form>

@if(session('success'))

<div class="bg-green-100 p-3 mb-4 rounded">

{{ session('success') }}

</div>

@endif

<table class="w-full border">

<thead class="bg-gray-100">

<tr>

<th class="p-3">Produk</th>
<th class="p-3">Satuan</th>
<th class="p-3">Harga</th>
<th class="p-3">Stok</th>
<th class="p-3">Action</th>

</tr>

</thead>

<tbody>

@foreach($products as $product)

<tr class="border-t">

<td class="p-3">
{{ $product->name }}
</td>

<td class="p-3">
{{ $product->unit }}
</td>

<td class="p-3">
Rp {{ number_format($product->price) }}
</td>

<td class="p-3">
{{ $product->stock }}
</td>

<td class="p-3 flex gap-2">

<a href="{{ route('products.edit',$product->id) }}"
class="bg-yellow-500 text-white px-3 py-1 rounded">

Edit

</a>

<form action="{{ route('products.destroy',$product->id) }}" method="POST">

@csrf
@method('DELETE')

<button class="bg-red-500 text-white px-3 py-1 rounded">

Delete

</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

<div class="mt-4">

{{ $products->links() }}

</div>

</div>

</div>
</div>

</x-app-layout>