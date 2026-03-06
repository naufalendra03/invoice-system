<x-app-layout>

<div class="py-8">
<div class="max-w-3xl mx-auto">

<div class="bg-white shadow rounded-lg p-6">

<h2 class="text-xl font-bold mb-6">Tambah Company</h2>

<form action="{{ route('companies.store') }}" method="POST">

@csrf

<div class="mb-4">
<label class="block text-gray-600">Nama</label>
<input type="text" name="name"
class="w-full border rounded p-2">
</div>

<div class="mb-4">
<label class="block text-gray-600">Kode</label>
<input type="text" name="code"
class="w-full border rounded p-2">
</div>

<div class="mb-4">
<label class="block text-gray-600">Alamat</label>
<textarea name="address"
class="w-full border rounded p-2"></textarea>
</div>

<div class="mb-4">
<label class="block text-gray-600">Telepon</label>
<input type="text" name="phone"
class="w-full border rounded p-2">
</div>

<div class="mb-4">
<label class="block text-gray-600">Rekening</label>
<input type="text" name="bank_account"
class="w-full border rounded p-2">
</div>

<button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">

Simpan

</button>

</form>

</div>

</div>
</div>

</x-app-layout>