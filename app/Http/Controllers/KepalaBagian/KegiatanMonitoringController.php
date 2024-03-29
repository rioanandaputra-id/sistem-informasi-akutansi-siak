<?php

namespace App\Http\Controllers\KepalaBagian;

use App\Http\Controllers\Controller;
use App\Models\DetailLaksanaKegiatan;
use App\Models\DetailRba;
use App\Models\LaksanaKegiatan;
use App\Models\Rba;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables as DaTables;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Imports\DetailRbaImport;
use App\Exports\DetailRbaTemplate;
use Maatwebsite\Excel\Facades\Excel;

class KegiatanMonitoringController extends Controller
{
    private $request;
    private $mRba;
    private $mDetailRba;
    private $mLaksanaKegiatan;
    private $mDetailLaksanaKegiatan;

    public function __construct()
    {
        $this->request = app(Request::class);
        $this->mRba = app(Rba::class);
        $this->mDetailRba = app(DetailRba::class);
        $this->mLaksanaKegiatan = app(LaksanaKegiatan::class);
        $this->mDetailLaksanaKegiatan = app(DetailLaksanaKegiatan::class);
    }

    public function apiGetAll()
    {
        $id_program = ($this->request->id_program) ? " AND  pgm.id_program = '" . $this->request->id_program . "'" : "";
        $apiGetAll = DB::select("
            SELECT
                kdiv.id_kegiatan_divisi,
                kdiv.id_divisi,
                div.nm_divisi,
                kdiv.id_kegiatan,
                msi.id_misi,
                CONCAT('[ ', msi.periode, ' ] ', msi.nm_misi) AS nm_misi,
                CONCAT('[ ', pgm.periode, ' ] ', pgm.nm_program) AS nm_program,
                kgt.nm_kegiatan,
                rba.id_rba,
                rba.tgl_submit,
                CASE
                    kdiv.a_verif_rba
                    WHEN '2' THEN 'Disetujui TIM RBA'
                    WHEN '3' THEN 'Ditolak TIM RBA'
                    ELSE 'Belum Diverifikasi TIM RBA'
                END AS kdiv_a_verif_rba,
                CASE
                    rba.a_verif_rba
                    WHEN '2' THEN 'Disetujui Kepala UDD'
                    WHEN '3' THEN 'Ditolak Kepala UDD'
                    ELSE 'Belum Diverifikasi Kepala UDD'
                END AS rba_a_verif_rba,
                CASE
                    rba.a_verif_wilayah
                    WHEN '2' THEN 'Disetujui Kepala Pengurus Wilayah'
                    WHEN '3' THEN 'Ditolak Kepala Pengurus Wilayah'
                    ELSE 'Belum Diverifikasi Kepala Pengurus Wilayah'
                END AS rba_a_verif_wilayah,
                kdiv.id_verif_rba AS kdiv_id_verif_rba,
                kdiv.tgl_verif_rba AS kdiv_tgl_verif_rba,
                kdiv.catatan AS kdiv_catatan,
                rba.id_verif_rba AS rba_id_verif_rba,
                rba.tgl_verif_rba AS rba_tgl_verif_rba,
                rba.catatan_verif_rba AS rba_catatan_verif_rba,
                rba.id_verif_wilayah AS rba_id_verif_wilayah,
                rba.tgl_verif_wilayah AS rba_tgl_verif_wilayah,
                rba.catatan_verif_wilayah AS rba_catatan_verif_wilayah
            FROM
                kegiatan_divisi AS kdiv
                JOIN divisi AS div ON div.id_divisi = kdiv.id_divisi
                AND div.deleted_at IS NULL
                JOIN kegiatan AS kgt ON kgt.id_kegiatan = kdiv.id_kegiatan
                AND kgt.deleted_at IS NULL
                JOIN program AS pgm ON pgm.id_program = kgt.id_program
                AND pgm.deleted_at IS NULL
                LEFT JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                AND msi.deleted_at IS NULL
                LEFT JOIN rba AS rba ON rba.id_kegiatan_divisi = kdiv.id_kegiatan_divisi
                AND rba.deleted_at IS NULL
            WHERE
                kdiv.deleted_at IS NULL
                AND kdiv.id_divisi = '" . Auth::user()->id_divisi . "'
                " . $id_program . "
        ");
        return DaTables::of($apiGetAll)->make(true);
    }

    public function apiGetById()
    {
        $id_kegiatan_divisi = $this->request->id_kegiatan_divisi;
        $apiGetById = DB::select("
            SELECT
                kdiv.id_kegiatan_divisi,
                kdiv.id_divisi,
                div.nm_divisi,
                kdiv.id_kegiatan,
                msi.id_misi,
                msi.nm_misi,
                pgm.nm_program,
                kgt.nm_kegiatan,
                rba.id_rba,
                rba.tgl_submit,
                kdiv.a_verif_rba,
                CASE
                    kdiv.a_verif_rba
                    WHEN '2' THEN 'Disetujui TIM RBA'
                    WHEN '3' THEN 'Ditolak TIM RBA'
                    ELSE 'Belum Diverifikasi TIM RBA'
                END AS kdiv_a_verif_rba,
                rba.a_verif_rba AS a_verif_kepalaudd,
                CASE
                    rba.a_verif_rba
                    WHEN '2' THEN 'Disetujui Kepala UDD'
                    WHEN '3' THEN 'Ditolak Kepala UDD'
                    ELSE 'Belum Diverifikasi Kepala UDD'
                END AS rba_a_verif_rba,
                rba.a_verif_wilayah,
                CASE
                    rba.a_verif_wilayah
                    WHEN '2' THEN 'Disetujui Kepala Pengurus Wilayah'
                    WHEN '3' THEN 'Ditolak Kepala Pengurus Wilayah'
                    ELSE 'Belum Diverifikasi Kepala Pengurus Wilayah'
                END AS rba_a_verif_wilayah,
                kdiv.id_verif_rba AS kdiv_id_verif_rba,
                kdiv.tgl_verif_rba AS kdiv_tgl_verif_rba,
                kdiv.catatan AS kdiv_catatan,
                rba.id_verif_rba AS rba_id_verif_rba,
                rba.tgl_verif_rba AS rba_tgl_verif_rba,
                rba.catatan_verif_rba AS rba_catatan_verif_rba,
                rba.id_verif_wilayah AS rba_id_verif_wilayah,
                rba.tgl_verif_wilayah AS rba_tgl_verif_wilayah,
                rba.catatan_verif_wilayah AS rba_catatan_verif_wilayah
            FROM
                kegiatan_divisi AS kdiv
                JOIN divisi AS div ON div.id_divisi = kdiv.id_divisi
                AND div.deleted_at IS NULL
                JOIN kegiatan AS kgt ON kgt.id_kegiatan = kdiv.id_kegiatan
                AND kgt.deleted_at IS NULL
                JOIN program AS pgm ON pgm.id_program = kgt.id_program
                AND pgm.deleted_at IS NULL
                LEFT JOIN misi AS msi ON msi.id_misi = pgm.id_misi
                AND msi.deleted_at IS NULL
                LEFT JOIN rba AS rba ON rba.id_kegiatan_divisi = kdiv.id_kegiatan_divisi
                AND rba.deleted_at IS NULL
            WHERE
                kdiv.deleted_at IS NULL
                AND kdiv.id_kegiatan_divisi = '" . $id_kegiatan_divisi . "'
        ");
        return $apiGetById;
    }

    public function apiUpdate()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_rba' => 'required',
                'id_kegiatan_divisi' => 'required',
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
            $id_kegiatan_divisi = $this->request->id_kegiatan_divisi;
            $id_rba = $this->request->id_rba;
            $tgl_submit = now();
            $updated_at = now();
            $id_updater = Auth::user()->id_user;
            $check = $this->mDetailRba->where('id_rba', $id_rba)->count();
            if ($check == 0) {
                return [
                    'status' => false,
                    'latency' => AppLatency(),
                    'message' => 'Rincian RENCANA BISNIS & ANGGARAN Kegiatan Belum Anda Tambahkan!',
                    'error' => null,
                    'response' => ['id_rba' => $id_rba]
                ];
            } else {
                $this->mRba->where('id_rba', $id_rba)->update([
                    'tgl_submit' => $tgl_submit,
                    'a_verif_rba' => '1',
                    'tgl_verif_rba' => null,
                    'a_verif_wilayah' => '1',
                    'tgl_verif_wilayah' => null,
                    'updated_at' => $updated_at,
                    'id_updater' => $id_updater,
                ]);
                \App\Models\KegiatanDivisi::where('id_kegiatan_divisi', $id_kegiatan_divisi)->update([
                    'a_verif_rba' => 1,
                    'tgl_verif_rba' => null
                ]);
                DB::commit();
                return [
                    'status' => true,
                    'latency' => AppLatency(),
                    'message' => 'Updated',
                    'error' => null,
                    'response' => ['id_rba' => $id_rba]
                ];
            }
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
            'title' => 'Kegiatan Telah Diajukan',
            'site_active' => 'KegiatanDiajukan',
        ];
        $program = DB::select("
            SELECT
                pgm.id_program,
                pgm.nm_program,
                pgm.periode
            FROM
                program AS pgm
            WHERE
                pgm.deleted_at IS NULL
                AND pgm.a_aktif = '1'
                ORDER BY pgm.periode DESC, pgm.nm_program ASC
        ");
        return view('pages._kepalaBagian.kegiatanMonitoring.viewGetAll', compact('info', 'program'));
    }

    public function viewDetail()
    {
        $id_kegiatan_divisi = $this->request->id_kegiatan_divisi;
        $info = [
            'title' => 'Detail Kegiatan Telah Diajukan',
            'site_active' => 'KegiatanDiajukan',
        ];
        $kegiatan = $this->apiGetById();
        $detailRba = DB::select("
            SELECT
                drba.id_detail_rba,
                drba.id_rba,
                drba.id_akun,
                drba.vol,
                drba.satuan,
                drba.indikator,
                drba.tarif,
                drba.total,
                drba.created_at,
                drba.updated_at,
                drba.deleted_at,
                drba.id_updater,
                CONCAT(akn.elemen, akn.sub_elemen, akn.jenis, akn.no_akun) AS no_akun,
                akn.nm_akun,
                akn.keterangan
            FROM
                detail_rba AS drba
                JOIN rba as rba ON rba.id_rba = drba.id_rba
                AND rba.deleted_at IS NULL AND rba.id_kegiatan_divisi = '" . $id_kegiatan_divisi . "'
                JOIN akun AS akn ON akn.id_akun = drba.id_akun
                AND akn.deleted_at IS NULL
            WHERE
                drba.deleted_at IS NULL
            ORDER BY drba.created_at ASC
        ");
        $laksKegiatan = DB::select("
            SELECT
                lkgt.id_laksana_kegiatan,
                lkgt.id_kegiatan_divisi,
                lkgt.tgl_ajuan,
                lkgt.urutan_laksana_kegiatan,
                CASE
                    WHEN lkgt.a_verif_kabag_keuangan = '1'
                    AND pgm.id_misi IS NULL THEN 'Belum Diverifikasi Bend. Pengeluaran'
                    WHEN lkgt.a_verif_kabag_keuangan = '2'
                    AND pgm.id_misi IS NULL THEN 'Disetujui Bend. Pengeluaran'
                    WHEN lkgt.a_verif_kabag_keuangan = '3'
                    AND pgm.id_misi IS NULL THEN 'Ditolak Bend. Pengeluaran'
                    WHEN lkgt.a_verif_kabag_keuangan = '1'
                    AND pgm.id_misi IS NOT NULL THEN 'Belum Diverifikasi Kabag. Keuangan'
                    WHEN lkgt.a_verif_kabag_keuangan = '2'
                    AND pgm.id_misi IS NOT NULL THEN 'Disetujui Kabag. Keuangan'
                    WHEN lkgt.a_verif_kabag_keuangan = '3'
                    AND pgm.id_misi IS NOT NULL THEN 'Ditolak Kabag. Keuangan'
                    ELSE 'Belum Diverifikasi'
                END AS a_verif_kabag_keuangan,
                lkgt.id_verif_kabag_keuangan,
                lkgt.tgl_verif_kabag_keuangan,
                lkgt.catatan,
                lkgt.lokasi,
                lkgt.waktu_pelaksanaan,
                lkgt.waktu_selesai,
                lkgt.tahun,
                lkgt.created_at,
                lkgt.updated_at,
                lkgt.deleted_at,
                lkgt.id_updater,
                kgt.id_kegiatan,
                kgt.nm_kegiatan,
                (
                    SELECT
                        SUM(total)
                    FROM
                        detail_laksana_kegiatan
                    WHERE
                        deleted_at IS NULL
                        AND id_laksana_kegiatan = lkgt.id_laksana_kegiatan
                ) AS total_anggaran
            FROM
                laksana_kegiatan AS lkgt
                JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                AND kdiv.deleted_at IS NULL
                JOIN kegiatan AS kgt ON kgt.id_kegiatan = kdiv.id_kegiatan
                AND kgt.deleted_at IS NULL
                JOIN program AS pgm ON pgm.id_program = kgt.id_program
                AND pgm.deleted_at IS NULL
                AND pgm.a_aktif = '1'
            WHERE
                lkgt.deleted_at IS NULL
                AND lkgt.id_kegiatan_divisi = '" . $id_kegiatan_divisi . "'
            ORDER BY lkgt.created_at ASC
        ");
        $elemen = ($kegiatan[0]->nm_program=='Non Program (Pendapatan)') ? "'4'" : "'1','5'";
        $akun = \DB::SELECT("
            SELECT
                akn.id_akun,
                CONCAT(akn.elemen, akn.sub_elemen, akn.jenis, akn.no_akun) AS no_akun,
                akn.nm_akun
            FROM
                akun AS akn
            WHERE
                akn.elemen IN (".$elemen.")
                AND akn.no_akun > '0000'
                AND akn.deleted_at IS NULL
        ");
        $checkKdiv = \DB::SELECT("
            SELECT
                COUNT(rba.id_rba) AS jumlah
            FROM
                rba
                JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan_divisi=rba.id_kegiatan_divisi AND kdiv.deleted_at IS NULL
                JOIN kegiatan AS kgt ON kgt.id_kegiatan=kdiv.id_kegiatan AND kgt.deleted_at IS NULL
                JOIN divisi AS dvs ON dvs.id_divisi=kdiv.id_divisi AND dvs.deleted_at IS NULL
                LEFT JOIN program AS prog ON prog.id_program=kgt.id_program AND prog.deleted_at IS NULL
            WHERE
                rba.a_verif_wilayah = '1'
        ")[0];

        return view('pages._kepalaBagian.kegiatanMonitoring.viewDetail', compact('info', 'kegiatan', 'detailRba', 'akun', 'laksKegiatan', 'checkKdiv'));
    }

    public function apiGetAkun()
    {
        $akun = DB::table('akun')
            ->whereNull('deleted_at')
            ->where('elemen','5')
            ->where('sub_elemen','7')
            ->where('jenis','06')
            ->where('no_akun','>','0000')
            ->orderBy(DB::raw('LENGTH(keterangan), keterangan'))
            ->get();
        $filename = 'Akun Persediaan.xlsx';
        return Excel::download(new DetailRbaTemplate($akun), $filename);
    }

    public function apiCreateImportDetailRba()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_rba' => 'required|uuid',
                'file' => 'required'
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
            $id_detail_rba = guid();
            $id_rba = $this->request->id_rba;
            $file = $this->request->file('file');
            // membuat nama file unik
            $nama_file = rand().$file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('importPersediaan',$nama_file);
            Excel::import(new DetailRbaImport($id_rba), public_path('/importPersediaan/'.$nama_file));
            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Created',
                'error' => null,
                'response' => ['id_rba' => $id_rba]
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

    public function apiCreateDetailRba()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_rba' => 'required|uuid',
                'id_akun' => 'required|uuid',
                'vol' => 'required|numeric',
                'satuan' => 'required',
                'tarif' => 'required|numeric',
                'total' => 'required|numeric',
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
            $id_detail_rba = guid();
            $id_rba = $this->request->id_rba;
            $id_akun = $this->request->id_akun;
            $vol = $this->request->vol;
            $indikator = $this->request->indikator;
            $satuan = $this->request->satuan;
            $tarif = $this->request->tarif;
            $total = $this->request->total;
            $created_at = now();
            $id_updater = Auth::user()->id_user;
            $this->mDetailRba->create([
                'id_detail_rba' => $id_detail_rba,
                'id_rba' => $id_rba,
                'id_akun' => $id_akun,
                'vol' => $vol,
                'indikator' => $indikator,
                'satuan' => $satuan,
                'tarif' => $tarif,
                'total' => $total,
                'a_setuju' => 1,
                'created_at' => $created_at,
                'id_updater' => $id_updater,
            ]);
            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Created',
                'error' => null,
                'response' => ['id_detail_rba' => $id_detail_rba]
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

    public function apiUpdateDetailRba()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_detail_rba' => 'required|uuid',
                'id_akun' => 'required|uuid',
                'vol' => 'required|numeric',
                'satuan' => 'required',
                'tarif' => 'required|numeric',
                'total' => 'required|numeric',
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

            $id_detail_rba = $this->request->id_detail_rba;
            $id_akun = $this->request->id_akun;
            $vol = $this->request->vol;
            $indikator = $this->request->indikator;
            $satuan = $this->request->satuan;
            $tarif = $this->request->tarif;
            $total = $this->request->total;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mDetailRba->where('id_detail_rba', $id_detail_rba)->update([
                'id_akun' => $id_akun,
                'vol' => $vol,
                'indikator' => $indikator,
                'satuan' => $satuan,
                'tarif' => $tarif,
                'total' => $total,
                'updated_at' => $updated_at,
                'id_updater' => $id_updater,
            ]);

            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Updated',
                'error' => null,
                'response' => ['id_detail_rba' => $id_detail_rba]
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

    public function apiDeleteDetailRba()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_detail_rba.*' => 'required|uuid',
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

            $id_detail_rba = $idKegiatanDivisi ?? $this->request->id_detail_rba;
            $deleted_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mDetailRba->whereIn('id_detail_rba', $id_detail_rba)->update([
                'deleted_at' => $deleted_at,
                'id_updater' => $id_updater,
            ]);

            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Deleted',
                'error' => null,
                'response' => ['id_detail_rba' => $id_detail_rba]
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

    public function apiCreateLaksana()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_kegiatan_divisi' => 'required|uuid',
                'lokasi' => 'required',
                'waktu_pelaksanaan' => 'required|date',
                'waktu_selesai' => 'required|date',
                'tahun' => 'required|numeric',
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

            $id_laksana_kegiatan = guid();
            $id_kegiatan_divisi = $this->request->id_kegiatan_divisi;
            $lokasi = $this->request->lokasi;
            $waktu_pelaksanaan = $this->request->waktu_pelaksanaan;
            $waktu_selesai = $this->request->waktu_selesai;
            $tahun = $this->request->tahun;
            $created_at = now();
            $id_updater = Auth::user()->id_user;

            if($waktu_pelaksanaan == $waktu_selesai) {
                $waktu_selesai = date('Y-m-d\TH:i', strtotime($waktu_selesai) + 60);
            }

            $urutan_laksana_kegiatan =
                DB::select("
                    SELECT
                        MAX(urutan_laksana_kegiatan) AS urutan
                    FROM
                        laksana_kegiatan
                    WHERE
                        deleted_at IS NULL
                    AND id_kegiatan_divisi = '" . $id_kegiatan_divisi . "'
                ")[0]->urutan;

            $this->mLaksanaKegiatan->create([
                'id_laksana_kegiatan' => $id_laksana_kegiatan,
                'id_kegiatan_divisi' => $id_kegiatan_divisi,
                'urutan_laksana_kegiatan' => $urutan_laksana_kegiatan + 1,
                'a_verif_kabag_keuangan' => '1',
                'tgl_verif_kabag_keuangan' => null,
                'lokasi' => $lokasi,
                'waktu_pelaksanaan' => $waktu_pelaksanaan,
                'waktu_selesai' => $waktu_selesai,
                'tahun' => $tahun,
                'created_at' => $created_at,
                'id_updater' => $id_updater,
            ]);

            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Created',
                'error' => null,
                'response' => ['id_kegiatan_divisi' => $id_kegiatan_divisi]
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

    public function apiUpdateLaksana()
    {
        try {
            DB::beginTransaction();
            $type_request = $this->request->type_request;
            if ($type_request == 'ajuan') {
                $rules = [
                    'id_laksana_kegiatan' => 'required|uuid',
                ];
            } else {
                $rules = [
                    'id_laksana_kegiatan' => 'required|uuid',
                    'lokasi' => 'required',
                    'waktu_pelaksanaan' => 'required',
                    'waktu_selesai' => 'required',
                    'tahun' => 'required',
                ];
            }
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
            $lokasi = $this->request->lokasi;
            $waktu_pelaksanaan = $this->request->waktu_pelaksanaan;
            $waktu_selesai = $this->request->waktu_selesai;
            $tahun = $this->request->tahun;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;

            if ($type_request == 'ajuan') {
                $check = $this->mDetailLaksanaKegiatan->where('id_laksana_kegiatan', $id_laksana_kegiatan)->count();
                if ($check < 1) {
                    return [
                        'status' => false,
                        'latency' => AppLatency(),
                        'message' => 'Rincian Detail Pelaksanaan Belum Anda Tambahkan!',
                        'error' => null,
                        'response' => ['id_laksana_kegiatan' => $id_laksana_kegiatan]
                    ];
                }
            }

            $this->mLaksanaKegiatan->where('id_laksana_kegiatan', $id_laksana_kegiatan)->update([
                'tgl_ajuan' => $type_request == 'ajuan' ? now() : DB::raw('tgl_ajuan'),
                'a_verif_kabag_keuangan' => $type_request == 'ajuan' ? '1' : DB::raw('a_verif_kabag_keuangan'),
                'tgl_verif_kabag_keuangan' => null,
                'lokasi' => $lokasi ?? DB::raw('lokasi'),
                'waktu_pelaksanaan' => $waktu_pelaksanaan ?? DB::raw('waktu_pelaksanaan'),
                'waktu_selesai' => $waktu_selesai ?? DB::raw('waktu_selesai'),
                'tahun' => $tahun ?? DB::raw('tahun'),
                'updated_at' => $updated_at,
                'id_updater' => $id_updater,
            ]);

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

    public function apiDeleteLaksana()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_laksana_kegiatan.*' => 'required|uuid',
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
            $deleted_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mLaksanaKegiatan->whereIn('id_laksana_kegiatan', $id_laksana_kegiatan)->update([
                'deleted_at' => $deleted_at,
                'id_updater' => $id_updater,
            ]);

            $this->mDetailLaksanaKegiatan->whereIn('id_laksana_kegiatan', $id_laksana_kegiatan)->update([
                'deleted_at' => $deleted_at,
                'id_updater' => $id_updater,
            ]);

            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Deleted',
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

    public function viewGetAllLaksanaDetail()
    {
        $id_laksana_kegiatan = $this->request->id_laksana_kegiatan;
        $info = [
            'title' => 'Detail Pelaksanaan Kegiatan',
            'site_active' => 'KegiatanDiajukan',
        ];

        $kegiatan = DB::select("
            SELECT
                lkgt.id_laksana_kegiatan,
                div.nm_divisi,
                msi.nm_misi,
                msi.periode AS periode_misi,
                pgm.nm_program,
                pgm.periode AS periode_program,
                kgt.nm_kegiatan,
                lkgt.urutan_laksana_kegiatan,
                lkgt.tgl_ajuan,
                lkgt.lokasi,
                lkgt.waktu_pelaksanaan,
                lkgt.waktu_selesai,
                CASE
                    WHEN lkgt.a_verif_kabag_keuangan = '1'
                    AND pgm.id_misi IS NULL THEN 'Belum Diverifikasi Bend. Pengeluaran'
                    WHEN lkgt.a_verif_kabag_keuangan = '2'
                    AND pgm.id_misi IS NULL THEN 'Disetujui Bend. Pengeluaran'
                    WHEN lkgt.a_verif_kabag_keuangan = '3'
                    AND pgm.id_misi IS NULL THEN 'Ditolak Bend. Pengeluaran'
                    WHEN lkgt.a_verif_kabag_keuangan = '1'
                    AND pgm.id_misi IS NOT NULL THEN 'Belum Diverifikasi Kabag. Keuangan'
                    WHEN lkgt.a_verif_kabag_keuangan = '2'
                    AND pgm.id_misi IS NOT NULL THEN 'Disetujui Kabag. Keuangan'
                    WHEN lkgt.a_verif_kabag_keuangan = '3'
                    AND pgm.id_misi IS NOT NULL THEN 'Ditolak Kabag. Keuangan'
                    ELSE 'Belum Diverifikasi'
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
                JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                AND kdiv.deleted_at IS NULL
                JOIN rba AS rba ON rba.id_kegiatan_divisi = lkgt.id_kegiatan_divisi
                AND rba.deleted_at IS NULL
                JOIN detail_rba AS drba ON drba.id_rba = rba.id_rba
                AND drba.deleted_at IS NULL
                JOIN akun AS akn ON akn.id_akun = drba.id_akun
                AND akn.deleted_at IS NULL
            WHERE
                lkgt.deleted_at IS NULL
                AND lkgt.id_laksana_kegiatan = '" . $id_laksana_kegiatan . "'
            ORDER BY no_akun ASC
        ");

        return view('pages._kepalaBagian.kegiatanMonitoring.viewGetAllLaksanaDetail', compact('info', 'kegiatan', 'detailLaks', 'akun'));
    }

    public function apiCreateDetailLaksana()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_laksana_kegiatan' => 'required',
                'id_detail_rba' => 'required',
                'total' => 'required',
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

            $id_detail_laksana_kegiatan = guid();
            $id_laksana_kegiatan = $this->request->id_laksana_kegiatan;
            $id_detail_rba = $this->request->id_detail_rba;
            $total = $this->request->total;
            $created_at = now();
            $id_updater = Auth::user()->id_user;

            //CHECK
            $rbaCheck = $this->mDetailRba->where('id_detail_rba', $id_detail_rba)->sum('total');
            $laksKegCheck = $this->mDetailLaksanaKegiatan->where('id_detail_rba', $id_detail_rba)->sum('total');
            $checkSum = $rbaCheck - $laksKegCheck;
            if($total > $checkSum) {
                return [
                    'status' => false,
                    'latency' => AppLatency(),
                    'message' => 'BadRequest',
                    'error' => $validator->errors(),
                    'response' => null
                ];
            }

            $this->mDetailLaksanaKegiatan->create([
                'id_detail_laksana_kegiatan' => $id_detail_laksana_kegiatan,
                'id_laksana_kegiatan' => $id_laksana_kegiatan,
                'id_detail_rba' => $id_detail_rba,
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
                'response' => ['id_detail_laksana_kegiatan' => $id_detail_laksana_kegiatan]
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

    public function apiUpdateDetailLaksana()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_detail_laksana_kegiatan' => 'required',
                'id_laksana_kegiatan' => 'required',
                'id_detail_rba' => 'required',
                'total' => 'required',
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

            $id_detail_laksana_kegiatan = $this->request->id_detail_laksana_kegiatan;
            $id_laksana_kegiatan = $this->request->id_laksana_kegiatan;
            $id_detail_rba = $this->request->id_detail_rba;
            $total = $this->request->total;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mDetailLaksanaKegiatan->where('id_detail_laksana_kegiatan', $id_detail_laksana_kegiatan)->update([
                'id_detail_laksana_kegiatan' => $id_detail_laksana_kegiatan,
                'id_laksana_kegiatan' => $id_laksana_kegiatan,
                'id_detail_rba' => $id_detail_rba,
                'total' => $total,
                'updated_at' => $updated_at,
                'id_updater' => $id_updater,
            ]);

            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Updated',
                'error' => null,
                'response' => ['id_detail_laksana_kegiatan' => $id_detail_laksana_kegiatan]
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

    public function apiDeleteDetailLaksana()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_detail_laksana_kegiatan.*' => 'required|uuid',
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

            $id_detail_laksana_kegiatan = $this->request->id_detail_laksana_kegiatan;
            $deleted_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mDetailLaksanaKegiatan->whereIn('id_detail_laksana_kegiatan', $id_detail_laksana_kegiatan)->update([
                'deleted_at' => $deleted_at,
                'id_updater' => $id_updater,
            ]);

            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Deleted',
                'error' => null,
                'response' => ['id_detail_laksana_kegiatan' => $id_detail_laksana_kegiatan]
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
}
