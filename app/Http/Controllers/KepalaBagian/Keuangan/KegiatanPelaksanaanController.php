<?php

namespace App\Http\Controllers\KepalaBagian\Keuangan;

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

class KegiatanPelaksanaanController extends Controller
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
                    WHEN '2' THEN 'Disetujui Kabag. Keuangan'
                    WHEN '3' THEN 'Ditolak Kabag. Keuangan'
                    ELSE 'Belum Diverifikasi Kabag. Keuangan'
                END AS a_verif_kabag_keuangan,
                div.nm_divisi,
                kgt.nm_kegiatan,
                pgm.nm_program,
                msi.nm_misi
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
                LEFT JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                AND msi.deleted_at IS NULL
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

            if($a_verif_kabag_keuangan=='3') {
                LaksanaKegiatan::whereIn('id_laksana_kegiatan', $id_laksana_kegiatan)->update([
                    'tgl_ajuan' => null
                ]);
                DB::commit();
                return [
                    'status' => true,
                    'latency' => AppLatency(),
                    'message' => 'Updated',
                    'error' => null,
                    'response' => ['id_laksana_kegiatan' => $id_laksana_kegiatan]
                ];
            }

            $laksanaKegiatan = \DB::SELECT("
                SELECT
                    lkeg.id_laksana_kegiatan,
                    lkeg.tgl_verif_kabag_keuangan,
                    kdiv.id_divisi,
                    prog.id_misi,
                    SUM(dlkeg.total) AS total_anggaran_terpakai,
                    (
                        SELECT
                            (SUM(bku.masuk) - SUM(bku.keluar))
                        FROM
                            laksana_kegiatan AS laks
                            JOIN bku ON bku.id_laksana_kegiatan=laks.id_laksana_kegiatan AND bku.deleted_at IS NULL
                        WHERE
                            laks.deleted_at IS NULL
                            AND laks.a_verif_kabag_keuangan='2'
                            AND laks.id_kegiatan_divisi=lkeg.id_kegiatan_divisi
                    ) AS sisa
                FROM
                    laksana_kegiatan AS lkeg
                    JOIN detail_laksana_kegiatan AS dlkeg ON dlkeg.id_laksana_kegiatan=lkeg.id_laksana_kegiatan AND dlkeg.deleted_at IS NULL
                    JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan_divisi=lkeg.id_kegiatan_divisi AND kdiv.deleted_at IS NULL
                    JOIN kegiatan AS kgt ON kgt.id_kegiatan=kdiv.id_kegiatan AND kgt.deleted_at IS NULL
                    JOIN program AS prog ON prog.id_program=kgt.id_program AND prog.deleted_at IS NULL
                WHERE
                    lkeg.deleted_at IS NULL
                    AND lkeg.id_laksana_kegiatan IN ( ? )
                GROUP BY
                    lkeg.id_laksana_kegiatan,
                    lkeg.tgl_verif_kabag_keuangan,
                    kdiv.id_divisi,
                    sisa,
                    prog.id_misi
            ", $id_laksana_kegiatan);

            foreach ($laksanaKegiatan as $lk) {
                Bku::create([
                    'id_bku' => guid(),
                    'id_divisi' => $lk->id_divisi,
                    'id_laksana_kegiatan' => $lk->id_laksana_kegiatan,
                    'id_akun' => '59ff0cc2-685a-4c84-866b-f8823a8b63b1',
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
            'title' => 'Pelaksanaan Kegiatan',
            'site_active' => 'KegiatanPelaksana',
        ];
        $kegiatan = DB::select("
            SELECT
                kgt.id_kegiatan,
                kgt.nm_kegiatan
            FROM
                kegiatan AS kgt
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
        return view('pages._kepalaBagian._keuangan.kegiatanPelaksana.viewGetAll', compact('info', 'kegiatan', 'divisi'));
    }

    public function viewDetail()
    {
        $id_laksana_kegiatan = $this->request->id_laksana_kegiatan;
        $info = [
            'title' => 'Detail Pelaksanaan Kegiatan',
            'site_active' => 'KegiatanPelaksana',
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
                lkgt.lokasi,
                lkgt.waktu_pelaksanaan,
                lkgt.waktu_selesai,
                CASE
                    lkgt.a_verif_kabag_keuangan
                    WHEN '2' THEN 'Disetujui Kabag. Keuangan'
                    WHEN '3' THEN 'Ditolak Kabag. Keuangan'
                    ELSE 'Belum Diverifikasi Kabag. Keuangan'
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
            ORDER BY no_akun ASC
        ");

        return view('pages._kepalaBagian._keuangan.kegiatanPelaksana.viewGetAllLaksanaDetail', compact('info', 'kegiatan', 'detailLaks', 'akun'));
    }
}
