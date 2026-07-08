<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
  <div class="col-lg-6">
    <?= form_open('buy', 'class="row g-3"') ?>

    <?= form_hidden('username', session()->get('username')) ?>

    <?php
    $total_harga_barang = 0;

    // Ambil data dari session 'cart_contents'
    $cart_contents = session()->get('cart_contents') ?? [];

    if (!empty($cart_contents)) {
      foreach ($cart_contents as $item) {
        if (is_array($item)) {
          $price = $item['price'] ?? 0;
          $qty = $item['qty'] ?? 0;
          $total_harga_barang += $price * $qty;
        }
      }
    }
    ?>
    <?= form_hidden('total_harga', (string) $total_harga_barang) ?>

    <div class="col-12">
      <?= form_label('Nama', 'nama', ['class' => 'form-label']) ?>
      <?= form_input([
        'name' => 'nama',
        'id' => 'nama',
        'class' => 'form-control',
        'value' => session()->get('username'),
        'readonly' => true
      ]) ?>
    </div>
    <div class="col-12">
      <?= form_label('Alamat', 'alamat', ['class' => 'form-label']) ?>
      <?= form_input([
        'name' => 'alamat',
        'id' => 'alamat',
        'class' => 'form-control'
      ]) ?>
    </div>
    <div class="col-12">
      <?= form_label('Cari Kota / Kecamatan Tujuan', 'destination_location', ['class' => 'form-label fw-bold']) ?>
      <select name="destination_location" id="destination_location" class="form-control select2" style="width: 100%;">
        <option value="">Tulis nama kota atau kecamatan...</option>
      </select>
    </div>

    <div class="col-12 mt-3">
      <?= form_label('Pilih Layanan Kurir Pengiriman', 'layanan', ['class' => 'form-label fw-bold']) ?>
      <select name="layanan" id="layanan" class="form-control" disabled>
        <option value="">-- Pilih Layanan (Pilih lokasi tujuan dahulu) --</option>
      </select>
    </div>
    <div class="col-12">
      <?= form_label('Ongkir', 'ongkir', ['class' => 'form-label']) ?>
      <?= form_input([
        'name' => 'ongkir',
        'id' => 'ongkir',
        'class' => 'form-control',
        'readonly' => true,
        'value' => 0
      ]) ?>
    </div>

    <div class="col-12">
      <?= form_label('Kode Voucher', 'voucher_code', ['class' => 'form-label fw-bold']) ?>
      <?= form_input([
        'name' => 'voucher_code',
        'id' => 'voucher_code',
        'class' => 'form-control',
        'placeholder' => 'Masukkan kode voucher (contoh: FLASH10)',
        'value' => old('voucher_code')
      ]) ?>
      <div class="form-text text-muted mt-1">
        Tersedia:
        <span class="badge bg-secondary">FLASH10</span>,
        <span class="badge bg-secondary">FLASH15</span>,
        <span class="badge bg-secondary">MEMBER20</span>
      </div>
    </div>

    <div class="col-12 mt-4">
      <?= form_submit('submit', 'Buat Pesanan', ['class' => 'btn btn-primary']) ?>
    </div>

    <?= form_close() ?>
  </div>

  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title fw-bold">Ringkasan Pesanan</h5>
        <table class="table table-borderless align-middle">
          <thead>
            <tr class="border-bottom">
              <th>Nama</th>
              <th>Harga</th>
              <th>Jumlah</th>
              <th class="text-end">Sub Total</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (!empty($cart_contents)):
              foreach ($cart_contents as $key => $item):
                // Skip elemen total_items atau cart_total yang bukan array data produk
                if (!is_array($item)) {
                  continue;
                }
                $nama_produk = $item['name'] ?? 'Produk';
                $harga_produk = $item['price'] ?? 0;
                $jumlah_produk = $item['qty'] ?? 0;
                $sub_total_item = $item['subtotal'] ?? ($harga_produk * $jumlah_produk);
                ?>
                <tr>
                  <td><?= $nama_produk ?></td>
                  <td>IDR <?= number_format($harga_produk, 0, ',', '.') ?></td>
                  <td><?= $jumlah_produk ?></td>
                  <td class="text-end">IDR <?= number_format($sub_total_item, 0, ',', '.') ?></td>
                </tr>
                <?php
              endforeach;
            else:
              ?>
              <tr>
                <td colspan="4" class="text-center text-muted">Tidak ada item di keranjang.</td>
              </tr>
            <?php endif; ?>

            <tr class="border-top">
              <td colspan="3" class="text-end fw-bold">Subtotal Produk</td>
              <td class="text-end fw-bold">IDR <?= number_format($total_harga_barang, 0, ',', '.') ?></td>
            </tr>

            <tr>
              <td colspan="3" class="text-end text-danger">Diskon Voucher <span id="persen-diskon-view"></span></td>
              <td class="text-end text-danger">- IDR <span id="diskon-view">0</span></td>
            </tr>
            <tr>
              <td colspan="3" class="text-end">PPN (11%)</td>
              <td class="text-end">+ IDR <span id="ppn-view">0</span></td>
            </tr>
            <tr>
              <td colspan="3" class="text-end">Biaya Admin</td>
              <td class="text-end">+ IDR <span id="admin-view">0</span></td>
            </tr>
            <tr class="table-light">
              <td colspan="3" class="text-end fw-bold text-success">Subtotal (+PPN+Admin-Voucher)</td>
              <td class="text-end fw-bold text-success">IDR <span id="subtotal-akhir-view">0</span></td>
            </tr>
            <tr>
              <td colspan="3" class="text-end">Ongkir</td>
              <td class="text-end">+ IDR <span id="ongkir-view">0</span></td>
            </tr>
            <tr class="border-top">
              <td colspan="3" class="text-end fw-bold fs-5">Grand Total (incl. Ongkir)</td>
              <td class="text-end fw-bold fs-5 text-primary">IDR <span id="grand-total-view">0</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('script') ?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const totalHargaBarang = <?= $total_harga_barang ?>;
    const inputVoucher = document.getElementById('voucher_code');
    const inputOngkir = document.getElementById('ongkir');
    const selectLayanan = document.getElementById('layanan');

    function formatRupiah(angka) {
      return new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(angka);
    }

    // ==========================================
    // LOGIKA UTAMA KALKULASI CHECKOUT (UAS)
    // ==========================================
    function hitungUasCheckout() {
      const ppn = totalHargaBarang * 0.11;

      let biayaAdmin = 0;
      if (totalHargaBarang <= 20000000) {
        biayaAdmin = totalHargaBarang * 0.006;
      } else if (totalHargaBarang <= 40000000) {
        biayaAdmin = totalHargaBarang * 0.008;
      } else {
        biayaAdmin = totalHargaBarang * 0.01;
      }

      const kode = inputVoucher.value.toUpperCase().trim();
      let persenDiskon = 0;
      let diskonVoucher = 0;

      if (kode === 'FLASH10') persenDiskon = 10;
      else if (kode === 'FLASH15') persenDiskon = 15;
      else if (kode === 'MEMBER20') persenDiskon = 20;

      if (persenDiskon > 0) {
        diskonVoucher = totalHargaBarang * (persenDiskon / 100);
        document.getElementById('persen-diskon-view').innerText = `(${persenDiskon}%)`;
      } else {
        document.getElementById('persen-diskon-view').innerText = '';
      }

      const subtotalAkhir = totalHargaBarang - diskonVoucher + ppn + biayaAdmin;
      const ongkir = parseFloat(inputOngkir.value) || 0;
      const grandTotal = subtotalAkhir + ongkir;

      document.getElementById('diskon-view').innerText = formatRupiah(diskonVoucher);
      document.getElementById('ppn-view').innerText = formatRupiah(ppn);
      document.getElementById('admin-view').innerText = formatRupiah(biayaAdmin);
      document.getElementById('subtotal-akhir-view').innerText = formatRupiah(subtotalAkhir);
      document.getElementById('ongkir-view').innerText = formatRupiah(ongkir);
      document.getElementById('grand-total-view').innerText = formatRupiah(grandTotal);
    }

    // ==========================================
    // INSIALISASI SEARCH DROPDOWN VIA SELECT2
    // ==========================================
    if (typeof jQuery !== 'undefined') {
      $('#destination_location').select2({
        placeholder: 'Ketik nama kota atau kecamatan tujuan...',
        minimumInputLength: 3,
        ajax: {
          url: '<?= base_url("ajax/destinations") ?>',
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return { q: params.term };
          },
          processResults: function (data) {
            // 1. Ambil data dari data.results sesuai format JSON dari controllermu
            const lokasiData = data.results || [];

            // 2. Batasi (limit) hanya menampilkan 10 data teratas di layar browser
            const limitedData = lokasiData.slice(0, 10);

            return {
              results: limitedData.map(function (item) {
                return {
                  id: item.id, // Mengambil properti "id" (contoh: 65005)
                  text: item.text // Mengambil properti "text" alamat lengkap
                };
              })
            };
          },
          cache: true
        }
      });

      // Event listener saat lokasi selesai dipilih
      $('#destination_location').on('select2:select', function (e) {
        const destinationId = e.params.data.id;

        selectLayanan.disabled = true;
        selectLayanan.innerHTML = '<option value="">Memuat pilihan kurir...</option>';

        // Tembak ke route asli pengambil tarif ongkir kurir kamu
        fetch('<?= base_url("ajax/costs?destination=") ?>' + destinationId)
          .then(response => response.json())
          .then(data => {
            selectLayanan.innerHTML = '<option value="">-- Pilih Layanan Pengiriman --</option>';
            selectLayanan.disabled = false;

            // data berbentuk array langsung, kita loop tiap layanannya
            data.forEach(item => {
              let opt = document.createElement('option');

              // Gunakan properti 'cost' sesuai JSON asli kamu
              const tarif = item.cost || 0;
              opt.value = tarif;

              // Susun teks dropdown menggunakan properti 'description', 'service', dan 'etd' dari JSON-mu
              const namaKurir = item.description || 'JNE';
              const namaService = item.service || '';
              const estimasi = item.etd ? ` (Estimasi ${item.etd})` : '';

              opt.innerText = `${namaKurir} - ${namaService} [IDR ${formatRupiah(tarif)}]${estimasi}`;
              selectLayanan.appendChild(opt);
            });
          })
          .catch(err => {
            console.error('Detail Error Kurir:', err);
            selectLayanan.innerHTML = '<option value="">Gagal memuat kurir pengiriman</option>';
          });
      });
    }

    // Event listener untuk perubahan pilihan kurir dan input voucher
    selectLayanan.addEventListener('change', function () {
      inputOngkir.value = parseFloat(this.value) || 0;
      hitungUasCheckout();
    });

    inputVoucher.addEventListener('input', hitungUasCheckout);

    // Jalankan kalkulasi dasar saat halaman awal dimuat
    hitungUasCheckout();
  });
</script>

<?= $this->endSection() ?>