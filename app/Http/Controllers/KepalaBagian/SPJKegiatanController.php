<?php

namespace App\Http\Controllers\KepalaBagian;

use App\Models\Spj;
use App\Models\DetailSpj;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class SPJKegiatanController extends Controller
{   
    private $request;
    private $mSpj;
    private $mDetailSpj;
    public function __construct()
    {
        $this->request = app(Request::class);
        $this->mSpjKegiatan = app(Spj::class);
        $this->mDetailSpj = app(DetailSpj::class);
    }

    public function apiGetAll()
    {
        $apiGetAll = DB::select("
            SELECT
                DISTINCT ON (bku.id_laksana_kegiatan) bku.id_laksana_kegiatan,
                bku.id_bku,
                bku.id_divisi,
                lkgt.urutan_laksana_kegiatan,
                kgt.nm_kegiatan,
                CONCAT('[ ', pgm.periode ,' ] ', pgm.nm_program) AS nm_program,
                CONCAT('[ ', msi.periode ,' ] ', msi.nm_misi) AS nm_misi,
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
                JOIN kegiatan AS kgt ON kgt.id_kegiatan = kdiv.id_kegiatan
                AND kgt.deleted_at IS NULL
                JOIN program AS pgm ON pgm.id_program = kgt.id_program
                AND pgm.deleted_at IS NULL
                LEFT JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                AND msi.deleted_at IS NULL
            WHERE
                bku.deleted_at IS NULL
                AND bku.id_divisi = '" . Auth::user()->id_divisi . "'
        ");
        return DaTables::of($apiGetAll)
            ->editColumn('total_masuk', function ($ed) {
                return number_to_currency($ed->total_masuk, 0);
            })
            ->editColumn('total_keluar', function ($ed) {
                return number_to_currency($ed->total_keluar, 0);
            })
            ->addColumn('total_saldo', function ($ed) {
                return number_to_currency_without_rp(($ed->total_masuk - $ed->total_keluar), 0);
            })
            ->make(true);
    }

    public function apiCreateDetailSpj()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_spj' => 'required',
                'id_detail_laksana_kegiatan' => 'required',
                'id_akun' => 'required',
                'total' => 'required',
                // 'dokumen' => 'required'
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

            $id_detail_spj = guid();
            $id_spj = $this->request->id_spj;
            $id_detail_laksana_kegiatan = $this->request->id_detail_laksana_kegiatan;
            $id_akun = $this->request->id_akun;
            $total = $this->request->total;
            $created_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mDetailSpj->create([
                'id_detail_spj' => $id_detail_spj,
                'id_spj' => $id_spj,
                'id_detail_laksana_kegiatan' => $id_detail_laksana_kegiatan,
                'id_akun' => $id_akun,
                'total' => $total,
                'created_at' => $created_at,
                'id_updater' => $id_updater,
            ]);

            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Created',
                'error' => null,
                'response' => ['id_detail_spj' => $id_detail_spj]
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
            'title' => 'SPJ Kegiatan',
            'site_active' => 'SPJKegiatan',
        ];
        return view('pages._kepalaBagian.spj.viewGetAll', compact('info'));
    }

    public function viewDetail()
    {
        $id_laksana_kegiatan = $this->request->id_laksana_kegiatan;

        $info = [
            'title' => 'Detail SPJ Kegiatan',
            'site_active' => 'SPJKegiatan',
        ];

        $bku = DB::select("
            SELECT
                DISTINCT ON (bku.id_laksana_kegiatan) bku.id_laksana_kegiatan,
                bku.id_bku,
                bku.id_divisi,
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
                akun.no_akun,
                akun.nm_akun
            FROM
                detail_laksana_kegiatan AS dlaks
                JOIN detail_rba AS drba ON drba.id_detail_rba=dlaks.id_detail_rba
                JOIN akun ON akun.id_akun=drba.id_akun
            WHERE
                dlaks.id_laksana_kegiatan='".$id_laksana_kegiatan."'
        ");

        $akun = DB::select("SELECT * FROM akun WHERE SUBSTR(no_akun_induk,1,1) = '5'");
        $spj = \App\Models\SPJ::where('id_laksana_kegiatan', $id_laksana_kegiatan)->first();

        return view('pages._kepalaBagian.spj.viewDetail', compact('info', 'bku', 'detLaks', 'akun', 'spj'));
    }
}
