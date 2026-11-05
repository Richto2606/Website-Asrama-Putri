<?php

namespace App\Http\Controllers;

use App\Models\LaporanKeuangan;
use App\Models\Pembayaran;
use App\Models\Pengaturan;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminPembayaranController extends Controller
{
    public function index(): View
    {
        $this->generateMonthlyBills();
        return view('admin.pembayaran.index', [
            'pembayarans' => Pembayaran::with('user')->latest()->paginate(15),
            'penghunis' => User::where('role', 'penghuni')->where('status_penghuni', 'aktif')->orderBy('name')->get(),
            'settings' => Pengaturan::pluck('nilai', 'kunci'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'jenis_pembayaran' => ['required', 'in:registrasi,iuran_bulanan'],
            'bulan_tagihan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun_tagihan' => ['required', 'integer', 'min:2020', 'max:2100'],
            'jumlah_bayar' => ['nullable', 'numeric', 'min:0'],
            'metode_pembayaran' => ['required', 'in:cash,transfer'],
            'bukti_transfer' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3072'],
        ]);

        $user = User::where('role', 'penghuni')->where('status_penghuni', 'aktif')->findOrFail($data['user_id']);
        abort_if(Pembayaran::where('user_id', $data['user_id'])->where('jenis_pembayaran', $data['jenis_pembayaran'])->where('bulan_tagihan', $data['bulan_tagihan'])->where('tahun_tagihan', $data['tahun_tagihan'])->exists(), 422, 'Tagihan ini sudah tersedia.');
        $data['jumlah_bayar'] = $data['jumlah_bayar'] ?: Pengaturan::value('nominal_'.$data['jenis_pembayaran'], 0);
        $data['nomor_kuitansi'] = 'KWT-'.now()->format('YmdHis').'-'.str_pad((string) random_int(1, 999), 3, '0', STR_PAD_LEFT);
        $data['bukti_transfer'] = $request->file('bukti_transfer')?->store('pembayaran', 'public');
        $data['status_pembayaran'] = 'menunggu_verifikasi';
        $user->pembayaran()->create($data);

        return back()->with('success', 'Data pembayaran berhasil ditambahkan.');
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nominal_registrasi' => ['required', 'numeric', 'min:0'],
            'nominal_iuran_bulanan' => ['required', 'numeric', 'min:0'],
            'bank' => ['required', 'string', 'max:100'],
            'nomor_rekening' => ['required', 'string', 'max:50'],
            'atas_nama' => ['required', 'string', 'max:150'],
        ]);
        foreach ($data as $key => $value) {
            Pengaturan::updateOrCreate(['kunci' => $key], ['nilai' => $value]);
        }
        return back()->with('success', 'Pengaturan pembayaran berhasil disimpan.');
    }

    public function receipt(Pembayaran $pembayaran): Response
    {
        $pdf = Pdf::loadView('admin.pembayaran.kuitansi', ['pembayaran' => $pembayaran->load('user'), 'settings' => Pengaturan::pluck('nilai', 'kunci')])->setPaper('a5');
        return $pdf->download('kuitansi-'.$pembayaran->nomor_kuitansi.'.pdf');
    }

    private function generateMonthlyBills(): void
    {
        $month = now()->month;
        $year = now()->year;
        $amount = (float) Pengaturan::value('nominal_iuran_bulanan', 150000);
        User::where('role', 'penghuni')->where('status_penghuni', 'aktif')->each(function (User $user) use ($month, $year, $amount): void {
            Pembayaran::firstOrCreate(
                ['user_id' => $user->id, 'jenis_pembayaran' => 'iuran_bulanan', 'bulan_tagihan' => $month, 'tahun_tagihan' => $year],
                ['jumlah_bayar' => $amount, 'metode_pembayaran' => 'transfer', 'status_pembayaran' => 'menunggu_verifikasi', 'nomor_kuitansi' => 'TAG-'.$year.$month.'-'.$user->id],
            );
        });
    }

    public function update(Request $request, Pembayaran $pembayaran): RedirectResponse
    {
        $status = $request->validate([
            'status_pembayaran' => ['required', 'in:lunas,ditolak'],
        ])['status_pembayaran'];

        DB::transaction(function () use ($pembayaran, $status): void {
            $wasPaid = $pembayaran->status_pembayaran === 'lunas';
            $pembayaran->update(['status_pembayaran' => $status]);

            if ($status === 'lunas' && ! $wasPaid) {
                LaporanKeuangan::firstOrCreate(
                    ['pembayaran_id' => $pembayaran->id],
                    [
'jenis_transaksi' => 'pemasukan',
                         'kategori' => $pembayaran->jenis_pembayaran === 'registrasi' ? 'lain_lain' : 'iuran',
                         'nominal' => $pembayaran->jumlah_bayar,
                        'keterangan' => 'Pembayaran penghuni: '.$pembayaran->user()->value('name').' - '.$pembayaran->bulan_tagihan.'/'.$pembayaran->tahun_tagihan,
                        'tanggal' => now()->toDateString(),
                        'created_by' => auth()->id(),
                    ],
                );
            }
        });

        return back()->with('success', 'Status pembayaran diperbarui.');
    }
}
