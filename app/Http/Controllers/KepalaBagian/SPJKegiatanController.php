<?php

namespace App\Http\Controllers\KepalaBagian;

use App\Models\Spj;
use App\Models\DetailSpj;
use App\Models\Dokumen;
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
    private $mDokumen;
    public function __construct()
    {
        $this->request = app(Request::class);
        $this->mSpj = app(Spj::class);
        $this->mDetailSpj = app(DetailSpj::class);
        $this->mDokumen = app(Dokumen::class);
    }

    public function apiGetAll()
    {
        $kegiatan = ($this->request->nm_kegiatan != '-') ? " AND kdiv.id_kegiatan='".$this->request->nm_kegiatan."' " : "";
        $apiGetAll = DB::select("
            SELECT
                DISTINCT ON (bku.id_laksana_kegiatan) bku.id_laksana_kegiatan,
                bku.id_bku,
                bku.id_divisi,
                dvs.nm_divisi,
                lkgt.urutan_laksana_kegiatan,
                kgt.nm_kegiatan,
                CONCAT('[ ', pgm.periode ,' ] ', pgm.nm_program) AS nm_program,
                CONCAT('[ ', msi.periode ,' ] ', msi.nm_misi) AS nm_misi,
                spj.a_verif_kabag_keuangan,
                spj.a_verif_bendahara_pengeluaran,
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
                        SUM(dspj.total)
                    FROM
                        detail_spj AS dspj
                    WHERE
                        dspj.id_spj = spj.id_spj
                        AND dspj.deleted_at IS NULL
                ) AS total_realisasi
            FROM
                bku AS bku
                JOIN laksana_kegiatan AS lkgt ON lkgt.id_laksana_kegiatan = bku.id_laksana_kegiatan
                AND lkgt.deleted_at IS NULL
                JOIN spj ON spj.id_laksana_kegiatan=lkgt.id_laksana_kegiatan AND spj.deleted_at IS NULL
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
                AND pgm.id_misi IS NOT NULL
                AND bku.id_divisi = '" . Auth::user()->id_divisi . "'
                ".$kegiatan."
        ");
        return DaTables::of($apiGetAll)
            ->editColumn('total_masuk', function ($ed) {
                return number_to_currency($ed->total_masuk, 0);
            })
            ->editColumn('total_realisasi', function ($ed) {
                return number_to_currency($ed->total_realisasi, 0);
            })
            ->addColumn('status', function($data) {
                if(is_null($data->a_verif_bendahara_pengeluaran) AND is_null($data->a_verif_kabag_keuangan)) {
                    return 'Belum Disimpan';
                } else if($data->a_verif_bendahara_pengeluaran == '1' OR $data->a_verif_kabag_keuangan == '1') {
                    return 'Belum Diverifikasi';
                } else if($data->a_verif_bendahara_pengeluaran == '3' OR $data->a_verif_kabag_keuangan == '3') {
                    return 'Ditolak';
                } else {
                    return 'Disetujui';
                }
            })
            ->rawColumns(['status'])
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
                'dokumen' => 'required'
            ];
            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails() OR $this->request->total < 1) {
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

            $totDetLaks = \App\Models\DetailLaksanaKegiatan::find($id_detail_laksana_kegiatan);
            $totDetSpj = $this->mDetailSpj->where('id_detail_laksana_kegiatan', $id_detail_laksana_kegiatan)->whereNull('deleted_at')->sum('total');
            $sumCheck = $totDetLaks->total - $totDetSpj;
            if($total > $sumCheck) {
                return [
                    'status' => false,
                    'latency' => AppLatency(),
                    'message' => 'Total Melebihi Batas Anggaran!<br>Tersisa '.number_to_currency_without_rp($sumCheck, 0),
                    'error' => $validator->errors(),
                    'response' => null
                ];
            }

            //Read Image
            $image = $this->request->file('dokumen');
            $filename = $image->getClientOriginalName();
            $filepath = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('storage/uploads'), $filepath);
            //Store Image
            $dokumen = $this->mDokumen->create([
                'nm_dokumen' => $filepath,
                'nm_asli_dokumen' => $filename,
                'created_at' => now(),
                'id_updater' => $id_updater
            ]);
            $id_dokumen = $dokumen->id_dokumen;

            $this->mDetailSpj->create([
                'id_detail_spj' => $id_detail_spj,
                'id_spj' => $id_spj,
                'id_detail_laksana_kegiatan' => $id_detail_laksana_kegiatan,
                'id_akun' => $id_akun,
                'total' => $total,
                'created_at' => $created_at,
                'id_updater' => $id_updater,
                'id_dokumen' => $id_dokumen
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

    public function apiUpdateDetailSpj()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_detail_spj' => 'required',
                'id_spj' => 'required',
                'id_detail_laksana_kegiatan' => 'required',
                'id_akun' => 'required',
                'total' => 'required',
                'dokumen' => 'required'
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

            $id_detail_spj = $this->request->id_detail_spj;
            $id_spj = $this->request->id_spj;
            $id_detail_laksana_kegiatan = $this->request->id_detail_laksana_kegiatan;
            $id_akun = $this->request->id_akun;
            $total = $this->request->total;
            $updated_at = now();
            $id_updater = Auth::user()->id_user;

            $totDetLaks = \App\Models\DetailLaksanaKegiatan::find($id_detail_laksana_kegiatan);
            $totDetSpj = $this->mDetailSpj->where('id_detail_laksana_kegiatan', $id_detail_laksana_kegiatan)->whereNull('deleted_at')->sum('total');
            $lastTotDetSpj = $this->mDetailSpj::find($id_detail_spj);
            $sumCheck = $totDetLaks->total - ($totDetSpj - $lastTotDetSpj->total);
            if($total > $sumCheck) {
                return [
                    'status' => false,
                    'latency' => AppLatency(),
                    'message' => 'Total Melebihi Batas Anggaran!<br>Tersisa '.number_to_currency_without_rp($sumCheck, 0),
                    'error' => $validator->errors(),
                    'response' => null
                ];
            }

            //Read Image
            $image = $this->request->file('dokumen');
            $filename = $image->getClientOriginalName();
            $filepath = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('storage/uploads'), $filepath);
            //Store Image
            $dokumen = $this->mDokumen->create([
                'nm_dokumen' => $filepath,
                'nm_asli_dokumen' => $filename,
                'created_at' => now(),
                'id_updater' => $id_updater
            ]);
            $id_dokumen = $dokumen->id_dokumen;

            $this->mDetailSpj->where('id_detail_spj', $id_detail_spj)->update([
                'id_spj' => $id_spj,
                'id_detail_laksana_kegiatan' => $id_detail_laksana_kegiatan,
                'id_akun' => $id_akun,
                'total' => $total,
                'updated_at' => $updated_at,
                'id_updater' => $id_updater,
                'id_dokumen' => $id_dokumen
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

    public function apiDeleteDetailSpj()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_detail_spj.*' => 'required|uuid',
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

            $id_detail_spj = $this->request->id_detail_spj;
            $deleted_at = now();
            $id_updater = Auth::user()->id_user;

            $this->mDetailSpj->whereIn('id_detail_spj', $id_detail_spj)->update([
                'deleted_at' => $deleted_at,
                'id_updater' => $id_updater,
            ]);

            DB::commit();
            return [
                'status' => true,
                'latency' => AppLatency(),
                'message' => 'Deleted',
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

    public function apiUpdate()
    {
        try {
            DB::beginTransaction();
            $rules = [
                'id_spj' => 'required'
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
            $tgl_ajuan = now();
            $updated_at = now();
            $id_updater = Auth::user()->id_user;
            $check = $this->mDetailSpj->where('id_spj', $id_spj)->whereNull('deleted_at')->count();
            if ($check == 0) {
                return [
                    'status' => false,
                    'latency' => AppLatency(),
                    'message' => 'Rincian Detail SPJ Belum Anda Tambahkan!',
                    'error' => null,
                    'response' => ['id_spj' => $id_spj]
                ];
            } else {
                $this->mSpj->where('id_spj', $id_spj)->update([
                    'tgl_ajuan' => $tgl_ajuan,
                    'a_verif_kabag_keuangan' => '1',
                    'tgl_verif_kabag_keuangan' => null,
                    'id_verif_kabag_keuangan' => null,
                    'updated_at' => $updated_at,
                    'id_updater' => $id_updater,
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
        $kegiatan = \DB::SELECT("
            SELECT
                kgt.*
            FROM
                kegiatan AS kgt
                JOIN kegiatan_divisi AS kdiv ON kdiv.id_kegiatan=kgt.id_kegiatan AND kdiv.deleted_at IS NULL
                JOIN program AS pr ON pr.id_program=kgt.id_program AND pr.deleted_at IS NULL
            WHERE
                pr.id_misi IS NOT NULL
                AND kdiv.id_divisi='".\Auth::user()->id_divisi."'
        ");
        return view('pages._kepalaBagian.spj.viewGetAll', compact('info','kegiatan'));
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
                CONCAT(akun.elemen, akun.sub_elemen, akun.jenis, akun.no_akun) AS no_akun,
                akun.nm_akun
            FROM
                detail_laksana_kegiatan AS dlaks
                JOIN detail_rba AS drba ON drba.id_detail_rba=dlaks.id_detail_rba
                JOIN akun ON akun.id_akun=drba.id_akun
            WHERE
                dlaks.id_laksana_kegiatan='".$id_laksana_kegiatan."'
        ");

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
        $spj = \App\Models\SPJ::where('id_laksana_kegiatan', $id_laksana_kegiatan)->whereNull('deleted_at')->first();

        return view('pages._kepalaBagian.spj.viewDetail', compact('info', 'bku', 'detLaks', 'akun', 'spj'));
    }
}
