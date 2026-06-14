<x-app-layout>

<div class="py-8">
<div class="max-w-5xl mx-auto">

<div class="bg-white p-6 rounded shadow">

<h2 class="text-xl font-bold mb-6">
Edit Invoice {{ $sale->invoice_number }}
</h2>

<form action="{{ route('sales.update',$sale->id) }}" method="POST">
@csrf
@method('PUT')

<table class="w-full border" id="table">

<thead class="bg-gray-100">
<tr>
<th class="p-2">Produk</th>
<th class="p-2">Harga</th>
<th class="p-2">Qty</th>
<th class="p-2">Subtotal</th>
<th class="p-2 text-center">Aksi</th>
</tr>
</thead>

<tbody>

@foreach($sale->items as $item)

<tr>
<td>
<select name="product_id[]" class="product border p-2 w-full">
@foreach($products as $product)
<option value="{{ $product->id }}"
data-price="{{ $product->price }}"
{{ $product->id == $item->product_id ? 'selected' : '' }}>
{{ $product->name }}
</option>
@endforeach
</select>
</td>

<td>
<input type="text" name="price[]" value="{{ $item->price }}" class="price border p-2 w-full">
</td>

<td>
<input type="text"
name="qty[]"
value="{{ rtrim(rtrim(number_format($item->qty, 3, ',', ''), '0'), ',') }}"
class="qty border p-2 w-full"
inputmode="decimal"
placeholder="Contoh: 1,5">
</td>

<td>
<input type="text" name="subtotal[]" value="{{ $item->subtotal }}" class="subtotal border p-2 w-full" readonly>
</td>

<td class="text-center">
<button type="button" class="remove bg-red-500 text-white px-2 py-1 rounded">X</button>
</td>
</tr>

@endforeach

</tbody>
</table>

<button type="button" id="addRow"
class="bg-green-600 text-white px-4 py-2 mt-4 rounded">
+ Tambah Barang
</button>

<!-- ONGKIR -->
<div class="mt-6 max-w-xs">
<label class="block mb-1 font-semibold">Ongkir</label>
<input type="text" name="ongkir" id="ongkir" value="{{ $sale->ongkir ?? 0 }}" class="border p-2 w-full rounded">
<p class="text-sm text-gray-500 mt-1">
Ongkir hanya untuk laporan Excel, tidak tercetak di invoice.
</p>
</div>

<div class="mt-6 text-right">
<h2 class="text-xl font-bold">
Total : Rp <span id="grandTotal">{{ number_format($sale->total) }}</span>
</h2>
<input type="hidden" name="total" id="totalInput" value="{{ $sale->total }}">
</div>

<button class="bg-blue-600 text-white px-6 py-2 mt-4 rounded">
Update Invoice
</button>

<a href="{{ route('sales.detail',$sale->id) }}"
class="bg-gray-500 text-white px-4 py-2 rounded ml-2">
Batal
</a>

</form>

</div>
</div>
</div>

<script>
function formatRupiah(angka) {
    return Number(angka).toLocaleString('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 3
    });
}

function parseNumber(value) {
    if (!value) return 0;

    value = String(value).trim();

    if (value.includes(',')) {
        value = value.replace(/\./g, '');
        value = value.replace(',', '.');
    }

    return parseFloat(value) || 0;
}

function calculateTotal() {
    let totalBarang = 0;

    document.querySelectorAll(".subtotal").forEach(el => {
        totalBarang += parseNumber(el.value);
    });

    let ongkir = parseNumber(document.getElementById("ongkir").value);

    let grandTotal = totalBarang + ongkir;

    document.getElementById("grandTotal").innerText = formatRupiah(grandTotal);
    document.getElementById("totalInput").value = grandTotal;
}

function hitung(row) {
    let price = parseNumber(row.querySelector(".price").value);
    let qty = parseNumber(row.querySelector(".qty").value);

    let subtotal = price * qty;

    row.querySelector(".subtotal").value = subtotal;

    calculateTotal();
}

function setHarga(row) {
    let select = row.querySelector(".product");
    let selected = select.selectedOptions[0];

    let price = selected.getAttribute("data-price") || 0;

    row.querySelector(".price").value = price;

    hitung(row);
}

document.addEventListener("change", function(e) {
    if (e.target.classList.contains("product")) {
        let row = e.target.closest("tr");
        setHarga(row);
    }
});

document.addEventListener("input", function(e) {
    if (e.target.classList.contains("qty") || e.target.classList.contains("price")) {
        let row = e.target.closest("tr");
        hitung(row);
    }

    if (e.target.name === "ongkir") {
        calculateTotal();
    }
});

document.getElementById("addRow").addEventListener("click", function() {
    let table = document.querySelector("#table tbody");
    let firstRow = table.querySelector("tr");
    let clone = firstRow.cloneNode(true);

    clone.querySelectorAll("input").forEach(i => i.value = "");

    clone.querySelector(".qty").value = "1";

    let select = clone.querySelector(".product");
    select.selectedIndex = 0;

    table.appendChild(clone);

    setHarga(clone);
});

document.addEventListener("click", function(e) {
    if (e.target.classList.contains("remove")) {
        let rows = document.querySelectorAll("#table tbody tr");

        if (rows.length > 1) {
            e.target.closest("tr").remove();
        }

        calculateTotal();
    }
});

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll("#table tbody tr").forEach(row => {
        hitung(row);
    });

    calculateTotal();
});
</script>

</x-app-layout>