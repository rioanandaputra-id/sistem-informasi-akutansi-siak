<?php

namespace App\Http\Controllers\BendaharaPengeluaran;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Program;
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
                    kgt.id_kegiatan,
                    kgt.nm_kegiatan,
                    CASE
                    WHEN kgt.a_aktif = '1' THEN 'Aktif'
                    ELSE 'Non Aktif'
                    END AS a_aktif,
                    pgm.id_program,
                    pgm.id_misi,
                    pgm.nm_program,
                    pgm.periode
                FROM
                    kegiatan AS kgt
                    JOIN program AS pgm ON pgm.id_program = kgt.id_program
                    AND pgm.id_misi IS NULL
                    AND pgm.deleted_at IS NULL
                    AND pgm.a_aktif = '1'
                WHERE
                    kgt.deleted_at IS NULL
                ORDER BY
                    pgm.periode,
                    kgt.nm_kegiatan ASC
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
                'periode' => 'required',
                'nm_kegiatan' => 'required',
                'a_aktif' => 'required',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }
            $id_kegiatan = guid();
            $id_program = guid();
            $periode = $this->request->periode;
            $nm_kegiatan = $this->request->nm_kegiatan;
            $a_aktif = $this->request->a_aktif;
            $created_at = now();
            $id_updater = Auth::user()->id_user;

            $existProgram = Program::where('periode', $periode)
                ->whereNull('id_misi')
                ->whereNull('deleted_at')
                ->where('a_aktif', '1');

            if ($existProgram->exists()) {
                $id_program = $existProgram->first()->id_program;
            } else {
                Program::create([
                    'id_program' => $id_program,
                    'id_misi' => null,
                    'nm_program' => "Non Program",
                    'periode' => $periode,
                    'a_aktif' => $a_aktif,
                    'created_at' => $created_at,
                    'id_updater' => $id_updater,
                ]);
            }

            Kegiatan::create([
                'id_program' => $id_program,
                'id_kegiatan' => $id_kegiatan,
                'nm_kegiatan' => $nm_kegiatan,
                'a_aktif' => $a_aktif,
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

    public function apiUpdate()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_kegiatan' => 'required',
                'periode' => 'required',
                'nm_kegiatan' => 'required',
                'a_aktif' => 'required',
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }
            $id_kegiatan = $this->request->id_kegiatan;
            $id_program = guid();
            $periode = $this->request->periode;
            $nm_kegiatan = $this->request->nm_kegiatan;
            $a_aktif = $this->request->a_aktif;
            $created_at = now();
            $id_updater = Auth::user()->id_user;

            $existProgram = Program::where('periode', $periode)
                ->whereNull('id_misi')
                ->whereNull('deleted_at')
                ->where('a_aktif', '1');

            if ($existProgram->exists()) {
                $id_program = $existProgram->first()->id_program;
            } else {
                Program::create([
                    'id_program' => $id_program,
                    'id_misi' => null,
                    'nm_program' => "Non Program",
                    'periode' => $periode,
                    'a_aktif' => $a_aktif,
                    'created_at' => $created_at,
                    'id_updater' => $id_updater,
                ]);
            }

            Kegiatan::where('id_kegiatan', $id_kegiatan)->update([
                'id_program' => $id_program,
                'nm_kegiatan' => $nm_kegiatan,
                'a_aktif' => $a_aktif,
                'updated_at' => $created_at,
                'id_updater' => $id_updater,
            ]);
            DB::commit();
            return back()->with('success', 'Data Berhasil Diubah!');
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

    public function apiDelete()
    {
        try {
            DB::beginTransaction();
            $rules = ['id_kegiatan.*' => 'required|uuid'];
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
            $id_kegiatan = $this->request->id_kegiatan;
            $deleted_at = now();
            $id_updater = Auth::user()->id_user;
            Kegiatan::whereIn('id_kegiatan', $id_kegiatan)->update([
                'deleted_at' => $deleted_at,
                'id_updater' => $id_updater,
            ]);
            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Deleted',
                'error' => null,
                'response' => ['id_kegiatan' => $id_kegiatan]
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
            'title' => 'Kegiatan Rutin',
            'site_active' => 'KegiatanRutin',
        ];
        return view('pages._bendaharaPengeluaran.kegiatanRutin.viewGetAll', compact('info'));
    }

    public function viewCreate()
    {
        $info = [
            'title' => 'Kegiatan Rutin',
            'site_active' => 'KegiatanRutin',
        ];
        return view('pages._bendaharaPengeluaran.kegiatanRutin.viewCreate', compact('info'));
    }

    public function viewUpdate()
    {
        $id_kegiatan = $this->request->id_kegiatan;
        $info = [
            'title' => 'Kegiatan Rutin',
            'site_active' => 'KegiatanRutin',
        ];
        $kegiatanRutin = DB::select("
            SELECT
                kgt.id_kegiatan,
                kgt.nm_kegiatan,
                kgt.a_aktif,
                pgm.id_program,
                pgm.id_misi,
                pgm.nm_program,
                pgm.periode
            FROM
                kegiatan AS kgt
                JOIN program AS pgm ON pgm.id_program = kgt.id_program
                AND pgm.id_misi IS NULL
                AND pgm.deleted_at IS NULL
                AND pgm.a_aktif = '1'
            WHERE
                kgt.deleted_at IS NULL
                AND kgt.id_kegiatan = '" . $id_kegiatan . "'
        ");
        return view('pages._bendaharaPengeluaran.kegiatanRutin.viewUpdate', compact('info', 'kegiatanRutin'));
    }
}
