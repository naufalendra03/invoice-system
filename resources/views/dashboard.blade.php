<x-app-layout>

<div class="py-8">
<div class="max-w-7xl mx-auto px-4">

<!-- ============================= -->
<!-- NOTIFICATION BELL -->
<!-- ============================= -->

<div class="flex justify-end mb-4">

<div class="relative cursor-pointer" onclick="showNotification()">

<span class="text-2xl">🔔</span>

@if($dueSoon->count() > 0)

<span class="absolute -top-2 -right-3 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
{{ $dueSoon->count() }}
</span>

@endif

</div>

</div>


<!-- ============================= -->
<!-- NOTIFIKASI JATUH TEMPO -->
<!-- ============================= -->

<div class="bg-white shadow rounded p-4 mb-6">

<div class="flex justify-between items-center mb-3">

<h3 class="font-bold">
Notifikasi Jatuh Tempo
</h3>

<a href="{{ route('reports.piutang') }}" class="text-blue-600 text-sm">
Lainnya →
</a>

</div>

@if($dueSoon->count() > 0)

<ul>

@foreach($dueSoon as $sale)

<li class="border-b py-2">

⚠️ Invoice
<b>{{ $sale->invoice_number }}</b>

Customer :
{{ $sale->customer->name }}

Jatuh tempo :
<b>{{ \Carbon\Carbon::parse($sale->due_date)->format('d M Y') }}</b>

<a href="{{ route('sales.detail',$sale->id) }}"
class="text-blue-600 ml-2">
Lihat
</a>

</li>

@endforeach

</ul>

@else

<p class="text-gray-500">
Tidak ada invoice mendekati jatuh tempo
</p>

@endif

</div>



<!-- ============================= -->
<!-- STATISTIK -->
<!-- ============================= -->

<div class="grid grid-cols-4 gap-6 mb-8">

<div class="bg-white shadow rounded-lg p-6">
<div class="text-gray-500 text-sm">Companies</div>
<div class="text-3xl font-bold">{{ $companies }}</div>
</div>

<div class="bg-white shadow rounded-lg p-6">
<div class="text-gray-500 text-sm">Customers</div>
<div class="text-3xl font-bold">{{ $customers }}</div>
</div>

<div class="bg-white shadow rounded-lg p-6">
<div class="text-gray-500 text-sm">Products</div>
<div class="text-3xl font-bold">{{ $products }}</div>
</div>

<div class="bg-white shadow rounded-lg p-6">
<div class="text-gray-500 text-sm">Invoices</div>
<div class="text-3xl font-bold">{{ $sales }}</div>
</div>

</div>


<!-- ============================= -->
<!-- GRAFIK OMZET -->
<!-- ============================= -->

<div class="bg-white shadow rounded-lg p-6 mb-8">

<h2 class="text-lg font-bold mb-4">
Grafik Omzet
</h2>

<canvas id="salesChart" height="90"></canvas>

</div>



<!-- ============================= -->
<!-- INVOICE TERBARU -->
<!-- ============================= -->

<div class="bg-white shadow rounded-lg p-6 mb-8">

<div class="flex justify-between mb-4">

<h2 class="text-lg font-bold">
Invoice Terbaru
</h2>

<a href="{{ route('sales.index') }}"
class="text-blue-600 text-sm">
Lainnya →
</a>

</div>

<table class="w-full border border-gray-200">

<thead class="bg-gray-100">

<tr>
<th class="text-left p-2">Invoice</th>
<th class="text-left p-2">Customer</th>
<th class="text-left p-2">Total</th>
</tr>

</thead>

<tbody>

@forelse($latestSales as $sale)

<tr class="border-t">

<td class="p-2">
{{ $sale->invoice_number }}
</td>

<td class="p-2">
{{ $sale->customer->name ?? '-' }}
</td>

<td class="p-2">
Rp {{ number_format($sale->total ?? 0) }}
</td>

</tr>

@empty

<tr>
<td colspan="3" class="p-4 text-center text-gray-500">
Belum ada invoice
</td>
</tr>

@endforelse

</tbody>

</table>

</div>



<!-- ============================= -->
<!-- HUTANG AKAN JATUH TEMPO -->
<!-- ============================= -->

<div class="bg-yellow-50 border border-yellow-300 shadow rounded-lg p-6 mb-8">

<div class="flex justify-between mb-4">

<h2 class="text-lg font-bold text-yellow-700">
Hutang Akan Jatuh Tempo (H-3)
</h2>

<a href="{{ route('reports.piutang') }}"
class="text-blue-600 text-sm">
Lainnya →
</a>

</div>

<table class="w-full border border-yellow-200">

<thead class="bg-yellow-100">

<tr>
<th class="p-2 text-left">Invoice</th>
<th class="p-2 text-left">Customer</th>
<th class="p-2 text-left">Jatuh Tempo</th>
<th class="p-2 text-left">Total</th>
</tr>

</thead>

<tbody>

@forelse($dueSoon as $sale)

<tr class="border-t">

<td class="p-2">
{{ $sale->invoice_number }}
</td>

<td class="p-2">
{{ $sale->customer->name }}
</td>

<td class="p-2 text-yellow-700 font-semibold">
{{ \Carbon\Carbon::parse($sale->due_date)->format('d M Y') }}
</td>

<td class="p-2">
Rp {{ number_format($sale->total) }}
</td>

</tr>

@empty

<tr>
<td colspan="4" class="p-4 text-center text-gray-500">
Tidak ada invoice yang akan jatuh tempo
</td>
</tr>

@endforelse

</tbody>

</table>

</div>



<!-- ============================= -->
<!-- HUTANG JATUH TEMPO -->
<!-- ============================= -->

<div class="bg-white shadow rounded-lg p-6">

<div class="flex justify-between mb-4">

<h2 class="text-lg font-bold text-red-600">
Hutang Jatuh Tempo
</h2>

<a href="{{ route('reports.piutang') }}"
class="text-blue-600 text-sm">
Lainnya →
</a>

</div>

<table class="w-full border border-gray-200">

<thead class="bg-gray-100">

<tr>
<th class="p-2 text-left">Invoice</th>
<th class="p-2 text-left">Customer</th>
<th class="p-2 text-left">Jatuh Tempo</th>
<th class="p-2 text-left">Total</th>
</tr>

</thead>

<tbody>

@forelse($overdues as $sale)

<tr class="border-t">

<td class="p-2">
{{ $sale->invoice_number }}
</td>

<td class="p-2">
{{ $sale->customer->name }}
</td>

<td class="p-2 text-red-600 font-semibold">
{{ \Carbon\Carbon::parse($sale->due_date)->format('d M Y') }}
</td>

<td class="p-2">
Rp {{ number_format($sale->total) }}
</td>

</tr>

@empty

<tr>
<td colspan="4" class="p-4 text-center text-gray-500">
Tidak ada hutang jatuh tempo
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

</div>
</div>




<!-- ============================= -->
<!-- POPUP NOTIFICATION -->
<!-- ============================= -->
@if($overdues->count() > 0)

<div id="overduePopup"

style="
display:none;
position:fixed;
top:80px;
right:20px;
background:white;
border-left:6px solid red;
box-shadow:0 8px 20px rgba(0,0,0,0.2);
padding:20px;
width:320px;
border-radius:8px;
z-index:9999;
">

<div style="display:flex;justify-content:space-between">

<b style="color:red">🚨 Invoice Jatuh Tempo</b>

<button onclick="closeOverdue()" style="border:none;background:none;font-size:18px">
✕
</button>

</div>

<hr>

@foreach($overdues as $sale)

<div style="margin-bottom:10px">

<b style="color:red">{{ $sale->invoice_number }}</b><br>

{{ $sale->customer->name }}<br>

Jatuh tempo :
<b>{{ \Carbon\Carbon::parse($sale->due_date)->format('d M Y') }}</b>

<br>

<a href="{{ route('sales.detail',$sale->id) }}" class="text-blue-600 text-sm">
Lihat Detail
</a>

</div>

@endforeach

</div>

@endif

@if($dueSoon->count() > 0)

<div id="notificationPopup"

style="
display:none;
position:fixed;
top:80px;
right:20px;
background:white;
border-left:6px solid orange;
box-shadow:0 8px 20px rgba(0,0,0,0.2);
padding:20px;
width:320px;
border-radius:8px;
z-index:9999;
">

<div style="display:flex;justify-content:space-between">

<b>⚠️ Invoice Mendekati Jatuh Tempo</b>

<button onclick="closeNotification()" style="border:none;background:none;font-size:18px">
✕
</button>

</div>

<hr>

@foreach($dueSoon as $sale)

<div style="margin-bottom:10px">

<b>{{ $sale->invoice_number }}</b><br>

{{ $sale->customer->name }}<br>

Jatuh tempo :
<b>{{ \Carbon\Carbon::parse($sale->due_date)->format('d M Y') }}</b>

<br>

<a href="{{ route('sales.detail',$sale->id) }}" class="text-blue-600 text-sm">
Lihat Detail
</a>

</div>

@endforeach

</div>

@endif



<!-- ============================= -->
<!-- CHART JS -->
<!-- ============================= -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx = document.getElementById('salesChart');

new Chart(ctx, {
type: 'bar',
data: {
labels: {!! json_encode($months ?? []) !!},
datasets: [{
label: 'Omzet',
data: {!! json_encode($totals ?? []) !!},
backgroundColor: '#3b82f6',
borderRadius: 4
}]
},
options: {
responsive: true,
scales: {
y: {
beginAtZero: true
}
}
}
});

</script>



<!-- ============================= -->
<!-- POPUP SCRIPT -->
<!-- ============================= -->

<script>

function showNotification(){

let overdue = document.getElementById("overduePopup");
let soon = document.getElementById("notificationPopup");

// PRIORITAS: OVERDUE DULU
if(overdue){
overdue.style.display = "block";

setTimeout(()=>{
overdue.style.display = "none";

// setelah overdue hilang → tampilkan dueSoon
if(soon){
soon.style.display = "block";

setTimeout(()=>{
soon.style.display = "none";
},8000);
}

},8000);

}else if(soon){

soon.style.display = "block";

setTimeout(()=>{
soon.style.display = "none";
},8000);

}

}


function closeOverdue(){
let el = document.getElementById("overduePopup");
if(el) el.style.display="none";
}

function closeNotification(){
let el = document.getElementById("notificationPopup");
if(el) el.style.display="none";
}


// AUTO SHOW SAAT LOAD
document.addEventListener("DOMContentLoaded", function(){

showNotification();

// 🔊 SOUND KHUSUS OVERDUE
let overdue = document.getElementById("overduePopup");

if(overdue){
let audio = new Audio('https://www.soundjay.com/buttons/sounds/beep-07.mp3');
audio.play().catch(()=>{});
}

});

</script>

</x-app-layout>