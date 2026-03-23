<x-app-layout>

<div class="max-w-7xl mx-auto py-10">

<h2 class="text-xl font-bold mb-6">
Dashboard Omset
</h2>

<!-- FILTER PERIODE -->

<div class="bg-white shadow rounded p-6 mb-6">

<h3 class="font-bold mb-4">
Periode Laporan
</h3>

<!-- FILTER DASHBOARD -->

<form method="GET" action="{{ route('reports.dashboard.piutang') }}">

<div class="flex gap-3 items-center mb-3">

<input
type="date"
name="start_date"
value="{{ $start }}"
class="border p-2 rounded">

<input
type="date"
name="end_date"
value="{{ $end }}"
class="border p-2 rounded">

<button
class="bg-blue-600 text-white px-4 py-2 rounded">

Filter

</button>

</div>

</form>


<!-- KIRIM WA -->

<form method="POST" action="{{ route('reports.send.omset') }}">

@csrf

<input type="hidden" name="start_date" value="{{ $start }}">
<input type="hidden" name="end_date" value="{{ $end }}">

<button
class="bg-green-600 text-white px-4 py-2 rounded">

Kirim Laporan WA

</button>

</form>


<!-- SHORTCUT -->

<div class="flex gap-2 mt-4">

<a href="{{ route('reports.dashboard.piutang',[
'start_date'=>date('Y-m-d'),
'end_date'=>date('Y-m-d')
]) }}"
class="bg-gray-600 text-white px-3 py-2 rounded text-sm">

Hari Ini

</a>

<a href="{{ route('reports.dashboard.piutang',[
'start_date'=>date('Y-m-01'),
'end_date'=>date('Y-m-d')
]) }}"
class="bg-green-600 text-white px-3 py-2 rounded text-sm">

Bulan Ini

</a>

</div>

</div>

<!-- CARD STATISTIC -->

<div class="grid grid-cols-4 gap-6">

<div class="bg-white shadow rounded p-6">
<p class="text-gray-500">Total Piutang</p>
<h2 class="text-2xl font-bold text-red-600">
Rp {{ number_format($totalPiutang) }}
</h2>
</div>

<div class="bg-white shadow rounded p-6">
<p class="text-gray-500">Invoice Belum Lunas</p>
<h2 class="text-2xl font-bold">
{{ $totalInvoice }}
</h2>
</div>

<div class="bg-white shadow rounded p-6">
<p class="text-gray-500">Customer Berhutang</p>
<h2 class="text-2xl font-bold">
{{ $totalCustomer }}
</h2>
</div>

<div class="bg-white shadow rounded p-6">
<p class="text-gray-500">Omset Periode</p>
<h2 class="text-2xl font-bold text-green-600">
Rp {{ number_format($omset) }}
</h2>
</div>

</div>


<!-- GRAFIK OMSET -->

<div class="bg-white shadow rounded p-6 mt-6">

<h3 class="font-bold mb-4">
Grafik Omset
</h3>

<canvas id="omsetChart" height="100"></canvas>

</div>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx = document.getElementById('omsetChart');

new Chart(ctx, {

type: 'line',

data: {

labels: {!! json_encode($labels) !!},

datasets: [{

label: 'Omset',

data: {!! json_encode($data) !!},

borderColor: '#16a34a',

backgroundColor: 'rgba(34,197,94,0.2)',

fill: true,

tension: 0.4

}]

},

options: {

responsive:true,

plugins:{
legend:{display:true}
},

scales:{
y:{
beginAtZero:true
}
}

}

});

</script>

</x-app-layout>