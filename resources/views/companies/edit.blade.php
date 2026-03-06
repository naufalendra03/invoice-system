<x-app-layout>

<div class="py-8">
<div class="max-w-xl mx-auto">

<div class="bg-white shadow p-6 rounded">

<h2 class="text-xl font-bold mb-6">Edit Company</h2>

<form action="{{ route('companies.update',$company->id) }}" method="POST">

@csrf
@method('PUT')

<div class="mb-4">
<label>Nama</label>
<input type="text" name="name"
value="{{ $company->name }}"
class="w-full border p-2 rounded">
</div>

<div class="mb-4">
<label>Kode</label>
<input type="text" name="code"
value="{{ $company->code }}"
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