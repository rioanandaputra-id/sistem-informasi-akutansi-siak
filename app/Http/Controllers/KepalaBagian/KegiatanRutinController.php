<?php

namespace App\Http\Controllers\KepalaBagian;

use App\Http\Controllers\Controller;
use App\Models\KegiatanRutin;
use App\Models\DetailKegiatanRutin;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;
use Illuminate\Support\Facades\Validator;

class KegiatanRutinController extends Controller
{
    private $request;

    public function __construct()
    {
        $this->request = app(Request::class);
    }

    public function apiGetAll()
    {
        try {
            $apiGetAll = DB::select("
                SELECT
                    kgt.id_kegiatan_rutin,
                    kgt.id_divisi,
                    kgt.nm_kegiatan,
                    kgt.tgl_buat,
                    kgt.tgl_submit,
                    kgt.a_verif_rba,
                    kgt.a_verif_kabag_keuangan,
                    dvs.nm_divisi
                FROM
                    kegiatan_rutin AS kgt
                    JOIN divisi AS dvs ON dvs.id_divisi=kgt.id_divisi
                WHERE
                    kgt.deleted_at IS NULL
                    AND kgt.a_aktif = '1'
                    AND kgt.id_divisi = '".Auth::user()->id_divisi."'
                ORDER BY
                    kgt.periode,
                    kgt.created_at DESC
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

    public function apiCreate()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'nm_kegiatan' => 'required',
                'a_aktif' => 'required',
                'periode' => 'required',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }
            $id_kegiatan_rutin = guid();
            $tgl_buat = now();
            $nm_kegiatan = $this->request->nm_kegiatan;
            $a_aktif = $this->request->a_aktif;
            $created_at = now();
            $id_divisi = Auth::user()->id_divisi;
            $id_updater = Auth::user()->id_user;
            $periode = $this->request->periode;
            $a_verif_rba = '1';
            $a_verif_kabag_keuangan = '1';

            KegiatanRutin::create([
                'id_kegiatan_rutin' => $id_kegiatan_rutin,
                'id_divisi' => $id_divisi,
                'tgl_buat' => $tgl_buat,
                'periode' => $periode,
                'nm_kegiatan' => $nm_kegiatan,
                'a_aktif' => $a_aktif,
                'a_verif_rba' => $a_verif_rba,
                'a_verif_kabag_keuangan' => $a_verif_kabag_keuangan,
                'created_at' => $created_at,
                'id_updater' => $id_updater,
            ]);
            DB::commit();
            return back()->with('success', 'Data Berhasil Ditambahkan!');
        } catch (QueryException $e) {
            DB::rollBack();
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            return back()->with('error', 'Internal Server Error | QueryException');
        } catch (Exception $e) {
            DB::rollBack();
            logger($this->request->ip(), [$this->request->fullUrl(), __CLASS__, __FUNCTION__, $e->getLine(), $e->getMessage()]);
            return back()->with('error', 'Internal Server Error | Exception');
        }
    }

    public function viewDetail()
    {
        //
    }
}
