<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

<style>

body{
font-family: Arial, Helvetica, sans-serif;
font-size:12px;
margin:40px;
}

.header-table td{
border:none;
vertical-align:top;
}

.title{
font-weight:bold;
font-size:16px;
}

table{
width:100%;
border-collapse:collapse;
}

th,td{
border:1px solid #000;
padding:6px;
}

.no-border td{
border:none;
}

.text-center{
text-align:center;
}

.text-right{
text-align:right;
}

.items{
margin-top:15px;
}

.signature{
margin-top:60px;
}

.notice{
border:1px solid #000;
padding:6px;
width:160px;
text-align:center;
font-size:11px;
}

</style>

</head>


<body>

<table class="header-table" width="100%">

<tr>

<td width="50%">

<div class="title">SURAT JALAN</div>

<br>

NO.SURAT JALAN : {{ $sale->surat_jalan_number }} <br>
NO.PO : -

</td>


<td width="50%">

Tanggal : {{ \Carbon\Carbon::parse($sale->date)->locale('id')->translatedFormat('d F Y') }}

<br><br>

Kepada : <br>

<b>{{ $sale->customer->name }}</b><br>

{{ $sale->customer->address }}

</td>

</tr>

</table>



<br>

Dengan ini kami kirimkan barang-barang tersebut di bawah ini :

<br>


<table class="items">

<tr class="text-center">

<th width="5%">No</th>
<th width="60%">Nama Barang</th>
<th width="15%">Satuan</th>
<th width="20%">Qty</th>

</tr>

@foreach($sale->items as $key => $item)

<tr>

<td class="text-center">
{{ $key + 1 }}
</td>

<td>
{{ $item->product->name }}
</td>

<td class="text-center">
{{ $item->product->unit }}
</td>

<td class="text-center">
{{ $item->qty }}
</td>

</tr>

@endforeach

</table>



<div class="signature">

<table class="no-border">

<tr>

<td width="33%" class="text-center">

Penerima

<br><br><br><br>

_______________________

<br>

Cap & tanda tangan

</td>


<td width="33%" class="text-center">

<div class="notice">

Perhatian : <br>

Barang tersebut harap <br>

diperiksa kembali

</div>

</td>


<td width="33%" class="text-center">

Hormat kami,

<br><br><br><br>

<br>

Adm Penjualan

</td>

</tr>

</table>

</div>


</body>
</html>