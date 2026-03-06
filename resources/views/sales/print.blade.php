<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

<style>

body{
font-family: Arial, Helvetica, sans-serif;
font-size:11px;
}

table{
width:100%;
border-collapse: collapse;
}

th,td{
border:1px solid #000;
padding:4px;
}

.no-border td{
border:none;
}

.title{
text-align:center;
font-weight:bold;
font-size:16px;
margin-bottom:6px;
}

.center{
text-align:center;
}

.right{
text-align:right;
}

.section{
margin-bottom:35px;
}

.box{
border:1px solid #000;
padding:6px;
width:220px;
margin:auto;
text-align:center;
}

</style>

</head>

<body>

<!-- ================================================= -->
<!-- ==================== INVOICE ==================== -->
<!-- ================================================= -->

<div class="section">

<div class="title">INVOICE</div>

<table class="no-border">

<tr>

<td width="50%">

Tanggal : {{ date('d F Y',strtotime($sale->date)) }} <br>

No.Invoice : {{ $sale->invoice_number }} <br>

No.SJ : {{ $sale->surat_jalan_number }}

</td>

<td>

Kepada : <br>

<b>{{ $sale->customer->name }}</b><br>

{{ $sale->customer->address }}

</td>

</tr>

</table>

<br>

<table>

<tr class="center">

<th width="5%">No</th>
<th width="40%">Nama Barang</th>
<th width="15%">Satuan</th>
<th width="10%">Qty</th>
<th width="15%">Harga (Rp)</th>
<th width="15%">Jumlah</th>

</tr>

@foreach($sale->items as $key=>$item)

<tr>

<td class="center">{{ $key+1 }}</td>

<td>{{ $item->product->name }}</td>

<td class="center">{{ $item->product->unit }}</td>

<td class="center">{{ $item->qty }}</td>

<td class="right">{{ number_format($item->price) }}</td>

<td class="right">{{ number_format($item->subtotal) }}</td>

</tr>

@endforeach

</table>

<br>

<table class="no-border">

<tr>

<td width="50%">

Yang menerima,

<br><br><br>

________________________

<br>

Cap & Tanda tangan

</td>

<td width="50%" class="right">

Jumlah Rp. {{ number_format($sale->total) }}

<br><br>

Hormat Kami,

<br><br><br>

<div style="height:60px;"></div>

ADM. PENJUALAN

</td>

</tr>

</table>

<br>

<div class="box">

Rekening BCA <br>

182.000.4961 <br>

Bambang Parwanto

</div>

</div>

<hr>

<!-- ================================================= -->
<!-- ================= SURAT JALAN =================== -->
<!-- ================================================= -->

<div class="section">

<div class="title">SURAT JALAN</div>

<table class="no-border">

<tr>

<td width="50%">

Tanggal : {{ date('d F Y',strtotime($sale->date)) }} <br>

No.Surat Jalan : {{ $sale->surat_jalan_number }}

</td>

<td>

Kepada : <br>

<b>{{ $sale->customer->name }}</b><br>

{{ $sale->customer->address }}

</td>

</tr>

</table>

<br>

<table>

<tr class="center">

<th width="5%">No</th>
<th width="55%">Nama Barang</th>
<th width="20%">Satuan</th>
<th width="20%">Qty</th>

</tr>

@foreach($sale->items as $key=>$item)

<tr>

<td class="center">{{ $key+1 }}</td>

<td>{{ $item->product->name }}</td>

<td class="center">{{ $item->product->unit }}</td>

<td class="center">{{ $item->qty }}</td>

</tr>

@endforeach

</table>

<br><br>

<table class="no-border">

<tr>

<td width="40%">

Penerima

<br><br><br>

________________________

<br>

Cap & Tanda tangan

</td>

<td width="20%"></td>

<td width="40%" class="center">

Hormat Kami,

<br><br><br>

<div style="height:60px;"></div>

ADM. PENJUALAN

</td>

</tr>

</table>

<br>

<div style="border:1px solid #000;width:230px;padding:6px">

Perhatian : <br>

Barang tersebut harap diperiksa kembali

</div>

</div>

</body>
</html>