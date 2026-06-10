<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TransaksiController extends BaseController
{
    protected $cart;

    public function __construct()
    {
        helper(['number', 'form']);
        $this->cart = service('cart');
    }

    public function index()
    {
        $data = [
            'items' => $this->cart->contents()
        ];

        return view('v_keranjang', $data);
    }

    public function cart_add()
    {
        $this->cart->insert([
            'id' => $this->request->getPost('id'),
            'qty' => 1,
            'price' => $this->request->getPost('harga'),
            'name' => $this->request->getPost('nama'),
            'options' => [
                'foto' => $this->request->getPost('foto')
            ]
        ]);

        session()->setFlashdata(
            'success',
            'Produk berhasil ditambahkan ke keranjang. 
        <a href="' . base_url('keranjang') . '">Lihat</a>'
        );

        return redirect()->to(base_url('/'));
    }

    public function cart_delete($rowid)
    {
        $this->cart->remove($rowid);

        session()->setFlashdata(
            'success',
            'Produk berhasil dihapus dari keranjang'
        );

        return redirect()->to(base_url('keranjang'));
    }

    // --- TAMBAHAN BARU: Fungsi untuk memperbarui jumlah kuantitas (qty) barang ---
    public function cart_update()
    {
        $qtyData = $this->request->getPost('qty');

        // Looping untuk mengupdate qty berdasarkan masing-masing rowid
        if ($qtyData) {
            foreach ($qtyData as $rowid => $qty) {
                $this->cart->update([
                    'rowid' => $rowid,
                    'qty' => $qty
                ]);
            }

            session()->setFlashdata(
                'success',
                'Keranjang berhasil diperbarui'
            );
        }

        return redirect()->to(base_url('keranjang'));
    }

    // --- TAMBAHAN BARU: Fungsi untuk menghapus semua isi keranjang ---
    public function cart_clear()
    {
        $this->cart->destroy();

        session()->setFlashdata(
            'success',
            'Keranjang berhasil dikosongkan'
        );

        return redirect()->to(base_url('keranjang'));
    }
}