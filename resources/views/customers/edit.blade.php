<x-app-layout>

<div class="py-8">
<div class="max-w-xl mx-auto">

<div class="bg-white shadow p-6 rounded">

<h2 class="text-xl font-bold mb-6">
Edit Customer
</h2>

<form action="{{ route('customers.update',$customer->id) }}" method="POST">

@csrf
@method('PUT')

<div class="mb-4">

<label>Nama</label>

<input type="text" name="name"
value="{{ $customer->name }}"
class="w-full border p-2 rounded">

</div>

<div class="mb-4">

<label>Alamat</label>

<textarea name="address"
class="w-full border p-2 rounded">

{{ $customer->address }}

</textarea>

</div>

<div class="mb-4">

<label>Telepon</label>

<input type="text" name="phone"
value="{{ $customer->phone }}"
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