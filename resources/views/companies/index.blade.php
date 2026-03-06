<x-app-layout>

<div class="py-8">
<div class="max-w-7xl mx-auto">

<div class="bg-white shadow rounded-lg p-6">

<div class="flex justify-between mb-6">

<h2 class="text-xl font-bold">Companies</h2>

<a href="{{ route('companies.create') }}"
class="bg-blue-600 text-white px-4 py-2 rounded">

+ Tambah Company

</a>

</div>

@if(session('success'))
<div class="bg-green-100 p-3 mb-4 rounded">
{{ session('success') }}
</div>
@endif

<table class="w-full border">

<thead class="bg-gray-100">

<tr>
<th class="p-3">ID</th>
<th class="p-3">Name</th>
<th class="p-3">Code</th>
<th class="p-3">Action</th>
</tr>

</thead>

<tbody>

@foreach($companies as $company)

<tr class="border-t">

<td class="p-3">{{ $company->id }}</td>
<td class="p-3">{{ $company->name }}</td>
<td class="p-3">{{ $company->code }}</td>

<td class="p-3 flex gap-2">

<a href="{{ route('companies.edit',$company->id) }}"
class="bg-yellow-500 text-white px-3 py-1 rounded">
Edit
</a>

<form action="{{ route('companies.destroy',$company->id) }}" method="POST">
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

</div>
</div>
</div>

</x-app-layout>