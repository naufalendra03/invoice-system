<x-app-layout>

<div class="py-8">
<div class="max-w-7xl mx-auto px-4">

<div class="bg-white shadow rounded-lg p-6">

<h2 class="text-xl font-bold mb-6">
Buat Invoice
</h2>

<form action="{{ route('sales.store') }}" method="POST">
@csrf

<!-- HEADER -->
<div class="grid grid-cols-5 gap-4 mb-6">

<div>
<label class="block mb-1">Company</label>
<select name="company_id" class="border p-2 w-full rounded">
@foreach($companies as $company)
<option value="{{ $company->id }}">{{ $company->name }}</option>
@endforeach
</select>
</div>

<div>
<label class="block mb-1">Customer</label>
<select name="customer_id" class="border p-2 w-full rounded">
@foreach($customers as $customer)
<option value="{{ $customer->id }}">{{ $customer->name }}</option>
@endforeach
</select>
</div>

<div>
<label class="block mb-1">Tanggal Invoice</label>
<input type="date" name="date" class="border p-2 w-full rounded" required>
</div>

<div>
<label class="block mb-1">Jatuh Tempo</label>

<!-- INPUT DATE -->
<input type="date" id="due_date" name="due_date" class="border p-2 w-full rounded mb-2">

<!-- QUICK SELECT -->
<div class="flex flex-wrap gap-2">

<button type="button" class="due-btn bg-gray-200 px-2 py-1 rounded" data-day="7">7 Hari</button>
<button type="button" class="due-btn bg-gray-200 px-2 py-1 rounded" data-day="14">14 Hari</button>
<button type="button" class="due-btn bg-gray-200 px-2 py-1 rounded" data-day="21">21 Hari</button>
<button type="button" class="due-btn bg-gray-200 px-2 py-1 rounded" data-day="30">30 hari</button>
<button type="button" class="due-btn bg-gray-200 px-2 py-1 rounded" data-day="45">45 Hari</button>
<button type="button" class="due-btn bg-gray-200 px-2 py-1 rounded" data-day="60">60 Hari</button>

</div>
</div>

<div>
<label>No. PO</label>
<input type="text" name="po_number" class="border p-2 w-full rounded">
</div>

</div>

<!-- TABLE -->
<table class="w-full border" id="invoiceTable">

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

<tr>

<td>
<select name="product_id[]" class="product border p-2 w-full rounded">
<option value="">Pilih Produk</option>
@foreach($products as $product)
<option value="{{ $product->id }}" data-price="{{ $product->price }}">
{{ $product->name }}
</option>
@endforeach
</select>
</td>

<td>
<input type="number" name="price[]" class="price border p-2 w-full rounded">
</td>

<td>
<input type="text"
name="qty[]"
class="qty border p-2 w-full rounded"
value="1"
inputmode="decimal"
placeholder="Contoh: 1,5">
</td>

<td>
<input type="number" name="subtotal[]" class="subtotal border p-2 w-full rounded" readonly>
</td>

<td class="text-center">
<button type="button" class="remove bg-red-500 text-white px-3 py-1 rounded">X</button>
</td>

</tr>

</tbody>

</table>

<button type="button" id="addRow"
class="bg-green-600 text-white px-4 py-2 mt-4 rounded">
+ Tambah Barang
</button>

<!-- ONGKIR -->
<div class="mt-6 max-w-xs">
<label class="block mb-1 font-semibold">Ongkir</label>
<input type="number"
name="ongkir"
value="0"
min="0"
class="border p-2 w-full rounded"
placeholder="Masukkan ongkir">
<p class="text-sm text-gray-500 mt-1">
Ongkir hanya untuk laporan Excel, tidak tercetak di invoice.
</p>
</div>

<!-- TOTAL -->
<div class="mt-6 text-right">
<h2 class="text-xl font-bold">
Total : Rp <span id="grandTotal">0</span>
</h2>

<input type="hidden" name="total" id="totalInput">
</div>

<button class="bg-blue-600 text-white px-6 py-2 mt-4 rounded">
Simpan Invoice
</button>

</form>

</div>
</div>
</div>

<!-- ================= JS ================= -->
<script>

// ==============================
// HANDLE QUICK DUE DATE
// ==============================
document.querySelectorAll(".due-btn").forEach(btn => {

    btn.addEventListener("click", function(){

        let days = parseInt(this.getAttribute("data-day"));

        let invoiceDateInput = document.querySelector('input[name="date"]');
        let dueDateInput = document.getElementById("due_date");

        if(!invoiceDateInput.value){
            alert("Pilih tanggal invoice dulu!");
            return;
        }

        let invoiceDate = new Date(invoiceDateInput.value);

        invoiceDate.setDate(invoiceDate.getDate() + days);

        let year = invoiceDate.getFullYear();
        let month = String(invoiceDate.getMonth() + 1).padStart(2, '0');
        let day = String(invoiceDate.getDate()).padStart(2, '0');

        dueDateInput.value = `${year}-${month}-${day}`;

        document.querySelectorAll(".due-btn").forEach(b => {
            b.classList.remove("bg-blue-500","text-white");
        });

        this.classList.add("bg-blue-500","text-white");

    });

});


// ==============================
// FORMAT RUPIAH
// ==============================
function formatRupiah(angka) {

    return Number(angka).toLocaleString('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 3
    });

}


// ==============================
// SUPPORT ANGKA KOMA / DESIMAL
// ==============================
function parseNumber(value) {
    if (!value) return 0;

    value = String(value).trim();

    if (value.includes(',')) {
        value = value.replace(/\./g, '');
        value = value.replace(',', '.');
    }

    return parseFloat(value) || 0;
}


// ==============================
// HITUNG TOTAL
// ==============================
function calculateTotal() {

    let totalBarang = 0;

    document.querySelectorAll(".subtotal").forEach(el => {

        totalBarang += parseNumber(el.value);

    });

    let ongkirInput = document.querySelector('input[name="ongkir"]');

    let ongkir = ongkirInput
        ? parseNumber(ongkirInput.value)
        : 0;

    let grandTotal = totalBarang + ongkir;

    document.getElementById("grandTotal").innerText =
        formatRupiah(grandTotal);

    document.getElementById("totalInput").value =
        grandTotal;

}


// ==============================
// HITUNG SUBTOTAL
// ==============================
function hitungSubtotal(row) {

    let price = parseNumber(
        row.querySelector(".price").value
    );

    let qty = parseNumber(
        row.querySelector(".qty").value
    );

    let subtotal = price * qty;

    row.querySelector(".subtotal").value =
        subtotal;

    calculateTotal();

}


// ==============================
// PILIH PRODUK
// ==============================
document.addEventListener("change", function(e) {

    if (e.target.classList.contains("product")) {

        let row = e.target.closest("tr");

        let selected = e.target.selectedOptions[0];

        let price =
            selected.getAttribute("data-price") || 0;

        row.querySelector(".price").value = price;

        hitungSubtotal(row);
    }

});


// ==============================
// INPUT QTY / PRICE / ONGKIR
// ==============================
document.addEventListener("input", function(e) {

    if (
        e.target.classList.contains("qty") ||
        e.target.classList.contains("price")
    ) {

        let row = e.target.closest("tr");

        hitungSubtotal(row);

    }

    if (e.target.name === "ongkir") {

        calculateTotal();

    }

});


// ==============================
// TAMBAH ROW
// ==============================
document.getElementById("addRow")
.addEventListener("click", function() {

    let table =
        document.querySelector("#invoiceTable tbody");

    let row =
        table.rows[0].cloneNode(true);

    row.querySelectorAll("input")
        .forEach(input => input.value = "");

    row.querySelector(".qty").value = "1";

    let product = row.querySelector(".product");

    if (product) {

        product.selectedIndex = 0;

    }

    row.querySelector(".price").value = "";
    row.querySelector(".subtotal").value = "";

    table.appendChild(row);

    calculateTotal();

});


// ==============================
// HAPUS ROW
// ==============================
document.addEventListener("click", function(e) {

    if (e.target.classList.contains("remove")) {

        let row = e.target.closest("tr");

        if (
            document.querySelectorAll(
                "#invoiceTable tbody tr"
            ).length > 1
        ) {

            row.remove();

        }

        calculateTotal();

    }

});


// ==============================
// HITUNG AWAL
// ==============================
calculateTotal();

</script>

</x-app-layout>