<?php

namespace App\Http\Controllers;

use App\Models\LaporanKeuangan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminKeuanganController extends Controller
{
    public function deleteIndex(): View
    {
        return view('admin.keuangan.delete', [
            'transaksi' => LaporanKeuangan::with(['pembayaran.user.kamar'])->latest('tanggal')->get(),
        ]);
    }

    public function index(Request $request): View
    {
        $jenis = $request->query('jenis', 'pemasukan');
        $search = trim((string) $request->query('search', ''));
        $mulai = $request->query('mulai');
        $sampai = $request->query('sampai');
        $query = LaporanKeuangan::with(['creator', 'pembayaran.user.kamar'])->latest('tanggal');

        if (in_array($jenis, ['pemasukan', 'pengeluaran'], true)) {
            $query->where('jenis_transaksi', $jenis);
        }

        if ($mulai) {
            $query->whereDate('tanggal', '>=', $mulai);
        }
        if ($sampai) {
            $query->whereDate('tanggal', '<=', $sampai);
        }
        if ($search !== '') {
            $query->where(function ($query) use ($search): void {
                $query->where('keterangan', 'like', "%{$search}%")
                    ->orWhereHas('pembayaran.user', fn ($query) => $query->where('name', 'like', "%{$search}%"));
            });
        }

        return view('admin.keuangan.index', [
            'transaksi' => $query->paginate(15)->withQueryString(),
            'pemasukan' => $this->periodTotal('pemasukan', $mulai, $sampai),
            'pengeluaran' => $this->periodTotal('pengeluaran', $mulai, $sampai),
            'jenis' => $jenis,
            'search' => $search,
            'mulai' => $mulai,
            'sampai' => $sampai,
        ]);
    }

    private function periodTotal(string $jenis, ?string $mulai, ?string $sampai): float
    {
        return (float) LaporanKeuangan::where('jenis_transaksi', $jenis)->when($mulai, fn ($query) => $query->whereDate('tanggal', '>=', $mulai))->when($sampai, fn ($query) => $query->whereDate('tanggal', '<=', $sampai))->sum('nominal');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'jenis_transaksi' => ['required', 'in:pemasukan,pengeluaran'],
            'kategori' => ['required', 'in:iuran,donasi,listrik,air,perbaikan,kegiatan,lain_lain'],
            'nominal' => ['required', 'numeric', 'min:0.01'],
            'metode_pembayaran' => ['required_if:jenis_transaksi,pengeluaran', 'nullable', 'in:cash,transfer'],
            'bukti_pembayaran' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3072'],
            'keterangan' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
        ]);
        $data['created_by'] = auth()->id();
        $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')?->store('keuangan', 'public');
        LaporanKeuangan::create($data);

        return back()->with('success', 'Transaksi keuangan berhasil ditambahkan.');
    }

    public function destroy(LaporanKeuangan $laporanKeuangan): RedirectResponse
    {
        abort_if($laporanKeuangan->pembayaran_id !== null, 403, 'Transaksi pembayaran tidak dapat dihapus dari Kelola Keuangan.');
        $laporanKeuangan->delete();

        return back()->with('success', 'Transaksi keuangan berhasil dihapus.');
    }
}
