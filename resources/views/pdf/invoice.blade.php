<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

<style>

/* JARAK TEPI KERTAS */
@page{
    margin-top: 10px;
    margin-left: 28px;
    margin-right: 28px;
    margin-bottom: 20px;
}

body{
    font-family: Arial, Helvetica, sans-serif;
    font-size:12px;

    /* SEBELUMNYA 35px */
    margin-top:10px;
    margin-right:35px;
    margin-bottom:35px;
    margin-left:35px;
}


/* JUDUL */
.title{
    text-align:center;
    font-weight:bold;
    font-size:16px;

    /* AGAR LEBIH RAPAT KE ATAS */
    margin-top:0;
    margin-bottom:10px;
}
/* TABLE GLOBAL */
table{
    width:100%;
    border-collapse:collapse;
}

/* HEADER INFO */
.no-border td{
    border:none;
    padding:0;
    vertical-align:top;
    line-height:1.25;
}

/* TABEL BARANG */
.items{
    margin-top:8px;
}

/* HEADER */
.items th{
    border:1px solid #000;
    padding:4px 6px;
    text-align:center;
    font-weight:bold;
}

/* ISI */
.items td{
    padding:2px 6px;
    line-height:1.15;
}

/* HANYA GARIS VERTIKAL */
.col-no{
    border-left:1px solid #000;
    border-right:1px solid #000;
    text-align:center;
}

.col-barang{
    border-right:1px solid #000;
}

.col-satuan{
    border-right:1px solid #000;
    text-align:center;
}

.col-qty{
    border-right:1px solid #000;
    text-align:center;
}

.col-harga{
    border-right:1px solid #000;
    text-align:right;
}

.col-jumlah{
    border-right:1px solid #000;
    text-align:right;
}

/* TOTAL */
.total-row td{
    border:1px solid #000;
    padding:5px 6px;
    font-weight:bold;
}

.text-center{
    text-align:center;
}

.text-right{
    text-align:right;
}

/* TANDA TANGAN */
.signature{
    margin-top:55px;
}

.signature-table td{
    border:none;
    vertical-align:top;
    font-size:12px;
}

.left-sign{
    text-align:left;
}

.center-sign{
    text-align:center;
}

.right-sign{
    text-align:right;
}

.bank-box{
    border:1px solid #000;
    width:180px;
    text-align:center;
    padding:5px;
    margin:auto;
    font-size:11px;
}

.sign-space{
    height:65px;
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

<thead>

<tr class="text-center">

<th width="5%">No</th>
<th width="40%">Nama Barang</th>
<th width="10%">Satuan</th>
<th width="10%">Qty</th>
<th width="15%">Harga ( Rp )</th>
<th width="20%">Jumlah</th>

</tr>

</thead>

<tbody>

@foreach($sale->items as $key=>$item)

<tr>

<td class="col-no">
{{ $key+1 }}
</td>

<td class="col-barang">
{{ $item->product->name }}
</td>

<td class="col-satuan">
{{ $item->product->unit }}
</td>

<td class="col-qty">
{{ rtrim(rtrim(number_format($item->qty, 3, ',', ''), '0'), ',') }}
</td>

<td class="col-harga">
{{ number_format($item->price) }}
</td>

<td class="col-jumlah">
{{ number_format($item->subtotal) }}
</td>

</tr>

@endforeach


<tr class="total-row">

<td class="col-no"></td>

<td colspan="4" class="text-center">
Jumlah
</td>

<td class="text-right">
Rp. {{ number_format($sale->items->sum('subtotal')) }}
</td>

</tr>

</tbody>

</table>



<div class="signature">

<table class="signature-table">

<tr>

<td width="33%" class="left-sign">

Yang menerima,

<div class="sign-space"></div>

_________________________

<br>

Cap & Tanda tangan

</td>


<td width="33%" class="center-sign">

<div class="bank-box">

Rekening BCA : <br>

182 000 4961 <br>

Bambang Purwanto

</div>

</td>


<td width="33%" class="right-sign">

Hormat kami,

<div class="sign-space"></div>

Adm.Penjualan

</td>

</tr>

</table>

</div>

</body>
</html>