<?php

namespace App\Http\Controllers\BendaharaPengeluaran;

use App\Http\Controllers\Controller;
use App\Models\Bku;
use App\Models\LaksanaKegiatan;
use App\Models\Spj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KegiatanRutinPelaksanaanController extends Controller
{
    private $request;

    public function __construct()
    {
        $this->request = app(Request::class);
    }

    public function apiGetAll()
    {
        $a_verif_kabag_keuangan = ($this->request->a_verif_kabag_keuangan) ? " AND  lkgt.a_verif_kabag_keuangan = '" . $this->request->a_verif_kabag_keuangan . "'" : "";
        $id_kegiatan = ($this->request->id_kegiatan) ? " AND  kgt.id_kegiatan = '" . $this->request->id_kegiatan . "'" : "";
        $id_divisi = ($this->request->id_divisi) ? " AND  div.id_divisi = '" . $this->request->id_divisi . "'" : "";
        $apiGetAll = DB::select("
            SELECT
                lkgt.id_laksana_kegiatan,
                lkgt.id_kegiatan_divisi,
                lkgt.urutan_laksana_kegiatan,
                lkgt.waktu_pelaksanaan,
                lkgt.waktu_selesai,
                CASE
                    lkgt.a_verif_kabag_keuangan
                    WHEN '2' THEN 'Disetujui Bend. Pengeluaran'
                    WHEN '3' THEN 'Ditolak Bend. Pengeluaran'
                    ELSE 'Belum Diverifikasi Bend. Pengeluaran'
                END AS a_verif_kabag_keuangan,
                div.nm_divisi,
                kgt.nm_kegiatan,
                pgm.nm_program,
                pgm.periode AS periode_program
            FROM
                laksana_kegiatan AS lkgt
                JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                AND kdiv.deleted_at IS NULL
                JOIN divisi AS div ON div.id_divisi = kdiv.id_divisi
                AND kdiv.deleted_at IS NULL
                JOIN kegiatan AS kgt ON kgt.id_kegiatan = kdiv.id_kegiatan
                AND kgt.deleted_at IS NULL
                JOIN program AS pgm ON pgm.id_program = kgt.id_program
                AND pgm.deleted_at IS NULL
                AND pgm.id_misi IS NULL
            WHERE
                lkgt.deleted_at IS NULL
                AND lkgt.tgl_ajuan IS NOT NULL
                " . $a_verif_kabag_keuangan . "
                " . $id_kegiatan . "
                " . $id_divisi . "
            ORDER BY
                div.nm_divisi, lkgt.tgl_ajuan ASC
        ");
        return DaTables::of($apiGetAll)
            ->editColumn('waktu_pelaksanaan', function ($clm) {
                return tglWaktuIndonesia($clm->waktu_pelaksanaan);
            })
            ->editColumn('waktu_selesai', function ($clm) {
                return tglWaktuIndonesia($clm->waktu_pelaksanaan);
            })
            ->editColumn('a_verif_kabag_keuangan', function ($clm) {
                return status_verification_color($clm->a_verif_kabag_keuangan);
            })
            ->rawColumns(['waktu_pelaksanaan', 'waktu_selesai', 'a_verif_kabag_keuangan'])
            ->make(true);
    }

    public function apiUpdate()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_laksana_kegiatan' => 'required',
                'a_verif_kabag_keuangan' => 'required',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return [
                    'status' => false,
                    'latency' => AppLatency(),
                    'message' => 'BadRequest',
                    'error' => $validator->errors(),
                    'response' => null
                ];
            }

            $id_laksana_kegiatan = $this->request->id_laksana_kegiatan;
            $a_verif_kabag_keuangan = $this->request->a_verif_kabag_keuangan;
            $tgl_verif_kabag_keuangan = now();
            $id_verif_kabag_keuangan = Auth::user()->id_user;
            $catatan = $this->request->catatan;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;

            LaksanaKegiatan::whereIn('id_laksana_kegiatan', $id_laksana_kegiatan)->update([
                'a_verif_kabag_keuangan' => $a_verif_kabag_keuangan,
                'tgl_verif_kabag_keuangan' => $tgl_verif_kabag_keuangan,
                'id_verif_kabag_keuangan' => $id_verif_kabag_keuangan,
                'catatan' => $catatan,
                'updated_at' => $updated_at,
                'id_updater' => $id_updater,
            ]);

            $laksanaKegiatan = DB::select("
                SELECT
                    lkgt.id_laksana_kegiatan,
                    lkgt.tgl_verif_kabag_keuangan,
                    kdiv.id_divisi,
                    (
                        SELECT
                            SUM(dlkgt.total)
                        FROM
                            detail_laksana_kegiatan AS dlkgt
                            JOIN laksana_kegiatan AS llkgt ON dlkgt.id_laksana_kegiatan = llkgt.id_laksana_kegiatan
                            AND llkgt.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                            AND llkgt.deleted_at IS NULL
                        WHERE
                            dlkgt.deleted_at IS NULL
                    ) AS total_anggaran_terpakai,
                    (
                        SELECT
                            SUM(drba.total)
                        FROM
                            rba AS rba
                            JOIN detail_rba AS drba ON drba.id_rba = rba.id_rba
                            AND drba.deleted_at IS NULL
                        WHERE
                            rba.deleted_at IS NULL
                            AND rba.a_verif_wilayah = '2'
                            AND rba.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                    ) AS total_anggaran_tersedia
                FROM
                    laksana_kegiatan AS lkgt
                    JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                    AND kdiv.deleted_at IS NULL
                WHERE
                    lkgt.deleted_at IS NULL
                    AND lkgt.id_laksana_kegiatan IN ( ? )
            ", $id_laksana_kegiatan);

            foreach ($laksanaKegiatan as $lk) {
                Bku::create([
                    'id_bku' => guid(),
                    'id_divisi' => $lk->id_divisi,
                    'id_laksana_kegiatan' => $lk->id_laksana_kegiatan,
                    'id_akun' => \App\Models\Akun::where('nm_akun', 'Kas Kecil')->pluck('id_akun')[0],
                    'tanggal' => $lk->tgl_verif_kabag_keuangan,
                    'masuk' => $lk->total_anggaran_terpakai,
                    'keluar' => 0,
                    'saldo' => $lk->total_anggaran_terpakai,
                    'created_at' => $updated_at,
                    'id_updater' => $id_updater,
                ]);

                Spj::create([
                    'id_spj' => guid(),
                    'id_laksana_kegiatan' => $lk->id_laksana_kegiatan,
                    'created_at' => $updated_at,
                    'id_updater' => $id_updater,
                ]);
            }

            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Updated',
                'error' => null,
                'response' => ['id_laksana_kegiatan' => $id_laksana_kegiatan]
            ];
        } catch (QueryException $e) {
            DB::rollBack();
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            return [
                'status' => false,
                'latency' => AppLatency(),
                'message' => 'QueryException',
                'error' => null,
                'response' => null
            ];
        } catch (Exception $e) {
            DB::rollBack();
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            return [
                'status' => false,
                'latency' => AppLatency(),
                'message' => 'Exception',
                'error' => null,
                'response' => null
            ];
        }
    }

    public function viewGetAll()
    {
        $info = [
            'title' => 'Pelaksanaan Kegiatan Rutin',
            'site_active' => 'KegiatanRutinPelaksana',
        ];
        $kegiatan = DB::select("
            SELECT
                kgt.id_kegiatan,
                kgt.nm_kegiatan
            FROM
                kegiatan AS kgt
                JOIN program AS pgm ON pgm.id_program = kgt.id_program
                AND pgm.deleted_at IS NULL
                AND pgm.a_aktif = '1'
                AND pgm.id_misi IS NULL
            WHERE
                kgt.deleted_at IS NULL
                AND kgt.a_aktif = '1'
            ORDER BY
                kgt.nm_kegiatan ASC
        ");
        $divisi = DB::select("
            SELECT
                div.id_divisi,
                div.nm_divisi
            FROM
                divisi AS div
            WHERE
                div.deleted_at IS NULL
                AND div.id_divisi != 'da138a9a-23ed-4941-932d-d1a457db0cdf'
            ORDER BY
                div.nm_divisi ASC
        ");
        return view('pages._bendaharaPengeluaran.kegiatanRutinPelaksana.viewGetAll', compact('info', 'kegiatan', 'divisi'));
    }

    public function viewDetail()
    {
        $id_laksana_kegiatan = $this->request->id_laksana_kegiatan;
        $info = [
            'title' => 'Detail Pelaksanaan Kegiatan Rutin',
            'site_active' => 'KegiatanRutinPelaksana',
        ];

        $kegiatan = DB::select("
            SELECT
                lkgt.id_laksana_kegiatan,
                div.nm_divisi,
                msi.nm_misi,
                pgm.nm_program,
                kgt.nm_kegiatan,
                lkgt.urutan_laksana_kegiatan,
                lkgt.tgl_ajuan,
                lkgt.waktu_pelaksanaan,
                lkgt.waktu_selesai,
                CASE
                    lkgt.a_verif_kabag_keuangan
                    WHEN '2' THEN 'Disetujui Bend. Pengeluaran'
                    WHEN '3' THEN 'Ditolak Bend. Pengeluaran'
                    ELSE 'Belum Diverifikasi Bend. Pengeluaran'
                END AS a_verif_kabag_keuangan,
                lkgt.tgl_verif_kabag_keuangan,
                lkgt.catatan,
                (
                    SELECT
                        SUM(dlkgt.total)
                    FROM
                        detail_laksana_kegiatan AS dlkgt
                        JOIN laksana_kegiatan AS llkgt ON dlkgt.id_laksana_kegiatan = llkgt.id_laksana_kegiatan
                        AND llkgt.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                        AND llkgt.deleted_at IS NULL
                    WHERE
                        dlkgt.deleted_at IS NULL
                ) AS total_anggaran_terpakai,
                (
                    SELECT
                        SUM(drba.total)
                    FROM
                        rba AS rba
                        JOIN detail_rba AS drba ON drba.id_rba = rba.id_rba
                        AND drba.deleted_at IS NULL
                    WHERE
                        rba.deleted_at IS NULL
                        AND rba.a_verif_wilayah = '2'
                        AND rba.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                ) AS total_anggaran_tersedia
            FROM
                laksana_kegiatan AS lkgt
                JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                AND kdiv.deleted_at IS NULL
                JOIN divisi AS div ON div.id_divisi = kdiv.id_divisi
                AND div.deleted_at IS NULL
                JOIN kegiatan AS kgt ON kgt.id_kegiatan = kdiv.id_kegiatan
                AND kgt.deleted_at IS NULL
                JOIN program AS pgm ON pgm.id_program = kgt.id_program
                AND pgm.deleted_at IS NULL
                LEFT JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                AND msi.deleted_at IS NULL
            WHERE
                lkgt.deleted_at IS NULL
                AND lkgt.id_laksana_kegiatan = '" . $id_laksana_kegiatan . "'
        ");

        $detailLaks = DB::select("
            SELECT
                dlkgt.id_detail_laksana_kegiatan,
                dlkgt.id_laksana_kegiatan,
                dlkgt.id_detail_rba,
                dlkgt.total,
                dlkgt.created_at,
                dlkgt.updated_at,
                dlkgt.deleted_at,
                dlkgt.id_updater,
                CONCAT(akn.elemen, akn.sub_elemen, akn.jenis, akn.no_akun) AS no_akun,
                akn.nm_akun
            FROM
                detail_laksana_kegiatan AS dlkgt
                JOIN detail_rba AS drba ON drba.id_detail_rba = dlkgt.id_detail_rba
                AND drba.deleted_at IS NULL
                JOIN akun AS akn ON akn.id_akun = drba.id_akun
                AND akn.deleted_at IS NULL
            WHERE
                dlkgt.deleted_at IS NULL
                AND dlkgt.id_laksana_kegiatan = '" . $id_laksana_kegiatan . "'
            ORDER BY dlkgt.created_at ASC
        ");

        $akun = DB::select("
            SELECT
                drba.id_detail_rba,
                akn.id_akun,
                CONCAT(akn.elemen, akn.sub_elemen, akn.jenis, akn.no_akun) AS no_akun,
                akn.no_akun_induk,
                akn.nm_akun
            FROM
                laksana_kegiatan AS lkgt
                JOIN rba AS rba ON rba.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                AND rba.deleted_at IS NULL
                JOIN detail_rba AS drba ON drba.id_rba = drba.id_rba
                AND drba.deleted_at IS NULL
                JOIN akun AS akn ON akn.id_akun = drba.id_akun
                AND akn.deleted_at IS NULL
            WHERE
                lkgt.deleted_at IS NULL
                AND lkgt.id_laksana_kegiatan = '" . $id_laksana_kegiatan . "'
            ORDER BY akn.no_akun ASC
        ");

        return view('pages._bendaharaPengeluaran.kegiatanRutinPelaksana.viewGetAllLaksanaDetail', compact('info', 'kegiatan', 'detailLaks', 'akun'));
    }
}
