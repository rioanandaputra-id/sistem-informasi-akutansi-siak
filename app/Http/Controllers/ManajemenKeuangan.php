<?php

namespace App\Http\Controllers;

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
        $misi = \DB::SELECT("SELECT * FROM misi ORDER BY periode DESC, created_at ASC");
        return view('pages._manajemenKeuangan.perencanaan.viewGetAll', compact('info','misi'));
    }

    public function perencanaanApiGetAll()
    {
        try {
            $id_misi = " AND  prog.id_misi = '".$this->request->misi."'";
            $apiGetAll = DB::select("
                SELECT
                    kgt.id_kegiatan,
                    kgt.nm_kegiatan,
                    CASE
                        WHEN kgt.a_aktif = '1' THEN
                        'Aktif' ELSE'Non Aktif'
                    END AS a_aktif
                FROM
                    kegiatan AS kgt
                JOIN
                    program AS prog ON prog.id_program=kgt.id_program
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
                'Program',
                'Rutin'
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
                akn.elemen IN ('1','5')
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
                        kgt.id_kegiatan,
                        kgt.nm_kegiatan,
                        dvs.nm_divisi,
                        CASE
                            WHEN kgt.a_aktif = '1' THEN
                            'Aktif' ELSE'Non Aktif'
                        END AS a_aktif
                    FROM
                        kegiatan AS kgt
                    JOIN
                        program AS prog ON prog.id_program=kgt.id_program
                    JOIN
                        kegiatan_divisi AS kgt_dvs ON kgt_dvs.id_kegiatan=kgt.id_kegiatan
                    JOIN
                        divisi AS dvs ON dvs.id_divisi=kgt_dvs.id_divisi
                    WHERE
                        kgt.a_aktif = '1' AND prog.periode = '".$this->request->tahun."'
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
