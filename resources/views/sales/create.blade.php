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
<input type="date" name="due_date" class="border p-2 w-full rounded">
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
<input type="number" name="qty[]" class="qty border p-2 w-full rounded" value="1">
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

// FORMAT RUPIAH
function formatRupiah(angka) {
    return angka.toLocaleString('id-ID');
}

// HITUNG TOTAL
function calculateTotal() {
    let total = 0;

    document.querySelectorAll(".subtotal").forEach(el => {
        total += parseFloat(el.value) || 0;
    });

    document.getElementById("grandTotal").innerText = formatRupiah(total);
    document.getElementById("totalInput").value = total;
}

// HITUNG SUBTOTAL
function hitungSubtotal(row) {
    let price = parseFloat(row.querySelector(".price").value) || 0;
    let qty = parseFloat(row.querySelector(".qty").value) || 0;

    let subtotal = price * qty;

    row.querySelector(".subtotal").value = subtotal;

    calculateTotal();
}

// PILIH PRODUK
document.addEventListener("change", function(e) {

    if (e.target.classList.contains("product")) {

        let row = e.target.closest("tr");
        let selected = e.target.selectedOptions[0];

        let price = selected.getAttribute("data-price") || 0;

        row.querySelector(".price").value = price;

        // 🔥 FIX UTAMA
        hitungSubtotal(row);
    }

});

// INPUT QTY / PRICE
document.addEventListener("input", function(e) {

    if (e.target.classList.contains("qty") || e.target.classList.contains("price")) {

        let row = e.target.closest("tr");
        hitungSubtotal(row);
    }

});

// TAMBAH ROW
document.getElementById("addRow").addEventListener("click", function() {

    let table = document.querySelector("#invoiceTable tbody");
    let row = table.rows[0].cloneNode(true);

    row.querySelectorAll("input").forEach(input => input.value = "");
    row.querySelector(".qty").value = 1;

    table.appendChild(row);
});

// HAPUS ROW
document.addEventListener("click", function(e) {

    if (e.target.classList.contains("remove")) {

        let row = e.target.closest("tr");

        if (document.querySelectorAll("#invoiceTable tbody tr").length > 1) {
            row.remove();
        }

        calculateTotal();
    }

});

</script>

</x-app-layout>