<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

<style>

body{
font-family: Arial, Helvetica, sans-serif;
font-size:12px;
margin:35px;
}

.title{
text-align:center;
font-weight:bold;
font-size:16px;
margin-bottom:10px;
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
margin-top:10px;
}

.total{
margin-top:5px;
text-align:right;
font-weight:bold;
}

.signature{
margin-top:60px;
}

.bank-box{
border:1px solid #000;
width:180px;
text-align:center;
padding:5px;
margin:auto;
font-size:11px;
}

.stamp-box{
border:1px solid #000;
padding:8px;
width:220px;
text-align:center;
font-weight:bold;
margin:auto;
}


</style>

</head>

<body>

<div class="title">INVOICE</div>

<table class="no-border">

<tr>

<td width="50%">

Tanggal : {{ \Carbon\Carbon::parse($sale->date)->locale('id')->translatedFormat('d F Y') }} <br>

No.Invoice : {{ $sale->invoice_number }} <br>

No. SJ : {{ $sale->surat_jalan_number }} <br>

No. PO : {{ $sale->po_number ?? '-' }}

</td>


<td width="50%">

Kepada : <br>

<b>{{ $sale->customer->name }}</b><br>

{{ $sale->customer->address }}

</td>

</tr>

</table>



<table class="items">

<tr class="text-center">

<th width="5%">No</th>
<th width="40%">Nama Barang</th>
<th width="10%">Satuan</th>
<th width="10%">Qty</th>
<th width="15%">Harga ( Rp )</th>
<th width="20%">Jumlah</th>

</tr>

@foreach($sale->items as $key=>$item)

<tr>

<td class="text-center">
{{ $key+1 }}
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

<td class="text-right">
{{ number_format($item->price) }} /KG
</td>

<td class="text-right">
{{ number_format($item->subtotal) }}
</td>

</tr>

@endforeach


<tr>

<td colspan="5" style="text-align:center;font-weight:bold">
Jumlah
</td>

<td style="text-align:right;font-weight:bold">
Rp. {{ number_format($sale->total) }}
</td>

</tr>

</table>



<div class="signature">

<table class="no-border">

<tr>

<td width="33%" class="text-center">

Yang menerima,

<br><br><br><br>

_________________________

<br>

Cap & Tanda tangan

</td>


<td width="33%" class="text-center">

<div style="
border:1px solid #000;
width:180px;
padding:6px;
margin:auto;
">

Rekening BCA : <br>

182 000 4961 <br>

Bambang Purwanto

</div>

</td>


<td width="33%" class="text-center">

Hormat kami,

<br><br><br><br>

<br>

Adm.Penjualan

</td>

</tr>

</table>

</div>

</body>
</html>