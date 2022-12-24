<?php

namespace App\Http\Controllers\kepalabagian\Keuangan;

use App\Models\Spj;
use App\Models\DetailSpj;
use App\Models\Dokumen;
use App\Models\Bku;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class SPJKegiatanMonitoringController extends Controller
{
    private $request;
    private $mSpj;
    private $mDetailSpj;
    private $mDokumen;
    private $mBku;

    public function __construct()
    {
        $this->request = app(Request::class);
        $this->mSpj = app(Spj::class);
        $this->mDetailSpj = app(DetailSpj::class);
        $this->mDokumen = app(Dokumen::class);
        $this->mBku = app(Bku::class);
    }

    public function apiGetAll()
    {
        $status = ($this->request->a_verif) ? " AND spj.a_verif_kabag_keuangan='".$this->request->a_verif."' " : " ";
        $id_divisi = ($this->request->id_divisi) ? " AND kdiv.id_divisi='".$this->request->id_divisi."' " : " ";
        $id_kegiatan = ($this->request->id_kegiatan) ? " AND kdiv.id_kegiatan='".$this->request->id_kegiatan."' " : " ";
        $apiGetAll = DB::select("
            SELECT
                spj.id_spj,
                dvs.nm_divisi,
                kgt.nm_kegiatan,
                pr.nm_program,
                misi.nm_misi,
                lkeg.id_laksana_kegiatan,
                lkeg.urutan_laksana_kegiatan,
                CASE
                    WHEN spj.a_verif_kabag_keuangan = '1' THEN 'Belum Diverifikasi'
                    WHEN spj.a_verif_kabag_keuangan = '3' THEN 'Ditolak'
                    ELSE 'Disetujui' END AS status,
                (
                    SELECT
                        SUM(dspj.total)
                    FROM
                        detail_spj AS dspj
                    WHERE
                        dspj.id_spj=spj.id_spj
                        AND dspj.deleted_at IS NULL
                ) AS total_realisasi
            FROM
                spj
                JOIN laksana_kegiatan AS lkeg ON lkeg.id_laksana_kegiatan=spj.id_laksana_kegiatan AND lkeg.deleted_at IS NULL
                JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan_divisi=lkeg.id_kegiatan_divisi AND kdiv.deleted_at IS NULL
                JOIN divisi AS dvs ON dvs.id_divisi=kdiv.id_divisi AND dvs.deleted_at IS NULL
                JOIN kegiatan AS kgt ON kgt.id_kegiatan=kdiv.id_kegiatan AND kgt.deleted_at IS NULL
                JOIN program AS pr ON pr.id_program=kgt.id_program AND pr.deleted_at IS NULL
                LEFT JOIN misi ON misi.id_misi=pr.id_misi AND misi.deleted_at IS NULL
            WHERE
                spj.deleted_at IS NULL
                AND spj.a_verif_kabag_keuangan IS NOT NULL
                ".$status."
                ".$id_divisi."
                ".$id_kegiatan."
        ");
        return DaTables::of($apiGetAll)
            ->editColumn('total_realisasi', function ($ed) {
                return number_to_currency($ed->total_realisasi, 0);
            })
            ->make(true);
    }

    public function apiUpdate()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_spj' => 'required',
                'a_verif' => 'required',
                'id_divisi' => 'required',
                'id_laksana_kegiatan' => 'required'
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

            $id_spj = $this->request->id_spj;
            $a_verif_kabag_keuangan = $this->request->a_verif;
            $tgl_verif_kabag_keuangan = now();
            $id_verif_kabag_keuangan = Auth::user()->id_user;
            $catatan = $this->request->catatan;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;
            $id_divisi = $this->request->id_divisi;
            $id_laksana_kegiatan = $this->request->id_laksana_kegiatan;

            $this->mSpj->where('id_spj', $id_spj)->update([
                'a_verif_kabag_keuangan' => $a_verif_kabag_keuangan,
                'tgl_verif_kabag_keuangan' => $tgl_verif_kabag_keuangan,
                'id_verif_kabag_keuangan' => $id_verif_kabag_keuangan,
                'catatan' => $catatan,
                'updated_at' => $updated_at,
                'id_updater' => $id_updater,
            ]);

            if($a_verif_kabag_keuangan=='3') {
                $this->mSpj->where('id_spj', $id_spj)->update([
                    'tgl_ajuan' => null
                ]);
                DB::commit();
                return [
                    'status' => true,
                    'latency' => AppLatency(),
                    'message' => 'Updated',
                    'error' => null,
                    'response' => ['id_spj' => $id_spj]
                ];
            }

            $detSpj = $this->mDetailSpj->where('id_spj', $id_spj)->whereNull('deleted_at')->orderBy('created_at')->get();

            foreach ($detSpj as $item) {
                $sumMasuk = Bku::where('id_laksana_kegiatan', $id_laksana_kegiatan)->where('id_divisi', $id_divisi)->sum('masuk');
                $sumKeluar = Bku::where('id_laksana_kegiatan', $id_laksana_kegiatan)->where('id_divisi', $id_divisi)->sum('keluar');
                $saldo = $sumMasuk - $sumKeluar;
                Bku::create([
                    'id_bku' => guid(),
                    'id_divisi' => $id_divisi,
                    'id_laksana_kegiatan' => $id_laksana_kegiatan,
                    'id_akun' => $item->id_akun,
                    'tanggal' => $item->created_at,
                    'masuk' => 0,
                    'keluar' => $item->total,
                    'saldo' => ($saldo - $item->total),
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
                'response' => ['id_spj' => $id_spj]
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
            'title' => 'Monitoring SPJ Kegiatan',
            'site_active' => 'SPJKegiatanMonitoring',
        ];
        $divisi = DB::select("SELECT * FROM divisi WHERE deleted_at IS NULL ORDER BY nm_divisi ASC");
        $kegiatan = DB::select("SELECT kgt.* FROM kegiatan AS kgt JOIN program AS pr ON pr.id_program=kgt.id_program AND pr.deleted_at IS NULL WHERE kgt.deleted_at IS NULL ORDER BY nm_kegiatan ASC");
        return view('pages._kepalaBagian._keuangan.spj.viewGetAll', compact('info','divisi','kegiatan'));
    }
    
    public function viewDetail()
    {
        $id_laksana_kegiatan = $this->request->id_laksana_kegiatan;

        $info = [
            'title' => 'Detail SPJ Kegiatan',
            'site_active' => 'SPJKegiatanMonitoring',
        ];

        $bku = DB::select("
            SELECT
                DISTINCT ON (bku.id_laksana_kegiatan) bku.id_laksana_kegiatan,
                bku.id_bku,
                bku.id_divisi,
                dvs.nm_divisi,
                lkgt.urutan_laksana_kegiatan,
                kgt.nm_kegiatan,
                pgm.nm_program,
                msi.nm_misi,
                (
                    SELECT
                        SUM(bbku.masuk)
                    FROM
                        bku AS bbku
                    WHERE
                        bbku.id_laksana_kegiatan = bku.id_laksana_kegiatan
                        AND bbku.deleted_at IS NULL
                ) AS total_masuk,
                (
                    SELECT
                        SUM(bbbku.keluar)
                    FROM
                        bku AS bbbku
                    WHERE
                        bbbku.id_laksana_kegiatan = bku.id_laksana_kegiatan
                        AND bbbku.deleted_at IS NULL
                ) AS total_keluar
            FROM
                bku AS bku
                JOIN laksana_kegiatan AS lkgt ON lkgt.id_laksana_kegiatan = bku.id_laksana_kegiatan
                AND lkgt.deleted_at IS NULL
                JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                AND kdiv.deleted_at IS NULL
                JOIN divisi AS dvs ON dvs.id_divisi=kdiv.id_divisi AND dvs.deleted_at IS NULL
                JOIN kegiatan AS kgt ON kgt.id_kegiatan = kdiv.id_kegiatan
                AND kgt.deleted_at IS NULL
                JOIN program AS pgm ON pgm.id_program = kgt.id_program
                AND pgm.deleted_at IS NULL
                LEFT JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                AND msi.deleted_at IS NULL
            WHERE
                bku.deleted_at IS NULL
                AND bku.id_laksana_kegiatan = '" . $id_laksana_kegiatan . "'
        ");

        $detLaks = DB::select("
            SELECT
                dlaks.id_detail_laksana_kegiatan,
                drba.id_akun,
                dlaks.total,
                CONCAT(akun.elemen, akun.sub_elemen, akun.jenis, akun.no_akun) AS no_akun,
                akun.nm_akun
            FROM
                detail_laksana_kegiatan AS dlaks
                JOIN detail_rba AS drba ON drba.id_detail_rba=dlaks.id_detail_rba
                JOIN akun ON akun.id_akun=drba.id_akun
            WHERE
                dlaks.id_laksana_kegiatan='".$id_laksana_kegiatan."'
        ");

        $spj = \App\Models\SPJ::where('id_laksana_kegiatan', $id_laksana_kegiatan)->whereNull('deleted_at')->first();
        if($spj->a_verif_kabag_keuangan=='1') {
            $spj->a_verif_kabag_keuangan = 'Belum Diverifikasi';
        } else if ($spj->a_verif_kabag_keuangan=='3') {
            $spj->a_verif_kabag_keuangan = 'Ditolak';
        } else {
            $spj->a_verif_kabag_keuangan = 'Disetujui';
        }

        $totDetSpj = $this->mDetailSpj->where('id_spj', $spj->id_spj)->whereNull('deleted_at')->sum('total');

        return view('pages._kepalaBagian._keuangan.spj.viewDetail', compact('info', 'bku', 'detLaks', 'spj', 'totDetSpj'));
    }
}
