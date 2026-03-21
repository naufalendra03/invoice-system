<!DOCTYPE html>
<html>
<head>

<title>Invoice {{ $sale->invoice_number }}</title>

<style>

body{
font-family:Arial;
background:#f5f5f5;
padding:40px;
}

.card{
background:white;
padding:30px;
max-width:800px;
margin:auto;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}

table{
width:100%;
border-collapse:collapse;
margin-top:20px;
}

th,td{
border:1px solid #ddd;
padding:8px;
}

th{
background:#f0f0f0;
}

.header{
display:flex;
justify-content:space-between;
margin-bottom:20px;
}

.status{
padding:5px 10px;
background:red;
color:white;
display:inline-block;
}

</style>

</head>

<body>

<div class="card">

<div class="header">

<div>

<h2>INVOICE</h2>

<b>{{ $sale->company->name }}</b><br>
{{ $sale->company->address }}

</div>

<div>

Invoice : {{ $sale->invoice_number }} <br>
Tanggal : {{ \Carbon\Carbon::parse($sale->date)->format('d M Y') }}

</div>

</div>


<h3>Customer</h3>

<b>{{ $sale->customer->name }}</b><br>
{{ $sale->customer->address }}


<table>

<tr>
<th>No</th>
<th>Produk</th>
<th>Qty</th>
<th>Harga</th>
<th>Subtotal</th>
</tr>

@foreach($sale->items as $key=>$item)

<tr>

<td>{{ $key+1 }}</td>
<td>{{ $item->product->name }}</td>
<td>{{ $item->qty }}</td>
<td>{{ number_format($item->price) }}</td>
<td>{{ number_format($item->subtotal) }}</td>

</tr>

@endforeach

</table>


<h3 style="text-align:right">

Total : Rp {{ number_format($sale->total) }}

</h3>


@if($remaining > 0)

<div class="status">

Sisa Tagihan : Rp {{ number_format($remaining) }}

</div>

@endif


<br><br>

<a href="{{ route('invoice.pdf',$sale->id) }}" class="btn btn-primary">

Download PDF

</a>

</div>

</body>
</html>