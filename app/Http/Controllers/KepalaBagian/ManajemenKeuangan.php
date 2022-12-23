<?php

namespace App\Http\Controllers\KepalaBagian;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;
use Illuminate\Support\Facades\Validator;

class ManajemenKeuangan extends Controller
{
    private $request;

    public function __construct()
    {
        $this->request = app(Request::class);
    }

    public function perencanaanViewGetAll()
    {
        $info = [
            'title' => 'Perencanaan',
            'site_active' => 'Perencanaan',
        ];
        $misi = DB::SELECT("SELECT * FROM misi ORDER BY periode DESC, created_at ASC");
        return view('pages._manajemenKeuangan.perencanaan.viewGetAll', compact('info','misi'));
    }

    public function perencanaanApiGetAll()
    {
        try {
            $id_misi = " AND kdiv.id_divisi = '".Auth::user()->id_divisi."' AND prog.id_misi = '".$this->request->misi."' ";
            $apiGetAll = DB::select("
                SELECT
                    kgt.id_kegiatan,
                    kgt.nm_kegiatan,
                    CASE
                        WHEN kgt.a_aktif = '1' THEN
                        'Aktif' ELSE 'Non Aktif'
                    END AS a_aktif,
                    CASE
                        WHEN verif.a_verif_kepala_uud = '1' THEN 'Menunggu Verifikator UDD'
                        WHEN verif.a_verif_wilayah = '1' THEN 'Menunggu Verifikator Wilayah'
                        ELSE 'Terverifikasi untuk dilaksanakan'
                    END AS verifikator
                FROM
                    kegiatan AS kgt
                JOIN
                    program AS prog ON prog.id_program=kgt.id_program
                JOIN
                    kegiatan_divisi AS kdiv ON kdiv.id_kegiatan=kgt.id_kegiatan
                LEFT JOIN (
                    SELECT
                        id_kegiatan_divisi, a_verif_rba AS a_verif_kepala_uud, a_verif_wilayah
                    FROM
                        rba
                ) AS verif ON verif.id_kegiatan_divisi=kdiv.id_kegiatan_divisi
                WHERE
                    kgt.a_aktif = '1' AND prog.a_aktif = '1'
                    ".$id_misi."
                ORDER BY
                    kgt.created_at ASC
            ");
            return DaTables::of($apiGetAll)->make(true);
        } catch (QueryException $e) {
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            return [
                'status' => false,
                'latency' => AppLatency(),
                'message' => 'QueryException',
                'error' => null,
                'response' => null
            ];
        } catch (Exception $e) {
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

    public function penganggaranViewGetAll()
    {
        $info = [
            'title' => 'Penganggaran',
            'site_active' => 'Penganggaran',
            'kegiatan' => [
                'Program'
            ]
        ];
        $akun = \DB::SELECT("
            SELECT
                akn.id_akun,
                CONCAT(akn.elemen, akn.sub_elemen, akn.jenis, akn.no_akun) AS no_akun,
                akn.nm_akun
            FROM
                akun AS akn
            WHERE
                akn.elemen='5'
                AND akn.no_akun > '0000'
                AND akn.deleted_at IS NULL
        ");
        return view('pages._manajemenKeuangan.penganggaran.viewGetAll', compact('info','akun'));
    }

    public function penganggaranApiGetAll()
    {
        try {
            if($this->request->program == "Program") {
                $apiGetAll = DB::select("
                    SELECT
                        kdiv.id_kegiatan_divisi,
                        kgt.id_kegiatan,
                        kgt.nm_kegiatan,
                        prog.nm_program,
                        dvs.nm_divisi,
                        rba.total AS total_anggaran,
                        CASE
                            WHEN kgt.a_aktif = '1' THEN
                            'Aktif' ELSE'Non Aktif'
                        END AS a_aktif
                    FROM
                        kegiatan AS kgt
                    JOIN
                        program AS prog ON prog.id_program=kgt.id_program
                    JOIN
                        kegiatan_divisi AS kdiv ON kdiv.id_kegiatan=kgt.id_kegiatan
                    JOIN
                        divisi AS dvs ON dvs.id_divisi=kdiv.id_divisi
                    LEFT JOIN (
                        SELECT
                            rba.id_kegiatan_divisi, rba.a_verif_wilayah, SUM(detrba.total) AS total
                        FROM
                            rba
                        JOIN
                            detail_rba AS detrba ON detrba.id_rba=rba.id_rba
                        WHERE
                            detrba.deleted_at IS NULL
                        GROUP BY
                            rba.id_kegiatan_divisi, rba.a_verif_wilayah
                    ) AS rba ON rba.id_kegiatan_divisi=kdiv.id_kegiatan_divisi
                    WHERE
                        kdiv.id_divisi = '".Auth::user()->id_divisi."' AND kgt.a_aktif = '1' AND prog.periode = '".$this->request->tahun."' AND rba.a_verif_wilayah = '2'
                    ORDER BY
                        kgt.created_at ASC
                ");
            } else {
                $apiGetAll = [];
            }
            return DaTables::of($apiGetAll)->make(true);
        } catch (QueryException $e) {
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            return [
                'status' => false,
                'latency' => AppLatency(),
                'message' => 'QueryException',
                'error' => null,
                'response' => null
            ];
        } catch (Exception $e) {
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

    public function penganggaranApiUpdate($noApi = null)
    {
        try {
            DB::beginTransaction();
            $no_api = $noApi ?? $this->request->no_api;
            $rules = [
                'id_akun' => 'required|uuid',
                'nm_kegiatan' => 'required',
                'biaya_kegiatan' => 'required',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                if ($no_api) {
                    return back()->withInput()->withErrors($validator);
                } else {
                    return [
                        'status' => false,
                        'latency' => AppLatency(),
                        'message' => 'BadRequest',
                        'error' => $validator->errors(),
                        'response' => null
                    ];
                }
            }

            $id_akun = $this->request->id_akun;
            $nm_kegiatan = $this->request->nm_kegiatan;
            $biaya_kegiatan = $this->request->biaya_kegiatan;
            $a_aktif = 1;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;

            //

            DB::commit();
            if ($no_api) {
                return back()->with('success', 'Data Berhasil Ditambah!');
            } else {
                return [
                    'status' => true,
                    'latency' => AppLatency(),
                    'message' => 'Updated',
                    'error' => null,
                    'response' => null
                ];
            }
        } catch (QueryException $e) {
            DB::rollBack();
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            if ($no_api) {
                return back()->with('error', 'Internal Server Error | QueryException');
            } else {
                return [
                    'status' => false,
                    'latency' => AppLatency(),
                    'message' => 'QueryException',
                    'error' => null,
                    'response' => null
                ];
            }
        } catch (Exception $e) {
            DB::rollBack();
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            if ($no_api) {
                return back()->with('error', 'Internal Server Error | Exception');
            } else {
                return [
                    'status' => false,
                    'latency' => AppLatency(),
                    'message' => 'Exception',
                    'error' => null,
                    'response' => null
                ];
            }
        }
    }
}
