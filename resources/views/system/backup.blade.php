<x-app-layout>

<div class="max-w-4xl mx-auto py-10">

<h2 class="text-2xl font-bold mb-6">
Backup & Restore Sistem
</h2>

<!-- BACKUP -->

<div class="bg-white shadow rounded-lg p-6 mb-6">

<h3 class="text-lg font-semibold mb-3">
Backup Data
</h3>

<p class="text-gray-600 mb-4">
Download backup database dan file invoice sistem.
</p>

<a href="{{ route('system.backup') }}"
class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">

Download Backup

</a>

</div>


<!-- RESTORE -->

<div class="bg-white shadow rounded-lg p-6">

<h3 class="text-lg font-semibold mb-3">
Restore Data
</h3>

<p class="text-gray-600 mb-4">
Upload file backup (.zip) untuk memulihkan data sistem.
</p>

<form action="{{ route('system.restore') }}"
method="POST"
enctype="multipart/form-data">

@csrf

<input type="file"
name="backup"
required
class="border p-2 rounded mb-4 w-full">

<button type="submit"
class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">

Restore Backup

</button>

</form>

</div>

</div>

</x-app-layout>