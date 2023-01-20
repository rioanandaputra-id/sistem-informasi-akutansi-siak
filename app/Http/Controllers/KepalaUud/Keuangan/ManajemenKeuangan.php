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
        $divisi = \App\Models\Divisi::whereNull('deleted_at')->where('nm_divisi','!=','-')->orderBy('nm_divisi')->get();
        return view('pages._kepalaUud._keuangan.manajemenKeuangan.perencanaan.viewGetAll', compact('info','divisi'));
    }

    public function perencanaanApiGetAll()
    {
        try {
            $tahun = ($this->request->tahun == "-") ? " " : " AND pr.periode='".$this->request->tahun."'";
            $divisi = ($this->request->divisi == "-") ? " " : " AND kdiv.id_divisi='".$this->request->divisi."'";
            $apiGetAll = DB::SELECT("
                SELECT
                    kdiv.id_kegiatan_divisi,
                    pr.nm_program,
                    kgt.nm_kegiatan,
                    pr.periode,
                    dvs.nm_divisi
                FROM
                    kegiatan_divisi AS kdiv
                    JOIN divisi AS dvs ON dvs.id_divisi=kdiv.id_divisi AND dvs.deleted_at IS NULL
                    JOIN kegiatan AS kgt ON kgt.id_kegiatan=kdiv.id_kegiatan AND kgt.deleted_at IS NULL
                    JOIN program AS pr ON pr.id_program=kgt.id_program AND pr.deleted_at IS NULL
                WHERE
                    kdiv.deleted_at IS NULL
                    ".$divisi."
                    ".$tahun."
                ORDER BY
                    pr.nm_program,
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
                akn.elemen='4'
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
            $tahun_pagu = ($this->request->tahun == "-") ? " " : " AND date_part('year', laks.created_at)='".$this->request->tahun."'";
            $apiGetAll = DB::SELECT("
                SELECT
                    akun.id_akun,
                    akun.nm_akun,
                    CONCAT(
                        akun.elemen,
                        akun.sub_elemen,
                        akun.jenis,
                        akun.no_akun
                    ) AS no_akun,
                    CASE
                        WHEN realisasi.realisasi_anggaran IS NULL THEN 0
                        ELSE realisasi.realisasi_anggaran
                    END AS realisasi_anggaran,
                    (
                        SELECT
                            SUM(dlaks.total)
                        FROM
                            kegiatan_divisi AS kdiv
                            JOIN laksana_kegiatan AS laks ON laks.id_kegiatan_divisi = kdiv.id_kegiatan_divisi
                            AND laks.deleted_at IS NULL
                            AND laks.a_verif_kabag_keuangan = '2'
                            JOIN detail_laksana_kegiatan AS dlaks ON dlaks.id_laksana_kegiatan = laks.id_laksana_kegiatan
                            AND dlaks.deleted_at IS NULL
                            JOIN detail_rba AS drba ON drba.id_detail_rba = dlaks.id_detail_rba
                            AND drba.deleted_at IS NULL
                        WHERE
                            kdiv.deleted_at IS NULL
                            AND akun.id_akun=drba.id_akun
                            ".$tahun_pagu."
                            ".$divisi."
                    ) AS pagu_anggaran
                FROM
                    akun
                    JOIN (
                        SELECT
                            dspj.id_akun,
                            SUM(dspj.total) AS realisasi_anggaran
                        FROM
                            kegiatan_divisi AS kdiv
                            JOIN laksana_kegiatan AS laks ON laks.id_kegiatan_divisi = kdiv.id_kegiatan_divisi
                            AND laks.deleted_at IS NULL
                            AND laks.a_verif_kabag_keuangan = '2'
                            JOIN spj ON spj.id_laksana_kegiatan = laks.id_laksana_kegiatan
                            JOIN detail_spj AS dspj ON dspj.id_spj = spj.id_spj
                        WHERE
                            spj.a_verif_kabag_keuangan = '2'
                            ".$tahun."
                            ".$divisi."
                        GROUP BY
                            dspj.id_akun
                    ) AS realisasi ON realisasi.id_akun = akun.id_akun
                WHERE
                    akun.elemen = '4'
                ORDER BY
                    akun.no_akun,
                    akun.nm_akun ASC
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
                akn.elemen IN ('1','5')
                AND akn.no_akun > '0000'
                AND akn.deleted_at IS NULL
        ");
        
        return view('pages._kepalaUud._keuangan.manajemenKeuangan.penganggaran.pengeluaran.viewGetAll', compact('info'));
    }

    public function penganggaranPengeluaranApiGetAll()
    {
        try {
            $tahun = ($this->request->tahun == "-") ? " " : " AND pr.periode='".$this->request->tahun."'";
            $divisi = ($this->request->divisi == "-") ? " " : " AND kdiv.id_divisi='".$this->request->divisi."'";

            if($this->request->subAkun == null) {
                $subElemen = \App\Models\Akun::whereNull('deleted_at')->where('sub_elemen', '>', '0')->where('jenis', '00')->where('no_akun', '0000')->pluck('id_akun');
            } else {
                $subElemen = \App\Models\Akun::whereNull('deleted_at')->where('no_akun_induk', $this->request->subAkun)->pluck('id_akun');
            }
            //REFACTOR
            $subAkun = array();
            foreach($subElemen AS $item) {
                array_push($subAkun, "'".$item."'");
            }
            $subAkun = implode(',', $subAkun);

            $apiGetAll = DB::SELECT("
                SELECT
                    CONCAT(
                        akn.elemen,
                        akn.sub_elemen,
                        akn.jenis,
                        akn.no_akun
                    ) AS no_akun,
                    akn.nm_akun,
                    akn.id_akun
                FROM
                    akun AS akn
                WHERE
                    akn.deleted_at IS NULL
                    AND akn.id_akun IN (" . $subAkun .")
                ORDER BY
                    no_akun ASC
            ");
            foreach($apiGetAll AS $r) {
                $jenis = \App\Models\Akun::whereNull('deleted_at')->where('no_akun_induk', $r->id_akun)->pluck('id_akun');
                if($this->request->subAkun == null) {
                    $akun = \App\Models\Akun::whereNull('deleted_at')->whereIn('no_akun_induk', $jenis)->pluck('id_akun');
                    if(count($akun) < 1) {
                        $akun = array($r->id_akun);
                    }
                } else if (count($jenis) < 1) {
                    $akun = array($r->id_akun);
                } else if (count($jenis) > 0) {
                    $akun = $jenis;
                }
                //REFACTOR
                $idAkun = array();
                foreach($akun AS $value) {
                   array_push($idAkun, "'".$value."'");
                }
                $idAkun = implode(',', $idAkun);
                $idAkun = " AND drba.id_akun IN (".$idAkun.") ";

                $pagu_anggaran = DB::SELECT("
                    SELECT
                        SUM(drba.total)
                    FROM
                        kegiatan_divisi AS kdiv
                        JOIN kegiatan AS kgt ON kgt.id_kegiatan=kdiv.id_kegiatan AND kgt.deleted_at IS NULL
                        JOIN program AS pr ON pr.id_program=kgt.id_program AND pr.deleted_at IS NULL ".$tahun."
                        JOIN rba ON rba.id_kegiatan_divisi = kdiv.id_kegiatan_divisi
                        AND rba.deleted_at IS NULL
                        JOIN detail_rba AS drba ON drba.id_rba = rba.id_rba
                        AND drba.deleted_at IS NULL
                    WHERE
                        kdiv.deleted_at IS NULL
                        ".$divisi."
                        ".$idAkun."
                ");
                $r->pagu_anggaran = (is_null($pagu_anggaran)) ? '0' : $pagu_anggaran[0]->sum;
                $realisasi_anggaran = DB::SELECT("
                    SELECT
                        SUM(drba.total)
                    FROM
                        kegiatan_divisi AS kdiv
                        JOIN kegiatan AS kgt ON kgt.id_kegiatan=kdiv.id_kegiatan AND kgt.deleted_at IS NULL
                        JOIN program AS pr ON pr.id_program=kgt.id_program AND pr.deleted_at IS NULL ".$tahun."
                        JOIN laksana_kegiatan AS laks ON laks.id_kegiatan_divisi = kdiv.id_kegiatan_divisi
                        AND laks.deleted_at IS NULL
                        JOIN detail_laksana_kegiatan AS dlaks ON dlaks.id_laksana_kegiatan = laks.id_laksana_kegiatan
                        AND dlaks.deleted_at IS NULL
                        JOIN detail_rba AS drba ON drba.id_detail_rba = dlaks.id_detail_rba
                        AND drba.deleted_at IS NULL
                    WHERE
                        kdiv.deleted_at IS NULL
                        ".$divisi."
                        ".$idAkun."
                ");
                $r->realisasi_anggaran = (is_null($realisasi_anggaran)) ? '0' : $realisasi_anggaran[0]->sum;
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

    public function pelaporanRba21ViewGetAll()
    {
        $info = [
            'title' => 'Pelaporan RBA & SPJ 2.1',
            'site_active' => 'PelaporanRba21'
        ];
        return view('pages._kepalaUud._keuangan.manajemenKeuangan.pelaporan.rba21.viewGetAll', compact('info'));
    }

    public function pelaporanRba21ApiGetAll()
    {
        try {
            $apiGetAll = DB::SELECT("
                SELECT
                    dvs.id_divisi,
                    dvs.nm_divisi
                FROM
                    divisi AS dvs
                WHERE
                    dvs.deleted_at IS NULL
                    AND dvs.nm_divisi != '-'
                ORDER BY
                    dvs.nm_divisi ASC
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

    public function pelaporanRba211ViewGetAll()
    {
        $info = [
            'title' => 'Pelaporan RBA 2.1.1',
            'site_active' => 'PelaporanRba211'
        ];
        return view('pages._kepalaUud._keuangan.manajemenKeuangan.pelaporan.rba211.viewGetAll', compact('info'));
    }

    public function pelaporanRba211ApiGetAll()
    {
        try {
            $divisi = ($this->request->id_divisi != 'All') ? " AND dvs.id_divisi='".$this->request->id_divisi."'" : "";
            $apiGetAll = DB::SELECT("
                SELECT
                    kgt.id_kegiatan,
                    kgt.nm_kegiatan,
                    pr.nm_program,
                    msi.nm_misi,
                    dvs.nm_divisi
                FROM
                    divisi AS dvs
                    JOIN kegiatan_divisi AS kdiv ON kdiv.id_divisi=dvs.id_divisi AND kdiv.deleted_at IS NULL
                    JOIN rba ON rba.id_kegiatan_divisi=kdiv.id_kegiatan_divisi AND rba.deleted_at IS NULL AND rba.tgl_submit IS NOT NULL
                    JOIN kegiatan AS kgt ON kgt.id_kegiatan=kdiv.id_kegiatan AND kgt.deleted_at IS NULL
                    JOIN program AS pr ON pr.id_program=kgt.id_program AND pr.deleted_at IS NULL
                    LEFT JOIN misi AS msi ON msi.id_misi=pr.id_misi AND msi.deleted_at IS NULL
                WHERE
                    dvs.deleted_at IS NULL
                    ".$divisi."
                ORDER BY
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

    public function pelaporanSpj211ViewGetAll()
    {
        $info = [
            'title' => 'Pelaporan SPJ 2.1.1',
            'site_active' => 'PelaporanSpj211'
        ];
        return view('pages._kepalaUud._keuangan.manajemenKeuangan.pelaporan.spj211.viewGetAll', compact('info'));
    }

    public function pelaporanSpj211ApiGetAll()
    {
        try {
            $divisi = ($this->request->id_divisi != 'All') ? " AND dvs.id_divisi='".$this->request->id_divisi."'" : "";
            $apiGetAll = DB::SELECT("
                SELECT
                    kgt.id_kegiatan,
                    kgt.nm_kegiatan,
                    pr.nm_program,
                    msi.nm_misi,
                    dvs.nm_divisi
                FROM
                    divisi AS dvs
                    JOIN kegiatan_divisi AS kdiv ON kdiv.id_divisi=dvs.id_divisi AND kdiv.deleted_at IS NULL
                    JOIN rba ON rba.id_kegiatan_divisi=kdiv.id_kegiatan_divisi AND rba.deleted_at IS NULL AND rba.tgl_submit IS NOT NULL
                    JOIN kegiatan AS kgt ON kgt.id_kegiatan=kdiv.id_kegiatan AND kgt.deleted_at IS NULL
                    JOIN program AS pr ON pr.id_program=kgt.id_program AND pr.deleted_at IS NULL
                    LEFT JOIN misi AS msi ON msi.id_misi=pr.id_misi AND msi.deleted_at IS NULL
                WHERE
                    dvs.deleted_at IS NULL
                    ".$divisi."
                ORDER BY
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
}
