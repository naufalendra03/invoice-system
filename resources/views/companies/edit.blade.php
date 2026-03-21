<x-app-layout>

<div class="py-8">
<div class="max-w-7xl mx-auto px-4">

<div class="bg-white shadow rounded-lg p-6 max-w-2xl mx-auto">

<h2 class="text-xl font-bold mb-6">
Edit Company
</h2>

<form action="{{ route('companies.update',$company->id) }}" method="POST">

@csrf
@method('PUT')

<!-- NAMA -->
<div class="mb-4">
<label class="block mb-1 font-medium">Nama</label>
<input type="text" name="name"
value="{{ old('name',$company->name) }}"
class="w-full border p-2 rounded focus:ring focus:ring-blue-200"
required>
</div>

<!-- KODE -->
<div class="mb-4">
<label class="block mb-1 font-medium">Kode</label>
<input type="text" name="code"
value="{{ old('code',$company->code) }}"
class="w-full border p-2 rounded focus:ring focus:ring-blue-200"
required>
</div>

<!-- ALAMAT -->
<div class="mb-4">
<label class="block mb-1 font-medium">Alamat</label>
<textarea name="address"
class="w-full border p-2 rounded focus:ring focus:ring-blue-200"
rows="3">{{ old('address',$company->address) }}</textarea>
</div>

<!-- TELEPON -->
<div class="mb-4">
<label class="block mb-1 font-medium">Telepon</label>
<input type="text" name="phone"
value="{{ old('phone',$company->phone) }}"
class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
</div>

<!-- REKENING -->
<div class="mb-6">
<label class="block mb-1 font-medium">Rekening</label>
<input type="text" name="rekening"
value="{{ old('rekening',$company->rekening) }}"
class="w-full border p-2 rounded focus:ring focus:ring-blue-200"
placeholder="Opsional">
</div>

<!-- BUTTON -->
<div class="flex gap-3">

<button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
Update
</button>

<a href="{{ route('companies.index') }}"
class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
Batal
</a>

</div>

</form>

</div>
</div>
</div>

</x-app-layout>