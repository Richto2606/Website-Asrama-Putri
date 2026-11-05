<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Kamar;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ValidationException;
use Illuminate\View\View;

class AdminPenghuniController extends Controller
{
    public function index(): View
    {
        $this->refreshOccupancy();

        return view('admin.penghuni.index', [
            'penghunis' => User::with(['kamar', 'pendaftaran.berkas'])->where('role', 'penghuni')->where('status_penghuni', 'aktif')->latest()->paginate(15),
            'kamars' => Kamar::orderBy('nomor_kamar')->get(),
            'expiringResidents' => User::where('role', 'penghuni')->where('status_penghuni', 'aktif')->whereBetween('tanggal_berakhir', [now()->toDateString(), now()->addDays(30)->toDateString()])->orderBy('tanggal_berakhir')->get(),
        ]);
    }

    public function show(User $user): View
    {
        abort_unless($user->role === 'penghuni', 404);

        return view('admin.penghuni.show', ['penghuni' => $user->load(['kamar', 'pendaftaran.berkas'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'nik' => ['required', 'digits:16'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'asal_daerah' => ['required', 'in:Kutai Kartanegara'],
            'alamat_asal' => ['required', 'string', 'max:255'],
            'institusi_pendidikan' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
            'no_hp_orang_tua' => ['required', 'string', 'max:20'],
            'nama_orang_tua' => ['required', 'string', 'max:255'],
            'kamar_id' => ['nullable', 'exists:kamars,id'],
            'jenjang' => ['nullable', 'string', 'max:50'],
            'id_asrama' => ['nullable', 'string', 'max:50', 'unique:users,id_asrama'],
            'tanggal_masuk' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($data): void {
            $this->validateRoomCapacity($data['kamar_id'] ?? null);
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => 'password',
                'role' => 'penghuni',
                'semester' => $data['semester'],
                'kamar_id' => $data['kamar_id'] ?? null,
                'id_asrama' => $data['id_asrama'] ?? null,
                'tanggal_masuk' => $data['tanggal_masuk'] ?? now()->toDateString(),
                'tanggal_berakhir' => $this->calculateEndDate($data['jenjang'] ?? null, $data['tanggal_masuk'] ?? now()->toDateString()),
                'status_penghuni' => 'aktif',
            ]);
            $user->pendaftaran()->create([
                'nama_lengkap' => $data['name'],
                'nik' => $data['nik'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'asal_daerah' => $data['asal_daerah'],
                'alamat_asal' => $data['alamat_asal'],
                'institusi_pendidikan' => $data['institusi_pendidikan'],
                'semester' => $data['semester'],
                'no_hp_orang_tua' => $data['no_hp_orang_tua'],
                'nama_orang_tua' => $data['nama_orang_tua'],
                'status_pendaftaran' => 'diterima',
            ]);
            $this->refreshOccupancy();
        });

        return back()->with('success', 'Penghuni berhasil ditambahkan. Password awal: password.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'penghuni', 404);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'digits:16'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'asal_daerah' => ['required', 'in:Kutai Kartanegara'],
            'alamat_asal' => ['required', 'string', 'max:255'],
            'institusi_pendidikan' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
            'no_hp_orang_tua' => ['required', 'string', 'max:20'],
            'nama_orang_tua' => ['required', 'string', 'max:255'],
            'kamar_id' => ['nullable', 'exists:kamars,id'],
            'jenjang' => ['nullable', 'string', 'max:50'],
            'id_asrama' => ['nullable', 'string', 'max:50', 'unique:users,id_asrama,'.$user->id],
            'tanggal_masuk' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($user, $data): void {
            $this->validateRoomCapacity($data['kamar_id'] ?? null, $user->id);
            $tanggalMasuk = $data['tanggal_masuk'] ?? $user->tanggal_masuk ?? now()->toDateString();
             $user->update(['name' => $data['name'], 'semester' => $data['semester'], 'kamar_id' => $data['kamar_id'] ?? null, 'id_asrama' => $data['id_asrama'] ?? $user->id_asrama, 'tanggal_masuk' => $tanggalMasuk, 'tanggal_berakhir' => $this->calculateEndDate($data['jenjang'] ?? $user->pendaftaran?->jenjang, $tanggalMasuk)]);
            $user->pendaftaran()->update(['nama_lengkap' => $data['name'], 'nik' => $data['nik'], 'tanggal_lahir' => $data['tanggal_lahir'], 'asal_daerah' => $data['asal_daerah'], 'alamat_asal' => $data['alamat_asal'], 'institusi_pendidikan' => $data['institusi_pendidikan'], 'jenjang' => $data['jenjang'] ?? $user->pendaftaran?->jenjang, 'semester' => $data['semester'], 'no_hp_orang_tua' => $data['no_hp_orang_tua'], 'nama_orang_tua' => $data['nama_orang_tua']]);
            $this->refreshOccupancy();
        });

        return back()->with('success', 'Data penghuni berhasil diperbarui.');
    }

    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'penghuni', 404);
        $data = $request->validate([
            'status_penghuni' => ['required', 'in:aktif,alumni,keluar'],
            'tanggal_keluar' => ['nullable', 'date', 'required_if:status_penghuni,keluar'],
            'alasan_keluar' => ['nullable', 'string', 'max:1000', 'required_if:status_penghuni,keluar'],
        ]);
        if ($data['status_penghuni'] !== 'aktif') {
            DB::transaction(function () use ($user, $data): void {
                $user->load(['kamar', 'pendaftaran.berkas']);
                $pendaftaran = $user->pendaftaran;
                Alumni::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'nik' => $pendaftaran?->nik,
                    'tanggal_lahir' => $pendaftaran?->tanggal_lahir,
                    'asal_daerah' => $pendaftaran?->asal_daerah,
                    'alamat_asal' => $pendaftaran?->alamat_asal,
                    'institusi_pendidikan' => $pendaftaran?->institusi_pendidikan,
                    'semester' => $user->semester,
                    'no_hp_orang_tua' => $pendaftaran?->no_hp_orang_tua,
                    'nama_orang_tua' => $pendaftaran?->nama_orang_tua,
                    'nomor_kamar' => $user->kamar?->nomor_kamar,
                    'status_penghuni' => $data['status_penghuni'],
                    'tanggal_masuk' => $user->tanggal_masuk,
                    'tanggal_keluar' => $data['tanggal_keluar'] ?? now()->toDateString(),
                    'alasan_keluar' => $data['alasan_keluar'] ?? null,
                    'id_asrama' => $user->id_asrama,
                    'berkas' => $pendaftaran?->berkas?->map(fn ($berkas) => ['jenis' => $berkas->jenis_berkas, 'file_path' => $berkas->file_path])->values()->all(),
                ]);
                $user->update(['kamar_id' => null]);
                $user->delete();
                $this->refreshOccupancy();
            });

            return back()->with('success', 'Penghuni dipindahkan ke Kelola Alumni.');
        }

        $user->update(['status_penghuni' => 'aktif', 'tanggal_keluar' => null, 'alasan_keluar' => null]);
        return back()->with('success', 'Status penghuni berhasil diperbarui.');
    }

    public function import(Request $request): RedirectResponse
    {
        $file = $request->validate(['file' => ['required', 'file', 'mimes:csv,txt,xlsx', 'max:5120']])['file'];
        $rows = $this->readImportRows($file->getRealPath(), $file->getClientOriginalExtension());
        $headers = array_map(fn ($value) => strtolower(trim((string) $value)), array_shift($rows) ?: []);
        $required = ['nama', 'email', 'nik', 'institusi pendidikan', 'semester', 'id asrama', 'tanggal masuk', 'jenjang'];
        abort_if(count(array_diff($required, $headers)) > 0, 422, 'Kolom impor wajib: '.implode(', ', $required));
        $count = 0;
        foreach ($rows as $row) {
            if (count(array_filter($row, fn ($value) => trim((string) $value) !== '')) === 0) {
                continue;
            }
            $item = array_combine($headers, array_pad($row, count($headers), null));
            $name = trim((string) ($item['nama'] ?? ''));
            $email = trim((string) ($item['email'] ?? ''));
            if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }
            $user = User::updateOrCreate(['email' => $email], [
                'name' => $name,
                'password' => 'password',
                'role' => 'penghuni',
                'semester' => (int) $item['semester'],
                'id_asrama' => trim((string) $item['id asrama']) ?: null,
                'tanggal_masuk' => $item['tanggal masuk'] ?: now()->toDateString(),
                'tanggal_berakhir' => $this->calculateEndDate($item['jenjang'] ?? null, $item['tanggal masuk'] ?: now()->toDateString()),
                'status_penghuni' => 'aktif',
            ]);
            $user->pendaftaran()->updateOrCreate([], [
                'nama_lengkap' => $name,
                'nik' => trim((string) ($item['nik'] ?? '')),
                'institusi_pendidikan' => trim((string) ($item['institusi pendidikan'] ?? '')),
                'semester' => (int) $item['semester'],
                'jenjang' => trim((string) ($item['jenjang'] ?? '')),
                'status_pendaftaran' => 'diterima',
            ]);
            $count++;
        }
        return back()->with('success', $count.' data penghuni berhasil diimpor.');
    }

    private function readImportRows(string $path, string $extension): array
    {
        if (strtolower($extension) === 'csv' || strtolower($extension) === 'txt') {
            $handle = fopen($path, 'r');
            $rows = [];
            while (($row = fgetcsv($handle)) !== false) {
                $rows[] = $row;
            }
            fclose($handle);
            return $rows;
        }

        if (!class_exists('ZipArchive')) {
            abort(422, 'Server belum mendukung pembacaan XLSX. Gunakan CSV UTF-8.');
        }
        $zip = new \ZipArchive();
        abort_unless($zip->open($path) === true, 422, 'File Excel tidak dapat dibaca.');
        $shared = [];
        if (($sharedXml = $zip->getFromName('xl/sharedStrings.xml')) !== false) {
            $sharedRoot = simplexml_load_string($sharedXml);
            foreach ($sharedRoot->si as $item) {
                $shared[] = (string) ($item->t ?: implode('', array_map('strval', iterator_to_array($item->r->t ?? []))));
            }
        }
        $sheet = simplexml_load_string((string) $zip->getFromName('xl/worksheets/sheet1.xml'));
        $rows = [];
        foreach ($sheet->sheetData->row as $row) {
            $values = [];
            foreach ($row->c as $cell) {
                $value = (string) $cell->v;
                if ((string) $cell['t'] === 's' && isset($shared[(int) $value])) {
                    $value = $shared[(int) $value];
                }
                $values[] = $value;
            }
            $rows[] = $values;
        }
        $zip->close();
        return $rows;
    }

    public function exportPdf(): Response
    {
        $pdf = Pdf::loadView('admin.penghuni.pdf', ['penghunis' => User::with(['kamar', 'pendaftaran'])->where('role', 'penghuni')->orderBy('name')->get()])->setPaper('a4', 'landscape');
        return $pdf->download('data-penghuni-'.now()->format('YmdHis').'.pdf');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_unless($user->role === 'penghuni', 404);
        DB::transaction(function () use ($user): void {
            $user->load(['kamar', 'pendaftaran.berkas']);
            $pendaftaran = $user->pendaftaran;
            Alumni::create([
                'name' => $user->name,
                'email' => $user->email,
                'nik' => $pendaftaran?->nik,
                'tanggal_lahir' => $pendaftaran?->tanggal_lahir,
                'asal_daerah' => $pendaftaran?->asal_daerah,
                'alamat_asal' => $pendaftaran?->alamat_asal,
                'institusi_pendidikan' => $pendaftaran?->institusi_pendidikan,
                'semester' => $user->semester,
                'no_hp_orang_tua' => $pendaftaran?->no_hp_orang_tua,
                'nama_orang_tua' => $pendaftaran?->nama_orang_tua,
                'nomor_kamar' => $user->kamar?->nomor_kamar,
                'berkas' => $pendaftaran?->berkas?->map(fn ($berkas) => [
                    'jenis' => $berkas->jenis_berkas,
                    'file_path' => $berkas->file_path,
                ])->values()->all(),
            ]);
            $user->update(['kamar_id' => null]);
            $user->delete();
            $this->refreshOccupancy();
        });

        return back()->with('success', 'Penghuni berhasil dipindahkan ke Kelola Alumni.');
    }

    public function export(): Response
    {
        $rows = User::with(['kamar', 'pendaftaran'])->where('role', 'penghuni')->orderBy('name')->get();
        $html = '<html><head><meta charset="UTF-8"><style>body{font-family:Arial}table{border-collapse:collapse;width:100%}th{background:#17201d;color:#fff}th,td{border:1px solid #d9d9d9;padding:8px;text-align:left}tr:nth-child(even){background:#f5f5f5}</style></head><body><h2>Data Penghuni SIAP-Kukar</h2><table><thead><tr><th>No</th><th>ID Asrama</th><th>Nama</th><th>Email</th><th>NIK</th><th>Institusi</th><th>Jenjang</th><th>Semester</th><th>Kamar</th><th>Tanggal Masuk</th><th>Masa Berakhir</th><th>Status</th><th>Tanggal Keluar</th><th>Alasan Keluar</th></tr></thead><tbody>';
        foreach ($rows as $index => $user) {
            $values = [$index + 1, $user->id_asrama, $user->name, $user->email, $user->pendaftaran?->nik, $user->pendaftaran?->institusi_pendidikan, $user->pendaftaran?->jenjang, $user->semester, $user->kamar?->nomor_kamar, $user->tanggal_masuk?->format('d-m-Y'), $user->tanggal_berakhir?->format('d-m-Y'), ucfirst($user->status_penghuni), $user->tanggal_keluar?->format('d-m-Y'), $user->alasan_keluar];
            $html .= '<tr>'.collect($values)->map(fn ($value) => '<td>'.e((string) $value).'</td>')->implode('').'</tr>';
        }
        $html .= '</tbody></table></body></html>';
        return response($html)->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')->header('Content-Disposition', 'attachment; filename="data-penghuni.xls"');
    }

    private function calculateEndDate(?string $jenjang, string $tanggalMasuk): string
    {
        $years = str_contains(strtolower((string) $jenjang), 's2') || str_contains(strtolower((string) $jenjang), 'magister') ? 2 : 4;
        return \Carbon\Carbon::parse($tanggalMasuk)->addYears($years)->toDateString();
    }

    private function validateRoomCapacity(?int $kamarId, ?int $userId = null): void
    {
        if ($kamarId === null) {
            return;
        }

        $kamar = Kamar::findOrFail($kamarId);
        $occupied = User::where('kamar_id', $kamarId)->when($userId, fn ($query) => $query->where('id', '!=', $userId))->count();

        if ($occupied >= $kamar->kapasitas) {
            throw ValidationException::withMessages(['kamar_id' => 'Kamar sudah penuh. Maksimal 2 penghuni per kamar.']);
        }
    }

    private function refreshOccupancy(): void
    {
        Kamar::query()->each(function (Kamar $kamar): void {
            $kamar->update(['terisi' => User::where('kamar_id', $kamar->id)->count()]);
        });
    }
}
