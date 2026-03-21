<x-app-layout>

<div class="py-8">
<div class="max-w-xl mx-auto">

<div class="bg-white shadow p-6 rounded">

<h2 class="text-xl font-bold mb-6">

Edit Produk

</h2>

<form action="{{ route('products.update',$product->id) }}" method="POST">

@csrf
@method('PUT')

<div class="mb-4">

<label>Nama Produk</label>

<input type="text"
name="name"
value="{{ $product->name }}"
class="w-full border p-2 rounded">

</div>

<div class="mb-4">

<label>Satuan</label>

<input type="text"
name="unit"
value="{{ $product->unit }}"
class="w-full border p-2 rounded">

</div>

<div class="mb-4">

<label>Harga</label>

<input type="number"
name="price"
value="{{ $product->price }}"
class="w-full border p-2 rounded">

</div>


<button class="bg-blue-600 text-white px-4 py-2 rounded">

Update

</button>

</form>

</div>

</div>
</div>

</x-app-layout>