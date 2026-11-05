<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\LaporanKeuangan;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $totalCapacity = Kamar::sum('kapasitas');
        $totalOccupied = User::where('role', 'penghuni')->whereNotNull('kamar_id')->count();
        $pemasukan = LaporanKeuangan::where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $pengeluaran = LaporanKeuangan::where('jenis_transaksi', 'pengeluaran')->sum('nominal');
        $pendingRegistrations = Pendaftaran::where('status_pendaftaran', 'menunggu')->count();
        $pendingPayments = Pembayaran::where('status_pembayaran', 'menunggu_verifikasi')->count();
        $month = now()->month;
        $year = now()->year;
        $monthlyIncome = LaporanKeuangan::where('jenis_transaksi', 'pemasukan')->whereMonth('tanggal', $month)->whereYear('tanggal', $year)->sum('nominal');
        $monthlyExpense = LaporanKeuangan::where('jenis_transaksi', 'pengeluaran')->whereMonth('tanggal', $month)->whereYear('tanggal', $year)->sum('nominal');
        $unpaidResidents = User::where('role', 'penghuni')->whereDoesntHave('pembayaran', function ($query) use ($month, $year): void {
            $query->where('bulan_tagihan', $month)->where('tahun_tagihan', $year)->where('status_pembayaran', 'lunas');
        })->count();
        $activities = Pendaftaran::latest()->take(5)->get()->map(fn ($item) => [
            'label' => 'Pendaftar baru: '.$item->nama_lengkap,
            'date' => $item->created_at,
            'url' => route('admin.pendaftaran.show', $item),
        ])->concat(Pembayaran::with('user')->latest()->take(5)->get()->map(fn ($item) => [
            'label' => 'Pembayaran masuk: '.($item->user?->name ?? 'Penghuni'),
            'date' => $item->created_at,
            'url' => route('admin.pembayaran.index'),
        ]))->sortByDesc('date')->take(6)->values();

        return view('admin.dashboard', [
            'penghuni' => User::where('role', 'penghuni')->count(),
            'kamar' => Kamar::count(),
            'pendaftaran' => $pendingRegistrations,
            'pembayaran' => $pendingPayments,
            'totalCapacity' => $totalCapacity,
            'totalOccupied' => $totalOccupied,
            'availableRoomCount' => Kamar::whereColumn('terisi', '<', 'kapasitas')->count(),
            'occupancyRate' => $totalCapacity > 0 ? round(($totalOccupied / $totalCapacity) * 100) : 0,
            'fullRoomCount' => Kamar::whereColumn('terisi', '>=', 'kapasitas')->count(),
            'unallocatedResidents' => User::where('role', 'penghuni')->whereNull('kamar_id')->count(),
            'balance' => $pemasukan - $pengeluaran,
            'monthlyBalance' => $monthlyIncome - $monthlyExpense,
            'unpaidResidents' => $unpaidResidents,
            'activities' => $activities,
        ]);
    }

    public function exportPendaftaran(Request $request): Response
    {
        $query = Pendaftaran::with('user')->latest();
        $status = $request->query('status');
        $campus = trim((string) $request->query('campus', ''));

        if ($status === 'pending') {
            $query->where('status_pendaftaran', 'menunggu');
        } elseif ($status === 'riwayat') {
            $query->whereIn('status_pendaftaran', ['diterima', 'ditolak', 'perlu_perbaikan', 'daftar_tunggu']);
        } elseif ($status && $status !== 'semua') {
            $query->where('status_pendaftaran', $status);
        }
        if ($campus !== '') {
            $query->where('institusi_pendidikan', $campus);
        }

        $rows = $query->get();
        $html = '<html><head><meta charset="UTF-8"><style>body{font-family:Arial}table{border-collapse:collapse;width:100%}th{background:#17201d;color:#fff}th,td{border:1px solid #d9d9d9;padding:8px;text-align:left}tr:nth-child(even){background:#f5f5f5}</style></head><body><h2>Data Pendaftaran SIAP-Kukar</h2><table><thead><tr><th>No</th><th>Nomor Pendaftaran</th><th>Nama</th><th>Email</th><th>NIK</th><th>Institusi</th><th>Semester</th><th>Status</th><th>Alasan/ Catatan Admin</th><th>Tanggal Daftar</th></tr></thead><tbody>';
        foreach ($rows as $index => $row) {
            $values = [$index + 1, $row->nomor_pendaftaran, $row->nama_lengkap, $row->user?->email, $row->nik, $row->institusi_pendidikan, $row->semester, ucfirst(str_replace('_', ' ', $row->status_pendaftaran)), $row->catatan_admin, $row->created_at?->format('d-m-Y H:i')];
            $html .= '<tr>'.collect($values)->map(fn ($value) => '<td>'.e((string) $value).'</td>')->implode('').'</tr>';
        }
        $html .= '</tbody></table></body></html>';

        return response($html)->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')->header('Content-Disposition', 'attachment; filename="data-pendaftaran.xls"');
    }

    public function exportPendaftaranPdf(Request $request): Response
    {
        $query = Pendaftaran::with('user')->latest();
        $status = $request->query('status');
        $campus = trim((string) $request->query('campus', ''));

        if ($status === 'pending') {
            $query->where('status_pendaftaran', 'menunggu');
        } elseif ($status === 'riwayat') {
            $query->whereIn('status_pendaftaran', ['diterima', 'ditolak', 'perlu_perbaikan', 'daftar_tunggu']);
        } elseif ($status && $status !== 'semua') {
            $query->where('status_pendaftaran', $status);
        }
        if ($campus !== '') {
            $query->where('institusi_pendidikan', $campus);
        }

        $pdf = Pdf::loadView('admin.pendaftaran-pdf', [
            'pendaftarans' => $query->get(),
            'status' => $status,
            'campus' => $campus,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('data-pendaftaran-'.($status ?: 'semua').'-'.now()->format('YmdHis').'.pdf');
    }

    public function exportPembayaran(): Response
    {
        $rows = Pembayaran::with('user')->latest()->get();
        $csv = "\xEF\xBB\xBFNama,Email,Bulan,Tahun,Jumlah,Metode,Status\n";
        foreach ($rows as $row) {
            $values = [$row->user?->name, $row->user?->email, $row->bulan_tagihan, $row->tahun_tagihan, $row->jumlah_bayar, $row->metode_pembayaran, $row->status_pembayaran];
            $csv .= collect($values)->map(fn ($value) => '"'.str_replace('"', '""', (string) $value).'"')->implode(',')."\n";
        }

        return response($csv)->header('Content-Type', 'text/csv; charset=UTF-8')->header('Content-Disposition', 'attachment; filename="data-pembayaran.csv"');
    }

    public function exportLaporanPdf(Request $request): Response
    {
        $data = $this->laporanData($request);
        $pdf = Pdf::loadView('admin.laporan-pdf', $data)->setPaper('a4', 'landscape');

        return $pdf->download('laporan-'.$data['jenis'].'-'.now()->format('YmdHis').'.pdf');
    }

    public function exportLaporan(Request $request): Response
    {
        $data = $this->laporanData($request);
        $jenis = $data['jenis'];
        $title = $jenis === 'pengeluaran' ? 'LAPORAN PENGELUARAN' : ($jenis === 'cashflow' ? 'LAPORAN CASHFLOW' : 'LAPORAN PEMASUKAN');
        $csv = "\xEF\xBB\xBF".$title."\nPeriode: ".($data['mulai'] ?: 'Semua')." s/d ".($data['sampai'] ?: 'Sekarang')."\n\n";
        $csv .= $jenis === 'cashflow' ? "Tanggal,No Transaksi,Penghuni,Kamar,Metode,Kategori,Keterangan,Jenis Arus,Total\n" : "Tanggal,No Transaksi,Penghuni,Kamar,Metode,Kategori,Keterangan,Total ".ucfirst($jenis)."\n";

        foreach ($data['transaksi'] as $item) {
            $resident = $item->jenis_transaksi === 'pengeluaran' ? 'Bendahara' : ($item->pembayaran?->user?->name ?? '-');
            $room = $item->pembayaran?->user?->kamar?->nomor_kamar ? 'Kamar '.$item->pembayaran->user->kamar->nomor_kamar : ($item->pembayaran_id ? 'Belum Dialokasikan' : 'Transaksi Manual');
            $method = $item->pembayaran?->metode_pembayaran === 'cash' ? 'Cash' : ($item->pembayaran_id ? 'Transfer' : ($item->metode_pembayaran ? ucfirst($item->metode_pembayaran) : 'Manual'));
            $amount = $jenis === 'cashflow' && $item->jenis_transaksi === 'pengeluaran' ? -$item->nominal : $item->nominal;
            $values = [$item->tanggal?->format('d M Y'), $item->pembayaran_id ? 'PAY-'.str_pad($item->pembayaran_id, 5, '0', STR_PAD_LEFT) : 'MAN-'.str_pad($item->id, 5, '0', STR_PAD_LEFT), $resident, $room, $method, $item->kategori ?? 'lain_lain', $item->keterangan];
            if ($jenis === 'cashflow') {
                $values[] = ucfirst($item->jenis_transaksi);
            }
            $values[] = $amount;
            $csv .= collect($values)->map(fn ($value) => '"'.str_replace('"', '""', (string) $value).'"')->implode(',')."\n";
        }
        if ($jenis === 'cashflow') {
            $csv .= "\nTOTAL PEMASUKAN,,,,,,,".$data['pemasukan']."\nTOTAL PENGELUARAN,,,,,,,".$data['pengeluaran']."\nSALDO,,,,,,,".($data['pemasukan'] - $data['pengeluaran'])."\n";
        } else {
            $total = $jenis === 'pengeluaran' ? $data['pengeluaran'] : $data['pemasukan'];
            $csv .= 'TOTAL,,,,,,' . $total . "\n";
        }

        return response($csv)->header('Content-Type', 'text/csv; charset=UTF-8')->header('Content-Disposition', 'attachment; filename="laporan-'.strtolower($jenis).'.csv"');
    }

    public function laporanHunian(): View
    {
        return view('admin.laporan-hunian', [
            'penghunis' => User::with(['kamar', 'pendaftaran'])->where('role', 'penghuni')->where('status_penghuni', 'aktif')->orderBy('name')->get(),
            'kamars' => Kamar::withCount(['penghunis' => fn ($query) => $query->where('role', 'penghuni')->where('status_penghuni', 'aktif')])->orderBy('nomor_kamar')->get(),
        ]);
    }

    public function exportHunianPdf(): Response
    {
        $pdf = Pdf::loadView('admin.laporan-hunian-pdf', [
            'penghunis' => User::with(['kamar', 'pendaftaran'])->where('role', 'penghuni')->where('status_penghuni', 'aktif')->orderBy('name')->get(),
            'kamars' => Kamar::withCount(['penghunis' => fn ($query) => $query->where('role', 'penghuni')->where('status_penghuni', 'aktif')])->orderBy('nomor_kamar')->get(),
        ])->setPaper('a4', 'landscape');
        return $pdf->download('laporan-penghuni-kamar-'.now()->format('YmdHis').'.pdf');
    }

    private function laporanData(Request $request): array
    {
        $jenis = $request->query('jenis', 'pemasukan');
        $mulai = $request->query('mulai');
        $sampai = $request->query('sampai');
        $search = trim((string) $request->query('search', ''));
        $query = LaporanKeuangan::with(['creator', 'pembayaran.user.kamar']);

        if (in_array($jenis, ['pemasukan', 'pengeluaran'], true)) {
            $query->where('jenis_transaksi', $jenis);
        }
        if ($jenis === 'cashflow') {
            $query->whereIn('jenis_transaksi', ['pemasukan', 'pengeluaran']);
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

        $transaksi = $query->latest('tanggal')->get();
        $pemasukan = $transaksi->where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $pengeluaran = $transaksi->where('jenis_transaksi', 'pengeluaran')->sum('nominal');

        return compact('transaksi', 'pemasukan', 'pengeluaran', 'jenis', 'mulai', 'sampai', 'search');
    }

    public function laporan(Request $request): View
    {
        $jenis = $request->query('jenis', 'pemasukan');
        $mulai = $request->query('mulai');
        $sampai = $request->query('sampai');
        $search = trim((string) $request->query('search', ''));
        if ($request->query('periode') === 'bulan_ini') {
            $mulai = now()->startOfMonth()->toDateString();
            $sampai = now()->endOfMonth()->toDateString();
        } elseif ($request->query('periode') === 'tahun_ini') {
            $mulai = now()->startOfYear()->toDateString();
            $sampai = now()->endOfYear()->toDateString();
        }
        $base = LaporanKeuangan::with(['creator', 'pembayaran.user.kamar']);
        $query = clone $base;

        if (in_array($jenis, ['pemasukan', 'pengeluaran'], true)) {
            $query->where('jenis_transaksi', $jenis);
        }
        if ($jenis === 'cashflow') {
            $query->whereIn('jenis_transaksi', ['pemasukan', 'pengeluaran']);
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

        $filtered = clone $query;
        $pemasukan = (clone $filtered)->where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $pengeluaran = (clone $filtered)->where('jenis_transaksi', 'pengeluaran')->sum('nominal');

        return view('admin.laporan', [
            'transaksi' => $query->latest('tanggal')->get(),
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'jenis' => $jenis,
            'mulai' => $mulai,
            'sampai' => $sampai,
            'search' => $search,
            'pendingPembayaran' => Pembayaran::where('status_pembayaran', 'menunggu_verifikasi')->count(),
        ]);
    }
}
