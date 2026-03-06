<x-app-layout>

<div class="py-8">
<div class="max-w-7xl mx-auto">

<div class="bg-white shadow rounded-lg p-6">

<div class="flex justify-between items-center mb-6">

<h2 class="text-xl font-bold">
Customers
</h2>

<a href="{{ route('customers.create') }}"
class="bg-blue-600 text-white px-4 py-2 rounded">

+ Tambah Customer

</a>

</div>


<form method="GET" class="mb-4">

<input type="text" name="search"
value="{{ $search }}"
placeholder="Search customer..."

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

<th class="p-3 text-left">Nama</th>
<th class="p-3 text-left">Alamat</th>
<th class="p-3 text-left">Telepon</th>
<th class="p-3 text-center">Action</th>

</tr>

</thead>

<tbody>

@foreach($customers as $customer)

<tr class="border-t">

<td class="p-3">
{{ $customer->name }}
</td>

<td class="p-3">
{{ $customer->address }}
</td>

<td class="p-3">
{{ $customer->phone }}
</td>

<td class="p-3 text-center flex justify-center gap-2">

<a href="{{ route('customers.edit',$customer->id) }}"
class="bg-yellow-500 text-white px-3 py-1 rounded">

Edit

</a>

<form action="{{ route('customers.destroy',$customer->id) }}" method="POST">

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

{{ $customers->links() }}

</div>

</div>

</div>
</div>

</x-app-layout>