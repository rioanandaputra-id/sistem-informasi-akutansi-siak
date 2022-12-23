<?php

namespace App\Http\Controllers\KepalaUud\Keuangan;

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
        $divisi = \App\Models\Divisi::whereNull('deleted_at')->orderBy('nm_divisi')->get();
        return view('pages._kepalaUud._keuangan.manajemenKeuangan.perencanaan.viewGetAll', compact('info','divisi'));
    }

    public function perencanaanApiGetAll()
    {
        try {
            $tahun = ($this->request->tahun == "-") ? " " : " AND date_part('year', rba.tgl_submit)='".$this->request->tahun."'";
            $divisi = ($this->request->divisi == "-") ? " " : " AND kdiv.id_divisi='".$this->request->divisi."'";
            $apiGetAll = DB::SELECT("
                SELECT
                    akun.id_akun,
                    akun.nm_akun,
                    CONCAT(akun.elemen, akun.sub_elemen, akun.jenis, akun.no_akun) AS no_akun,
                    CASE
                        WHEN rencana.rencana_anggaran IS NULL THEN 0
                        ELSE rencana.rencana_anggaran
                        END AS rencana_anggaran
                FROM
                    akun
                    JOIN (
                        SELECT
                            drba.id_akun,
                            SUM(drba.total) AS rencana_anggaran
                        FROM
                            kegiatan_divisi AS kdiv
                            JOIN rba ON rba.id_kegiatan_divisi=kdiv.id_kegiatan_divisi
                            JOIN detail_rba AS drba ON drba.id_rba=rba.id_rba
                        WHERE
                            rba.a_verif_wilayah='2'
                            ".$tahun."
                            ".$divisi."
                        GROUP BY
                            drba.id_akun
                    ) AS rencana ON rencana.id_akun=akun.id_akun
                ORDER BY
                    akun.no_akun, akun.nm_akun ASC
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

    public function penganggaranPendapatanViewGetAll()
    {
        $info = [
            'title' => 'Penganggaran Pendapatan',
            'site_active' => 'PenganggaranPendapatan',
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
                akn.elemen='5'
                AND akn.no_akun > '0000'
                AND akn.deleted_at IS NULL
        ");
        return view('pages._kepalaUud._keuangan.manajemenKeuangan.penganggaran.pendapatan.viewGetAll', compact('info'));
    }

    public function penganggaranPendapatanApiGetAll()
    {
        try {
            $tahun = ($this->request->tahun == "-") ? " " : " AND date_part('year', spj.created_at)='".$this->request->tahun."'";
            $divisi = ($this->request->divisi == "-") ? " " : " AND kdiv.id_divisi='".$this->request->divisi."'";
            $apiGetAll = DB::SELECT("
                SELECT
                    akun.id_akun,
                    akun.nm_akun,
                    CONCAT(akun.elemen, akun.sub_elemen, akun.jenis, akun.no_akun) AS no_akun,
                    CASE
                        WHEN realisasi.realisasi_anggaran IS NULL THEN 0
                        ELSE realisasi.realisasi_anggaran
                        END AS realisasi_anggaran
                FROM
                    akun
                    JOIN (
                        SELECT
                            dspj.id_akun,
                            SUM(dspj.total) AS realisasi_anggaran
                        FROM
                            kegiatan_divisi AS kdiv
                            JOIN laksana_kegiatan AS lkeg ON lkeg.id_kegiatan_divisi=kdiv.id_kegiatan_divisi
                            JOIN spj ON spj.id_laksana_kegiatan=lkeg.id_laksana_kegiatan
                            JOIN detail_spj AS dspj ON dspj.id_spj=spj.id_spj
                        WHERE
                            spj.a_verif_kabag_keuangan='2'
                        GROUP BY
                            dspj.id_akun
                    ) AS realisasi ON realisasi.id_akun=akun.id_akun
                WHERE
                    akun.elemen='4'
                ORDER BY
                    akun.no_akun, akun.nm_akun ASC
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

    public function penganggaranPengeluaranViewGetAll()
    {
        $info = [
            'title' => 'Penganggaran Pengeluaran',
            'site_active' => 'PenganggaranPengeluaran',
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
                akn.elemen='5'
                AND akn.no_akun > '0000'
                AND akn.deleted_at IS NULL
        ");
        return view('pages._kepalaUud._keuangan.manajemenKeuangan.penganggaran.pengeluaran.viewGetAll', compact('info'));
    }

    public function penganggaranPengeluaranApiGetAll()
    {
        try {
            $tahun = ($this->request->tahun == "-") ? " " : " AND date_part('year', spj.created_at)='".$this->request->tahun."'";
            $divisi = ($this->request->divisi == "-") ? " " : " AND kdiv.id_divisi='".$this->request->divisi."'";
            $apiGetAll = DB::SELECT("
                SELECT
                    akun.id_akun,
                    akun.nm_akun,
                    CONCAT(akun.elemen, akun.sub_elemen, akun.jenis, akun.no_akun) AS no_akun,
                    CASE
                        WHEN realisasi.realisasi_anggaran IS NULL THEN 0
                        ELSE realisasi.realisasi_anggaran
                        END AS realisasi_anggaran
                FROM
                    akun
                    JOIN (
                        SELECT
                            dspj.id_akun,
                            SUM(dspj.total) AS realisasi_anggaran
                        FROM
                            kegiatan_divisi AS kdiv
                            JOIN laksana_kegiatan AS lkeg ON lkeg.id_kegiatan_divisi=kdiv.id_kegiatan_divisi
                            JOIN spj ON spj.id_laksana_kegiatan=lkeg.id_laksana_kegiatan
                            JOIN detail_spj AS dspj ON dspj.id_spj=spj.id_spj
                        WHERE
                            spj.a_verif_kabag_keuangan='2'
                            ".$tahun."
                            ".$divisi."
                        GROUP BY
                            dspj.id_akun
                    ) AS realisasi ON realisasi.id_akun=akun.id_akun
                WHERE
                    akun.elemen='5'
                ORDER BY
                    akun.no_akun, akun.nm_akun ASC
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

    public function penatausahaanViewGetAll()
    {
        $info = [
            'title' => 'Penatausahaan',
            'site_active' => 'Penatausahaan'
        ];
        return view('pages._kepalaUud._keuangan.manajemenKeuangan.penatausahaan.viewGetAll', compact('info'));
    }

    public function penatausahaanApiGetAll()
    {
        try {

            //

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

    public function pelaporanViewGetAll()
    {
        $info = [
            'title' => 'Pelaporan',
            'site_active' => 'Pelaporan'
        ];
        return view('pages._kepalaUud._keuangan.manajemenKeuangan.pelaporan.viewGetAll', compact('info'));
    }

    public function pelaporanApiGetAll()
    {
        try {
            
            //

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
}
