<?php

namespace App\Http\Controllers;

use App\Models\laporan_kerusakanModel;
use App\Models\pembelianModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class rekapController extends Controller
{
    // ========================================================================
    // FUNCTION: periode
    // TUJUAN: Membaca rentang tanggal ?dari=YYYY-MM-DD&sampai=YYYY-MM-DD.
    //         Jika kosong, default = tanggal 1 bulan ini s/d hari ini.
    // Penjelasan: $request->validate() dipanggil DI LUAR try/catch agar
    //             pesan error validasi tampil di form, bukan tertangkap catch.
    // ========================================================================
    private function periode(Request $request): array
    {
        $request->validate([
            'dari' => 'nullable|date',
            'sampai' => 'nullable|date|after_or_equal:dari',
        ], [
            'dari.date' => 'Tanggal awal tidak valid',
            'sampai.date' => 'Tanggal akhir tidak valid',
            'sampai.after_or_equal' => 'Tanggal akhir tidak boleh lebih awal dari tanggal awal',
        ]);

        $awal = $request->dari ? Carbon::parse($request->dari) : now()->startOfMonth();
        $akhir = $request->sampai ? Carbon::parse($request->sampai) : now();

        return [$awal->startOfDay(), $akhir->endOfDay()];
    }

    // ========================================================================
    // FUNCTION: laporan
    // TUJUAN: Mencetak rekap laporan kerusakan dalam rentang tanggal ke PDF
    // ========================================================================
    public function laporan(Request $request)
    {
        [$awal, $akhir] = $this->periode($request);

        try {

            $laporan_all = laporan_kerusakanModel::with('pelapor')
                ->whereBetween('tanggal_lapor', [$awal->toDateString(), $akhir->toDateString()])
                ->orderBy('tanggal_lapor')
                ->get();

            // Jumlah laporan per status untuk ringkasan di bagian atas PDF
            $ringkasan = [];
            foreach (['Menunggu', 'Diverifikasi', 'Diperbaiki', 'Selesai'] as $status) {
                $ringkasan[$status] = $laporan_all->where('status_laporan', $status)->count();
            }

            $pdf = Pdf::loadView('admin.print.rekap_laporan', compact('laporan_all', 'ringkasan', 'awal', 'akhir'))
                ->setPaper('a4', 'landscape');

            return $pdf->stream('rekap-laporan-' . $awal->format('Y-m-d') . '_sd_' . $akhir->format('Y-m-d') . '.pdf');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e));
        }
    }

    // ========================================================================
    // FUNCTION: pembelian
    // TUJUAN: Mencetak rekap pembelian barang/jasa dalam rentang tanggal ke PDF
    // ========================================================================
    public function pembelian(Request $request)
    {
        [$awal, $akhir] = $this->periode($request);

        try {

            $pembelian_all = pembelianModel::with(['mitra_bisnis', 'laporan'])
                ->whereBetween('created_at', [$awal, $akhir])
                ->orderBy('created_at')
                ->get();

            // Total hanya dihitung dari pembelian yang sudah dibayar
            $total_dibayar = $pembelian_all->whereNotNull('bukti_bayar')->sum('total_harga');

            $pdf = Pdf::loadView('admin.print.rekap_pembelian', compact('pembelian_all', 'total_dibayar', 'awal', 'akhir'))
                ->setPaper('a4', 'landscape');

            return $pdf->stream('rekap-pembelian-' . $awal->format('Y-m-d') . '_sd_' . $akhir->format('Y-m-d') . '.pdf');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e));
        }
    }
}
