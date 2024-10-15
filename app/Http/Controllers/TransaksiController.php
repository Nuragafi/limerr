<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Paket;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::paginate(20); // Atur jumlah item per halaman
        return view('staff.pages.transaksi.index', compact('transaksis'));
    }
    public function index_admin()
    {
       $transaksis = Transaksi::with('user')->paginate(20); 

       return view('admin.pages.transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        // Ambil semua data paket dari model Paket
        $pakets = Paket::all();

        // Kirim data paket ke view
        return view('staff.pages.transaksi.create', compact('pakets'));
    }
    public function createadmin()
    {
        // Get all paket data for admin
        $pakets = Paket::all();

        // Send paket data to the view
        return view('admin.pages.transaksi.create', compact('pakets'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nm_konsumen' => 'required|string|max:255',
            'nohp' => 'required|string|max:15',
            'paket_id' => 'required|exists:paket,id',
            'barcode' => 'required|string|max:255|unique:transaksi,barcode',
            'wahana_hidden' => 'required|numeric|min:1', // validasi untuk hidden field wahana
            'porsi_hidden' => 'required|numeric|min:1', // validasi untuk hidden field porsi
        ]);
    
        $paket = Paket::find($request->paket_id);
    
        if (!$paket) {
            return redirect()->back()->with('error', 'Paket tidak ditemukan.');
        }
    
        // Validasi tambahan jika wahana atau porsi kurang dari yang diinput di hidden field
        if ($paket->wahana < $request->wahana_hidden || $paket->porsi < $request->porsi_hidden) {
            return redirect()->back()->with('error', 'Wahana atau porsi tidak mencukupi.');
        }
    
        // Create transaksi dan gunakan nilai dari hidden field untuk wahana dan porsi
        $transaksi = Transaksi::create([
            'nm_konsumen' => $request->nm_konsumen,
            'nohp' => $request->nohp,
            'paket_id' => $paket->id,
            'barcode' => $request->barcode,
            'wahana' => $request->wahana_hidden, // Mengambil dari hidden field
            'porsi' => $request->porsi_hidden, // Mengambil dari hidden field
        ]);
    
        // Kurangi jumlah wahana dan porsi berdasarkan hidden input
        $paket->wahana -= $request->wahana_hidden;
        $paket->porsi -= $request->porsi_hidden;
        $paket->save(); // Simpan perubahan
    
        return redirect()->route('staff.transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
    }
    
    

    public function show($id)
{
    $transaksi = Transaksi::find($id);
    if (!$transaksi) {
        return response()->json(['message' => 'Transaksi not found.'], 404);
    }
    return view('staff.transaksi.show', compact('transaksi'));
}
    
    
    
    public function getPaket($id)
    {
        // Find the Paket by ID
        $paket = Paket::find($id);
    
        // Check if the Paket exists
        if ($paket) {
            return response()->json([
                'wahana' => $paket->wahana,
                'porsi' => $paket->porsi,
                'nm_paket' => $paket->nm_paket // Include the name of the paket if needed
            ]);
        }
    
        return response()->json(['error' => 'Paket not found'], 404);
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::find($id);
        if ($transaksi) {
            $transaksi->delete();
            return redirect()->route('staff.transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
        }

        return redirect()->route('staff.transaksi.index')->with('error', 'Transaksi tidak ditemukan.');
    }
}
