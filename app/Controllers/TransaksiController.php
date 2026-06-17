<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\RajaOngkirService;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class TransaksiController extends BaseController
{
    protected $cart;
    protected $transactionModel;
    protected $transactionDetailModel;

    public function __construct()
    {
        helper(['number', 'form']);
        $this->cart = service('cart');
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
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

    public function checkout()
    {
        $data = [
            'items' => $this->cart->contents(),
            'total' => $this->cart->total(),
        ];

        return view('v_checkout', $data);
    }
    public function destinations()
    {
        $search = $this->request->getGet('q');

        if (empty($search)) {
            return $this->response->setJSON([
                'results' => []
            ]);
        }

        $service = new RajaOngkirService();
        $response = $service->getDestination($search);

        $results = [];
        $data = $response['data'] ?? [];

        foreach ($data as $item) {
            $results[] = [
                'id' => $item['id'],
                'text' => $item['label']
            ];
        }

        return $this->response->setJSON([
            'results' => $results
        ]);
    }

    public function costs()
    {
        $origin = '64999';
        $destination = $this->request->getGet('destination');
        $weight = '1000';
        $courier = 'jne';

        $service = new RajaOngkirService();
        $response = $service->getCost($origin, $destination, $weight, $courier);

        $results = [];
        $data = $response['data'] ?? [];

        foreach ($data as $item) {
            $results[] = [
                'service' => $item['service'],
                'description' => $item['description'],
                'cost' => $item['cost'],
                'etd' => $item['etd']
            ];
            
        }

        return $this->response->setJSON($results);
    }


}