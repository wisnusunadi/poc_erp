<?php

namespace App\Http\Controllers;

use App\Exports\KontrolLab;
use App\Exports\KontrolLabs;
use App\Models\DetailMetodeLab;
use App\Models\DetailPesananProduk;
use App\Models\Ekatalog;
use App\Models\JenisPemilik;
use App\Models\KodeLab;
use App\Models\MetodeLab;
use App\Models\NoseriDetailPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\RiwayatTf;
use App\Models\RuangKalibrasi;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\SystemLog;
use App\Models\UjiLab;
use App\Models\UjiLabDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class LabController extends Controller
{
    //
    public function riwayat_lab_uji(Request $request)
    {
        $obj =  json_decode(json_encode($request->all()), FALSE);
        dd($obj);
        if ($request->customer->value == null) {
            $data = UjiLabDetail::select(
                'jenis_pemilik.nama as jp',
                'pesanan.no_po',
                'uji_lab.pesanan_id as p_id',
                'karyawans.nama as pemeriksa',
                'uji_lab.no_order',
                'uji_lab.nama',
                'uji_lab.alamat',
                'uji_lab_detail.no',
                'uji_lab_detail.no_sertifikat',
                'uji_lab_detail.tgl_masuk',
                'uji_lab_detail.status',
                'metode_lab.metode',
                'produk.nama as produk',
                'noseri_barang_jadi.noseri',
                'uji_lab_detail.tgl_kalibrasi'
            )
                ->leftJoin('uji_lab', 'uji_lab.id', '=', 'uji_lab_detail.uji_lab_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'uji_lab_detail.detail_pesanan_produk_id')
                ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'uji_lab_detail.noseri_id')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
                ->leftJoin('detail_metode_lab', 'detail_metode_lab.id', '=', 'uji_lab_detail.metode_id')
                ->leftJoin('metode_lab', 'metode_lab.id', '=', 'detail_metode_lab.metode_lab_id')
                ->leftJoin('erp_kesehatan.karyawans', 'karyawans.id', '=', 'uji_lab_detail.pemeriksa_id')
                ->leftJoin('pesanan', 'pesanan.id', '=', 'uji_lab.pesanan_id')
                ->leftJoin('jenis_pemilik', 'jenis_pemilik.id', '=', 'uji_lab.jenis_pemilik_id')
                ->where('uji_lab_detail.status', '!=', 'belum')
                ->whereBetween('uji_lab_detail.tgl_masuk', [$obj->tanggal_awal, $obj->tanggal_akhir]);
            // ->whereYear('uji_lab_detail.created_at', $request->years );

            $spb = Spb::select('spb.pesanan_id as id', 'customer.nama', 'spb.ket')
                ->selectRaw('"" AS no_paket')
                ->selectRaw('"-" AS instansi')
                ->selectRaw('"-" AS alamat_instansi')
                ->selectRaw('"-" AS status')
                ->selectRaw('"-" AS satuan')
                ->selectRaw('"-" AS no_urut')
                ->selectRaw('"-" AS tgl_buat')
                ->selectRaw('"-" AS tgl_kontrak')
                ->leftJoin('customer', 'customer.id', '=', 'spb.customer_id')
                ->whereIn('spb.pesanan_id', $data->pluck('p_id')->toArray())->get();

            $spa = Spa::select('spa.pesanan_id as id', 'customer.nama', 'spa.ket')
                ->selectRaw('"" AS no_paket')
                ->selectRaw('"-" AS instansi')
                ->selectRaw('"-" AS alamat_instansi')
                ->selectRaw('"-" AS status')
                ->selectRaw('"-" AS satuan')
                ->selectRaw('"-" AS no_urut')
                ->selectRaw('"-" AS tgl_buat')
                ->selectRaw('"-" AS tgl_kontrak')
                ->leftJoin('customer', 'customer.id', '=', 'spa.customer_id')
                ->whereIn('spa.pesanan_id', $data->pluck('p_id')->toArray())->get();

            $ekatalog = Ekatalog::select('ekatalog.pesanan_id as id', 'ekatalog.ket', 'ekatalog.tgl_buat', 'ekatalog.tgl_kontrak', 'ekatalog.no_urut as no_urut', 'customer.nama', 'ekatalog.no_paket', 'ekatalog.instansi', 'ekatalog.alamat as alamat_instansi', 'ekatalog.satuan', 'ekatalog.status')
                ->leftJoin('customer', 'customer.id', '=', 'ekatalog.customer_id')
                ->whereIn('ekatalog.pesanan_id', $data->pluck('p_id')->toArray())->get();

            $dataInfo =   $ekatalog->merge($spa)->merge($spb);
        } else {
            $data = UjiLabDetail::select(
                'jenis_pemilik.nama as jp',
                'pesanan.no_po',
                'uji_lab.pesanan_id as p_id',
                'karyawans.nama as pemeriksa',
                'uji_lab.no_order',
                'uji_lab.nama',
                'uji_lab.alamat',
                'uji_lab_detail.no',
                'uji_lab_detail.no_sertifikat',
                'uji_lab_detail.tgl_masuk',
                'uji_lab_detail.status',
                'metode_lab.metode',
                'produk.nama as produk',
                'noseri_barang_jadi.noseri',
                'uji_lab_detail.tgl_kalibrasi'
            )
                ->leftJoin('uji_lab', 'uji_lab.id', '=', 'uji_lab_detail.uji_lab_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'uji_lab_detail.detail_pesanan_produk_id')
                ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'uji_lab_detail.noseri_id')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
                ->leftJoin('detail_metode_lab', 'detail_metode_lab.id', '=', 'uji_lab_detail.metode_id')
                ->leftJoin('metode_lab', 'metode_lab.id', '=', 'detail_metode_lab.metode_lab_id')
                ->leftJoin('erp_kesehatan.karyawans', 'karyawans.id', '=', 'uji_lab_detail.pemeriksa_id')
                ->leftJoin('pesanan', 'pesanan.id', '=', 'uji_lab.pesanan_id')
                ->leftJoin('jenis_pemilik', 'jenis_pemilik.id', '=', 'uji_lab.jenis_pemilik_id')
                ->where('uji_lab_detail.status', '!=', 'belum')
                ->whereBetween('uji_lab_detail.tgl_masuk', [$obj->tanggal_awal, $obj->tanggal_akhir]);
            // ->whereYear('uji_lab_detail.created_at', $request->years );

            $spb = Spb::select('spb.pesanan_id as id', 'customer.nama', 'spb.ket')
                ->selectRaw('"" AS no_paket')
                ->selectRaw('"-" AS instansi')
                ->selectRaw('"-" AS alamat_instansi')
                ->selectRaw('"-" AS status')
                ->selectRaw('"-" AS satuan')
                ->selectRaw('"-" AS no_urut')
                ->selectRaw('"-" AS tgl_buat')
                ->selectRaw('"-" AS tgl_kontrak')
                ->leftJoin('customer', 'customer.id', '=', 'spb.customer_id')
                ->where('customer.id', $obj->customer->value)
                ->whereIn('spb.pesanan_id', $data->pluck('p_id')->toArray())->get();

            $spa = Spa::select('spa.pesanan_id as id', 'customer.nama', 'spa.ket')
                ->selectRaw('"" AS no_paket')
                ->selectRaw('"-" AS instansi')
                ->selectRaw('"-" AS alamat_instansi')
                ->selectRaw('"-" AS status')
                ->selectRaw('"-" AS satuan')
                ->selectRaw('"-" AS no_urut')
                ->selectRaw('"-" AS tgl_buat')
                ->selectRaw('"-" AS tgl_kontrak')
                ->leftJoin('customer', 'customer.id', '=', 'spa.customer_id')
                ->where('customer.id', $obj->customer->value)
                ->whereIn('spa.pesanan_id', $data->pluck('p_id')->toArray())->get();

            $ekatalog = Ekatalog::select('ekatalog.pesanan_id as id', 'ekatalog.ket', 'ekatalog.tgl_buat', 'ekatalog.tgl_kontrak', 'ekatalog.no_urut as no_urut', 'customer.nama', 'ekatalog.no_paket', 'ekatalog.instansi', 'ekatalog.alamat as alamat_instansi', 'ekatalog.satuan', 'ekatalog.status')
                ->leftJoin('customer', 'customer.id', '=', 'ekatalog.customer_id')
                ->where('customer.id', $obj->customer->value)
                ->whereIn('ekatalog.pesanan_id', $data->pluck('p_id')->toArray())
                ->get();

            $dataInfo =   $ekatalog->merge($spa)->merge($spb);
        }




        if ($data->get()->isEmpty()) {
            $obj = array();
            return response()->json($obj);
        } else {
            foreach ($data->get() as $d) {
                $obj[] = array(
                    'no' => str_pad($d->no, 4, '0', STR_PAD_LEFT),
                    'p_id' => $d->p_id,
                    'no_po' => $d->no_po,
                    'no_order' => 'LAB-' . str_pad($d->no_order, 4, '0', STR_PAD_LEFT),
                    'tgl_masuk' => $d->tgl_masuk,
                    'nama_alat' => $d->metode,
                    'type' => $d->produk,
                    'noseri' => $d->noseri,
                    'nama_pemilik' => $d->jp,
                    'nama_pemilik_sert' => $d->nama,
                    'alamat' => $d->alamat,
                    'tgl_kalibrasi' => $d->tgl_kalibrasi,
                    'no_sertifikat' => $d->no_sertifikat,
                    'pemeriksa' => $d->pemeriksa,
                    'hasil' => $d->status == 'ok' ? 'Lolos Kalibrasi' : 'Tidak Lolos Kalibrasi',
                    // 'info' => $dataInfo->where('id',$d->p_id)->toArray(),
                    'nosj' => str_pad($d->no_order, 4, '0', STR_PAD_LEFT) . '/LAB/' . date('m', strtotime($d->tgl_kalibrasi)) . '/' . date('y', strtotime($d->tgl_kalibrasi)),
                );
            }
        }
        $merge = [];
        foreach ($obj as $labItem) {
            $mergedItem = $labItem;
            $p_id = $labItem['p_id'];

            foreach ($dataInfo as $poItem) {
                if ($poItem['id'] === $p_id) {
                    $mergedItem['info'] = $poItem;
                    break;
                }
            }
            $merge[] = $mergedItem;
        }

        return response()->json($merge);
        // $years = $request->years;
        // $data = SystemLog::where(['tipe' => 'Lab', 'subjek' => 'Kalibrasi Produk'])->whereYear('created_at', $years)->get();

        // $obj = [];

        // foreach ($data as $d) {
        //     $x = json_decode($d->response);
        //     $obj[] = array(
        //         'id' => $d->id,
        //         'order' => $x->no_order,
        //         'nama' => $x->nama,
        //         'jenis_pemilik' => $x->jenis_pemilik->label,
        //         'so' => $x->so,
        //         'customer' => $x->customer,
        //         'tgl_kalibrasi' => $x->tgl_kalibrasi,
        //         'jenis_transaksi' => 'internal',
        //         'hasil' => $x->hasil,
        //         'produk' => $x->produk
        //     );
        // }
        // return response()->json($obj);
    }

    public function export_laporan(Request $request)
    {
        $waktu = Carbon::now();
        $years = $request->years;
        return Excel::download(new KontrolLabs($request->dsb, $request->tanggal_awal, $request->tanggal_akhir), 'Kontrol Kalibrasi  ' . $waktu->toDateTimeString() . '.xlsx');

        $data = UjiLabDetail::select(
            'jenis_pemilik.nama as jp',
            'pesanan.no_po',
            'uji_lab.pesanan_id as p_id',
            'karyawans.nama as pemeriksa',
            'uji_lab.no_order',
            'uji_lab.nama',
            'uji_lab.alamat',
            'uji_lab_detail.no',
            'uji_lab_detail.no_sertifikat',
            'uji_lab_detail.tgl_masuk',
            'metode_lab.metode',
            'produk.nama as produk',
            'noseri_barang_jadi.noseri',
            'uji_lab_detail.tgl_kalibrasi'
        )
            ->leftJoin('uji_lab', 'uji_lab.id', '=', 'uji_lab_detail.uji_lab_id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'uji_lab_detail.detail_pesanan_produk_id')
            ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
            ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
            ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'uji_lab_detail.noseri_id')
            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
            ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
            ->leftJoin('detail_metode_lab', 'detail_metode_lab.id', '=', 'uji_lab_detail.metode_id')
            ->leftJoin('metode_lab', 'metode_lab.id', '=', 'detail_metode_lab.metode_lab_id')
            ->leftJoin('erp_kesehatan.karyawans', 'karyawans.id', '=', 'uji_lab_detail.pemeriksa_id')
            ->leftJoin('pesanan', 'pesanan.id', '=', 'uji_lab.pesanan_id')
            ->leftJoin('jenis_pemilik', 'jenis_pemilik.id', '=', 'uji_lab.jenis_pemilik_id')
            ->where('uji_lab_detail.status', '!=', 'belum');

        $spb = Spb::select('spb.pesanan_id as id', 'customer.nama', 'spb.ket')
            ->selectRaw('"" AS no_paket')
            ->selectRaw('"-" AS instansi')
            ->selectRaw('"-" AS alamat_instansi')
            ->selectRaw('"-" AS status')
            ->selectRaw('"-" AS satuan')
            ->selectRaw('"-" AS no_urut')
            ->selectRaw('"-" AS tgl_buat')
            ->selectRaw('"-" AS tgl_kontrak')
            ->leftJoin('customer', 'customer.id', '=', 'spb.customer_id')
            ->whereIn('spb.pesanan_id', $data->pluck('p_id')->toArray())->get();

        $spa = Spa::select('spa.pesanan_id as id', 'customer.nama', 'spa.ket')
            ->selectRaw('"" AS no_paket')
            ->selectRaw('"-" AS instansi')
            ->selectRaw('"-" AS alamat_instansi')
            ->selectRaw('"-" AS status')
            ->selectRaw('"-" AS satuan')
            ->selectRaw('"-" AS no_urut')
            ->selectRaw('"-" AS tgl_buat')
            ->selectRaw('"-" AS tgl_kontrak')
            ->leftJoin('customer', 'customer.id', '=', 'spa.customer_id')
            ->whereIn('spa.pesanan_id', $data->pluck('p_id')->toArray())->get();

        $ekatalog = Ekatalog::select('ekatalog.pesanan_id as id', 'ekatalog.ket', 'ekatalog.tgl_buat', 'ekatalog.tgl_kontrak', 'ekatalog.no_urut as no_urut', 'customer.nama', 'ekatalog.no_paket', 'ekatalog.instansi', 'ekatalog.alamat as alamat_instansi', 'ekatalog.satuan', 'ekatalog.status')
            ->leftJoin('customer', 'customer.id', '=', 'ekatalog.customer_id')
            ->whereIn('ekatalog.pesanan_id', $data->pluck('p_id')->toArray())->get();

        $dataInfo =   $ekatalog->merge($spa)->merge($spb);

        foreach ($data->get() as $d) {
            $obj[] = array(
                'no' => str_pad($d->no, 4, '0', STR_PAD_LEFT),
                'p_id' => $d->p_id,
                'no_po' => $d->no_po,
                'no_order' => 'LAB-' . str_pad($d->no_order, 4, '0', STR_PAD_LEFT),
                'tgl_masuk' => $d->tgl_masuk,
                'nama_alat' => $d->metode,
                'type' => $d->produk,
                'noseri' => $d->noseri,
                'nama_pemilik' => $d->jp,
                'nama_pemilik_sert' => $d->nama,
                'alamat' => $d->alamat,
                'tgl_kalibrasi' => $d->tgl_kalibrasi,
                'no_sertifikat' => $d->no_sertifikat,
                'pemeriksa' => $d->pemeriksa,
                // 'info' => $dataInfo->where('id',$d->p_id)->toArray(),
                'nosj' => str_pad($d->no_order, 4, '0', STR_PAD_LEFT) . '/LAB/' . date('m', strtotime($d->tgl_kalibrasi)) . '/' . date('y', strtotime($d->tgl_kalibrasi)),
            );
        }
        $merge = [];
        foreach ($obj as $labItem) {
            $mergedItem = $labItem;
            $p_id = $labItem['p_id'];

            foreach ($dataInfo as $poItem) {
                if ($poItem['id'] === $p_id) {
                    $mergedItem['info'] = $poItem;
                    break;
                }
            }
            $merge[] = $mergedItem;
        }
        return response()->json($merge);
    }
    public function bom_detail($id)
    {
        return view('page.teknik.bom.detail', ['id' => $id]);
    }

    public function bom_data_produk($id)
    {
        return view('page.teknik.bom.data.produk', ['id' => $id]);
    }

    public function kode_lab_update(Request $request, $id)
    {
        ///dd($request->all());

        $rules = [
            'kode' => 'required |unique:kode_lab,kode,' . $id,
            'nama' => 'required |unique:kode_lab,nama,' . $id,
        ];



        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'message' => 'Cek Kembali Form',
            ], 200);
        } else {
            DB::beginTransaction();
            try {
                //code...
                $kode = KodeLab::find($request->id);
                $kode->kode = $request->kode;
                $kode->nama = $request->nama;
                $kode->save();
                $getDB = $kode->Produk->pluck('id');


                foreach ($request->produk as $r) {
                    $getReq[] = $r['produk']['id'];
                }
                $getDBs = collect($getDB)->toArray();
                $difference_a = array_diff($getDBs, $getReq);
                $difference_b = array_diff($getReq, $getDBs);


                if (!empty($difference_a) || !empty($difference_b)) {
                    for ($j = 0; $j < count($getDBs); $j++) {
                        Produk::where('id', $getDBs[$j])
                            ->update(['kode_lab_id' => NULL]);
                    }

                    foreach ($request->produk as $q) {
                        Produk::where('id', $q['produk']['id'])
                            ->update(['kode_lab_id' => $request->id]);
                    }
                }
                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil',
                ], 200);
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollBack();
                return response()->json([
                    'status' => 404,
                    'message' => 'Transaksi Update Gagal' . $th,
                ], 500);
            }
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
            ], 200);
        }
        // if($request->kode == "" || $request->nama == ""){
        //     return response()->json([
        //         'status' => 404,
        //         'message' => 'Cek Kembali Form',
        //     ], 404);
        // }else{
        //     $kode = KodeLab::find($id);
        //     $kode->kode = $request->kode;
        //     $kode->nama = $request->nama;
        //     $kode->save();

        //     // produk belum update

        //     return response()->json([
        //         'status' => 200,
        //         'message' => 'Berhasil',
        //     ], 200);
        // }
    }

    public function kode_lab_detail($id)
    {
        $data = KodeLab::find($id);
        $object = new stdClass();
        $object->kode = $data->kode;
        $object->nama = $data->nama;
        if ($data->Produk) {
            // mapping label dan value
            $object->produk = $data->Produk->map(function ($item) {
                return [
                    'produk' => [
                        'id' => $item->id,
                        'label' => $item->nama,
                    ]
                ];
            });
        }

        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $object,
        ], 200);
    }

    public function kode_lab_data()
    {
        $data = KodeLab::all();
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $data,
        ], 200);
    }

    public function kode_lab_store(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            $data = KodeLab::create([
                'kode' => $request->kode,
                'nama' => $request->nama
            ]);

            if (count($request->produk) > 0) {
                for ($j = 0; $j < count($request->produk); $j++) {
                    Produk::where('id', $request->produk[$j]['produk']['id'])
                        ->update([
                            'kode_lab_id' => $data->id,
                        ]);
                }
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 404,
                'message' => 'Cek Kembali Form',
            ], 500);
        }
    }

    public function ruang_lab_store(Request $request)
    {
        DB::beginTransaction();
        try {
            $ruang = RuangKalibrasi::create([
                'nama' => $request->nama
            ]);

            for ($j = 0; $j < count($request->metode); $j++) {
                DetailMetodeLab::create([
                    'metode_lab_id' => $request->metode[$j]['metode']['id'],
                    'ruang' => $ruang->id
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 404,
                'message' => 'Cek Kembali Form',
            ], 500);
        }
    }

    public function ruang_lab_data()
    {
        $data = RuangKalibrasi::all();
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $data,
        ], 200);
    }

    public function ruang_lab_edit($id)
    {
        $data = RuangKalibrasi::find($id);
        if (count($data->DetailMetodeLab) > 0) {
            foreach ($data->DetailMetodeLab as $d) {
                $detail[] = [
                    'metode' => [
                        'id' => $d->metode_lab_id,
                        'label' => $d->MetodeLab->metode
                    ]
                ];
            }
        } else {
            $detail = array();
        }

        $object = new stdClass();
        $object->id = $data->id;
        $object->nama = $data->nama;
        $object->metode = $detail;


        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $object,
        ], 200);
    }



    public function ruang_lab_update(Request $request, $id)
    {
        //dd($request->all());
        $rules = [
            'nama' => 'required | unique:ruang_kalibrasi,nama,' . $id,
        ];


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'message' => 'Cek Kembali Form',
            ], 500);
        } else {
            DB::beginTransaction();
            try {
                $data = RuangKalibrasi::find($id);
                $data->nama = $request->nama;
                $data->save();
                $getDB = $data->DetailMetodeLab->pluck('metode_lab_id');

                foreach ($request->metode as $r) {
                    $getReq[] = $r['metode']['id'];
                }

                $getDBs = collect($getDB)->toArray();
                $difference_a = array_diff($getDBs, $getReq);
                $difference_b = array_diff($getReq, $getDBs);

                if (!empty($difference_a) || !empty($difference_b)) {
                    $records = DetailMetodeLab::doesntHave('UjiLabDetail')->where('ruang', $id)->get();

                    foreach ($records as $record) {
                        $record->delete();
                    }
                    foreach ($request->metode as $q) {
                        DetailMetodeLab::create([
                            'ruang' => $id,
                            'metode_lab_id' => $q['metode']['id']
                        ]);
                    }
                }

                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil',
                ], 200);
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollBack();
                return response()->json([
                    'status' => 404,
                    'message' => 'Transaksi Update Gagal' . $th,
                ], 500);
            }
        }
    }

    public function runag_by_metode($id)
    {
        $data = DetailMetodeLab::where('metode_lab_id', $id)->get();
        if ($data->isEmpty()) {
            $obj = array();
        } else {
            foreach ($data as $d) {
                $obj[] = array(
                    'id' => $d->id,
                    'label' => $d->RuangKalibrasi->nama
                );
            }
        }
        return response()->json($obj);
    }
    public function metode_by_ruang($id)
    {
        $data = DetailMetodeLab::where('ruang', $id)->get();


        if ($data->isEmpty()) {
            $obj = array();
        } else {
            foreach ($data as $d) {
                $obj[] = array(
                    'id' => $d->id,
                    'label' => $d->MetodeLab->metode
                );
            }
        }


        return response()->json($obj);
    }

    public function ruang_and_metode()
    {
        $detailMetodeLab = DetailMetodeLab::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'ruang_id' => $item->ruang,
                'ruang_label' => $item->RuangKalibrasi->nama,
                'metode_id' => $item->metode_lab_id,
                'metode_label' => $item->MetodeLab->metode
            ];
        });

        return response()->json($detailMetodeLab);

        // {
        //     ruang_id:
        //     ruang_label
        //     metode_id
        //     metode_label
        // }
    }
    public function dok_lab_data()
    {
        $data = MetodeLab::all();
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $data,
        ], 200);
    }

    public function dok_lab_store(Request $request)
    {
        $rules = [
            'metode' => 'required | unique:metode_lab',
            'no_dokumen' => 'required | unique:metode_lab',
            'ruang*' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'message' => 'Cek Kembali Form',
            ], 200);
        } else {
            $metode = MetodeLab::create([
                'metode' => $request->metode,
                'no_dokumen' => $request->no_dokumen
            ]);

            for ($j = 0; $j < count($request->ruang); $j++) {
                DetailMetodeLab::create([
                    'ruang' => $request->ruang[$j]['ruang']['id'],
                    'metode_lab_id' => $metode->id
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
            ], 200);
        }
    }

    public function produk_lab_update(Request $request, $id)
    {
        $rules = [
            'kode_lab_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        $data = Produk::find($id);
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'message' => 'Cek Kembali Form',
            ], 500);
        } else {
            DB::beginTransaction();
            try {
                //code...
                $data->kode_lab_id = $request->kode_lab_id;
                $data->save();

                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil',
                ], 200);
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollBack();
                return response()->json([
                    'status' => 404,
                    'message' => 'Transaksi Update Gagal',
                ], 500);
            }
        }
        $object = new stdClass();
        $object->id = $data->kode_lab_id;
        $object->label = $data->KodeLab->nama;


        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $object,
        ], 200);
    }

    public function produk_lab_detail_order($id)
    {
        $data = DB::table('uji_lab_detail')
            ->select('pesanan.no_po', 'uji_lab.nama as nama_pemilik', 'uji_lab.no_order', 'uji_lab.id as lab_id')
            ->leftJoin('uji_lab', 'uji_lab.id', '=', 'uji_lab_detail.uji_lab_id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'uji_lab_detail.detail_pesanan_produk_id')
            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->leftJoin('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
            ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
            ->where('kode_lab_id', $id)
            ->groupBy('pesanan.id')
            ->get();

        if ($data->isEmpty()) {
            $setData = array();
        } else {
            foreach ($data as $d) {
                $setData[] = array(
                    'id' => $d->lab_id,
                    'no_order' => 'LAB-' . sprintf("%04d",  $d->no_order),
                    'po' => $d->no_po,
                    'nama_pemilik' => $d->nama_pemilik
                );
            }
        }

        return response()->json(['data' => $setData]);
    }

    public function produk_lab_edit($id)
    {
        $data = Produk::find($id);

        $object = new stdClass();
        if ($data->kode_lab_id) {
            $object->id = $data->kode_lab_id;
            $object->label = $data->KodeLab->nama;
        } else {
            $object->id = NULL;
            $object->label = NULL;
        }

        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $object,
        ], 200);
    }

    public function dok_lab_edit($id)
    {
        $data = MetodeLab::find($id);

        if (count($data->DetailMetodeLab) > 0) {
            foreach ($data->DetailMetodeLab as $d) {
                $detail[] = [
                    'ruang' => [
                        'id' => $d->ruang,
                        'label' => $d->RuangKalibrasi->nama
                    ]
                ];
            }
        } else {
            $detail = array();
        }

        $object = new stdClass();
        $object->id = $data->id;
        $object->metode = $data->metode;
        $object->no_dokumen = $data->no_dokumen;
        $object->ruang = $detail;


        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $object,
        ], 200);
    }

    public function dok_lab_update(Request $request, $id)
    {
        //    dd($request->all());
        $rules = [
            'metode' => 'required | unique:metode_lab,metode,' . $id,
            'no_dokumen' => 'required | unique:metode_lab,no_dokumen,' . $id
        ];


        $validator = Validator::make($request->all(), $rules);
        //dd($request->all());
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'message' => 'Cek Kembali Form',
            ], 500);
        } else {
            DB::beginTransaction();
            try {
                $data = MetodeLab::find($id);
                $data->metode = $request->metode;
                $data->save();
                $getDB = $data->DetailMetodeLab->pluck('ruang');

                foreach ($request->ruang as $r) {
                    $getReq[] = $r['ruang']['id'];
                }
                $getDBs = collect($getDB)->toArray();
                $difference_a = array_diff($getDBs, $getReq);
                $difference_b = array_diff($getReq, $getDBs);


                if (!empty($difference_a) || !empty($difference_b)) {
                    $records = DetailMetodeLab::doesntHave('UjiLabDetail')->where('metode_lab_id', $id)->get();
                    foreach ($records as $record) {
                        $record->delete();
                    }
                    foreach ($request->ruang as $q) {
                        DetailMetodeLab::create([
                            'metode_lab_id' => $id,
                            'ruang' => $q['ruang']['id']
                        ]);
                    }
                }


                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil',
                ], 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json([
                    'status' => 404,
                    'message' => 'Transaksi Update Gagal' . $th,
                ], 500);
            }
        }
    }

    public function kode_milik_update(Request $request, $id)
    {
        //dd($request->all());
        $rules = [
            'kode' => 'required | unique:jenis_pemilik,kode,' . $id,
            'nama' => 'required | unique:jenis_pemilik,nama,' . $id
        ];


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'message' => 'Cek Kembali Form',
            ], 200);
        } else {
            $data = JenisPemilik::find($id);
            $data->kode = $request->kode;
            $data->nama = $request->nama;
            $data->save();

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
            ], 200);
        }
    }
    public function kode_milik_edit($id)
    {
        $data = JenisPemilik::find($id);
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $data,
        ], 200);
    }

    public function kode_milik_data()
    {
        $data = JenisPemilik::all();
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $data,
        ], 200);
    }
    public function kode_milik_store(Request $request)
    {
        if ($request->kode == "" || $request->nama == "") {
            return response()->json([
                'status' => 404,
                'message' => 'Cek Kembali Form',
            ], 200);
        } else {
            $rules = [
                'kode' => 'required | unique:jenis_pemilik',
                'nama' => 'required | unique:jenis_pemilik',
            ];


            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Cek Kembali Form',
                ], 200);
            } else {
                $data = JenisPemilik::create([
                    'kode' => $request->kode,
                    'nama' => $request->nama
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil',
                ], 200);
            }
        }
    }

    public function sertifikat_data()
    {
        $data = UjiLabDetail::whereNotNull('no_sertifikat')->get();
        foreach ($data as $d) {
            $object[] = array(
                'id' => $d->id,
                'kode' => $d->no_sertifikat,
                'noseri' => $d->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri,
                'tgl_sekarang' => Carbon::now()->format('Y-m-d'),
                'nama' => $d->UjiLab->jenis_pemilik_id != NULL ? $d->UjiLab->JenisPemilik->nama : '-',
                'nama_pemilik_sertifikat' => $d->UjiLab->nama,
                'order' => 'LAB-' . sprintf("%04d",  $d->UjiLab->no_order),
                'tgl_kalibrasi' => $d->tgl_kalibrasi,
                'tgl_kalibrasi_exp' => Carbon::parse($d->tgl_kalibrasi)->addDays(365)->format('Y-m-d'),
                'teknisi' => $d->Karyawan->nama,
                'hasil' =>  $d->status
            );
        }

        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $object,
        ], 200);
    }

    public function cetak_sertifikat($jenis, $id, $ttd, $hal)
    {

        try {
            if ($jenis == 'seri') {
                $data = UjiLabDetail::with(['DetailPesananProduk.GudangBarangJadi.Produk.KodeLab', 'NoseriDetailPesanan.NoseriTGbj.NoseriBarangJadi', 'DetailMetodeLab.MetodeLab', 'Karyawan'])->find($id);
                $object = new stdClass();
                $object->tgl_sekarang = Carbon::now()->format('Y-m-d');
                $object->kode = $data->no_sertifikat;
                $object->order = 'LAB-' . sprintf("%04d",  $data->UjiLab->no_order);
                $object->alat = array(
                    'nama' => $data->DetailPesananProduk->GudangBarangJadi->Produk->KodeLab->nama,
                    'merk' => $data->DetailPesananProduk->GudangBarangJadi->Produk->merk,
                    'model' => $data->DetailPesananProduk->GudangBarangJadi->Produk->nama,
                    'noseri' => $data->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri,
                    'tgl_penerimaan' => $data->tgl_masuk
                );

                $object->pelanggan = array('nama' => $data->UjiLab->nama, 'alamat' => $data->UjiLab->alamat);
                $object->detail = array(
                    'tempat' => 'Laboratorium Kalibrasi',
                    'ruangan' => 'Ruang ' . $data->DetailMetodeLab->RuangKalibrasi->nama,
                    'tgl_kalibrasi' => $data->tgl_kalibrasi,
                    'tgl_kalibrasi_exp' => Carbon::parse($data->tgl_kalibrasi)->addDays(365)->format('Y-m-d'),
                    'teknisi' => $data->Karyawan->nama,
                    'metode' =>  $data->DetailMetodeLab->MetodeLab->no_dokumen,
                    'hasil' => $data->status == 'ok' ? 'ALAT BAIK DAN LAIK DIGUNAKAN' : 'ALAT TIDAK LAIK PAKAI'
                );
            } else {

                if ($jenis == 'produk') {
                    $data = UjiLabDetail::with(['DetailPesananProduk.GudangBarangJadi.Produk.KodeLab', 'NoseriDetailPesanan.NoseriTGbj.NoseriBarangJadi', 'DetailMetodeLab.MetodeLab', 'Karyawan'])->where('detail_pesanan_produk_id', $id)->whereNotNull('no_sertifikat')->get();
                }
                if ($jenis == 'po') {
                    $data = UjiLabDetail::with(['DetailPesananProduk.GudangBarangJadi.Produk.KodeLab', 'NoseriDetailPesanan.NoseriTGbj.NoseriBarangJadi', 'DetailMetodeLab.MetodeLab', 'Karyawan'])->where('uji_lab_id', $id)->whereNotNull('no_sertifikat')->get();
                }

                foreach ($data as $d) {
                    $object[] = (object)[
                        'tgl_sekarang' => Carbon::now()->format('Y-m-d'),
                        'kode' => $d->no_sertifikat,
                        'order' => 'LAB-' . sprintf("%04d",  $d->UjiLab->no_order),
                        'alat' => (object)[
                            'nama' => $d->DetailPesananProduk->GudangBarangJadi->Produk->KodeLab->nama,
                            'merk' => $d->DetailPesananProduk->GudangBarangJadi->Produk->merk,
                            'model' => $d->DetailPesananProduk->GudangBarangJadi->Produk->nama,
                            'noseri' => $d->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri,
                            'tgl_penerimaan' => $d->tgl_masuk
                        ],
                        'pelanggan' => (object)['nama' => $d->UjiLab->nama, 'alamat' => $d->UjiLab->alamat],
                        'detail' => (object)[
                            'tempat' => 'Laboratorium Kalibrasi',
                            'ruangan' => 'Ruang ' . $d->DetailMetodeLab->RuangKalibrasi->nama,
                            'tgl_kalibrasi' => $d->tgl_kalibrasi,
                            'tgl_kalibrasi_exp' => Carbon::parse($d->tgl_kalibrasi)->addDays(365)->format('Y-m-d'),
                            'teknisi' => $d->Karyawan->nama,
                            'metode' =>  $d->DetailMetodeLab->MetodeLab->no_dokumen,
                            'hasil' => $d->status == 'ok' ? 'ALAT BAIK DAN LAIK DIGUNAKAN' : 'ALAT TIDAK LAIK PAKAI'
                        ]
                    ];
                }
            }

            $pdf = PDF::loadView(
                'page.lab.sertifikat.ttd',
                ['object' => $object, 'ttd' => $ttd, 'hal' => $hal]
            )->setOptions(['defaultFont' => 'sans-serif'], ['isPhpEnabled' => true])->setPaper('A4', 'potrait');
            return $pdf->stream('');
        } catch (\Throwable $th) {
            echo 'Error: ' . $th->getMessage();
        }
    }

    public function lab_store_uji(Request $request)
    {
        $obj =  json_decode(json_encode($request->all()), FALSE);
        DB::beginTransaction();
        try {
            //code...
            $obj =  json_decode(json_encode($request->all()), FALSE);
            $ujilab =  UjiLab::find($obj->uji_lab_id);
            //Dummy
            $ujilab->jenis_pemilik_id = $request->jenis_pemilik['value'];
            // $ujilab->nama = 'Nama Customer';
            $ujilab->alamat = $obj->alamat;
            $ujilab->save();

            //Dummy
            $pemilik = JenisPemilik::find($request->jenis_pemilik['value']);
            $pemilik_id = sprintf("%02d", $pemilik->kode);
            $tahun = Carbon::createFromFormat('Y-m-d', $obj->tgl_kalibrasi)->format('Y');
            $month = date('n', strtotime($obj->tgl_kalibrasi));

            foreach ($obj->produk as $dp) {
                $kode = DB::select('
                select kl.kode as kode from detail_pesanan_produk dpp join gdg_barang_jadi gbj on dpp.gudang_barang_jadi_id = gbj.id
                join produk p on p.id = gbj.produk_id
                join kode_lab kl on kl.id = p.kode_lab_id
                where dpp.id = ?', [$dp->id]);
                $metode_id = DetailMetodeLab::where(['metode_lab_id' => $dp->metode_id->id, 'ruang' => $dp->ruang_id->id])->first();
                // dd($metode_id->id);
                for ($j = 0; $j < count($dp->noseri); $j++) {
                    $detail = UjiLabDetail::find($dp->noseri[$j]->id);
                    $no = sprintf("%04d", $detail->no);
                    UjiLabDetail::where('id', $dp->noseri[$j]->id)
                        ->update([
                            'no_sertifikat' => $kode[0]->kode . '/' . $pemilik_id . '/' .  $month . '-' . $tahun . '/' . $no,
                            'tgl_kalibrasi' => $obj->tgl_kalibrasi,
                            'pemeriksa_id' => $obj->pemeriksa_id,
                            'metode_id' => $metode_id->id,
                            'status' => $obj->hasil,
                        ]);
                    // if($dp['seri'][$j]['hasil'] == 'ok'){
                    //     $detail= UjiLabDetail::find($dp['seri'][$j]['id']);
                    //     $no = sprintf("%04d", $detail->no);
                    //     UjiLabDetail::where('id', $dp['seri'][$j]['id'])
                    //     ->update([
                    //         'no_sertifikat' => $kode[0]->kode.'/'.$pemilik_id.'/'.  $month.'-'. $tahun.'/'.$no ,
                    //         'tgl_kalibrasi' => $request->tgl_kalibrasi,
                    //         'pemeriksa_id' => $request->pemeriksa_id,
                    //         'metode_id' => $dp['metode_id'],
                    //     ]);
                    // }else{
                    //     UjiLabDetail::where('id', $dp['seri'][$j]['id'])
                    //     ->update([
                    //         'no_sertifikat' => NULL,
                    //         'tgl_kalibrasi' => NULL,
                    //         'pemeriksa_id' => NULL,
                    //         'metode_id' => NULL,
                    //         'status' => 'nok',
                    //     ]);
                    // }
                }
            }
            SystemLog::create([
                'tipe' => 'Lab',
                'subjek' => 'Kalibrasi Produk',
                'response' => json_encode($obj)
            ]);
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 404,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function kalibrasi_detail_seri($id)
    {
        $dpp = DetailPesananProduk::find($id);

        $get_dpp = DetailPesananProduk::whereHas("DetailPesanan", function ($q) use ($dpp) {
            $q->where('pesanan_id', $dpp->DetailPesanan->pesanan_id);
        })->where('detail_pesanan_produk.gudang_barang_jadi_id', $dpp->gudang_barang_jadi_id)->pluck('detail_pesanan_produk.id')->toArray();

        $detail = UjiLabDetail::with('NoseriDetailPesanan.NoseriTGbj.NoseriBarangJadi')
            ->addSelect([
                'belum' => function ($q) {
                    $q->selectRaw('coalesce(count(uji.id),0)')
                        ->from('uji_lab_detail as uji')
                        ->where('uji.status', 'belum')
                        ->where('uji.is_ready', 1)
                        ->whereColumn('uji.id', 'uji_lab_detail.id');
                },
            ])
            ->havingRaw('belum !=0')
            ->whereIN('detail_pesanan_produk_id', $get_dpp)
            ->get();
        if ($detail->isEmpty()) {
            $seri = array();
        } else {
            foreach ($detail as $d) {
                $seri[] = array(
                    'id' => $d->id,
                    'no_seri' => $d->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri,
                    'tgl_masuk' => $d->tgl_masuk,
                    'status' => $d->status == 'nok' ? 'not_ok' : $d->status,
                );
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $seri,
        ], 200);
    }

    public function kalibrasi_detail($id)
    {
        $ujilab_head = UjiLab::find($id);
        $pesanan = Pesanan::find($ujilab_head->pesanan_id);
        $ujilab = UjiLabDetail::select('uji_lab_detail.id as lab_id', 'detail_pesanan_produk.gudang_barang_jadi_id', 'produk.nama as tipe', 'kode_lab.nama as nama', 'gdg_barang_jadi.nama as variasi', 'detail_pesanan_produk.id as dpp_id')
            ->selectRaw(
                "(CASE
        WHEN uji_lab_detail.status = 'ok' AND uji_lab_detail.is_ready = 1  THEN 1
        ELSE 0
        END) AS ok",
            )
            ->selectRaw(
                "(CASE
        WHEN uji_lab_detail.status = 'nok'  AND uji_lab_detail.is_ready = 1 THEN 1
        ELSE 0
        END) AS nok",
            )
            // ->selectRaw(
            //     "coalesce(count(uji_lab_detail.id),0) AS jumlah",
            // )
            // ->selectRaw(
            //     "coalesce(SUM(CASE WHEN uji_lab_detail.status != 'belum' THEN 1 ELSE 0 END),0) AS uji",
            // )
            ->addSelect([
                'belum' => function ($q) {
                    $q->selectRaw('coalesce(count(uji.id),0)')
                        ->from('uji_lab_detail as uji')
                        ->where('uji.status', 'belum')
                        ->where('uji.is_ready', 1)
                        ->whereColumn('uji.id', 'uji_lab_detail.id');
                },
                'uji' => function ($q) {
                    $q->selectRaw('coalesce(count(uji.id),0)')
                        ->from('uji_lab_detail as uji')
                        ->where('uji.status', 'belum')
                        ->where('uji.is_ready', 1)
                        ->whereColumn('uji.id', 'uji_lab_detail.id');
                },
            ])
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'uji_lab_detail.detail_pesanan_produk_id')
            ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
            ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
            ->leftJoin('kode_lab', 'kode_lab.id', '=', 'produk.kode_lab_id')
            ->where('uji_lab_id', $id)
            ->where('is_ready', 1)
            // ->havingRaw('belum !=0')
            ->get();

        // return response()->json($ujilab);

        if ($ujilab->isEmpty()) {
            $data = array();
        } else {
            $produks = [];
            foreach ($ujilab as $d) {
                $gudang_barang_jadi_id = $d['gudang_barang_jadi_id'];

                if (!isset($produks[$gudang_barang_jadi_id])) {
                    $produks[$gudang_barang_jadi_id] = array(
                        'id' => $d->dpp_id,
                        "nama" => $d->nama,
                        "tipe" => $d->tipe . ' ' . $d->variasi,
                        "jumlah" => 0,
                        "jumlah_ok" => 0,
                        "jumlah_nok" => 0,
                    );
                }
                $produks[$gudang_barang_jadi_id]["jumlah"] += 1;
                $produks[$gudang_barang_jadi_id]["jumlah_nok"] += $d->nok;
                $produks[$gudang_barang_jadi_id]["jumlah_ok"] += $d->ok;
            }
            $produks = array_values($produks);

            if ($pesanan->Spa) {
                $c =  $pesanan->Spa->Customer->nama;
            }
            if ($pesanan->Ekatalog) {
                $c =  $pesanan->Ekatalog->Customer->nama;
            }
            if ($pesanan->Spb) {
                $c =  $pesanan->Spb->Customer->nama;
            }

            if ($ujilab_head->jenis_pemilik_id != NULL) {
                $jnis = (object)[
                    'value' => $ujilab_head->JenisPemilik->id,
                    'label' => $ujilab_head->JenisPemilik->nama
                ];
            } else {
                $jnis = array();
            }
            $data = array(
                'header' => array(
                    'id' => $ujilab_head->id,
                    'no_order' => 'LAB-' . sprintf("%04d",  $ujilab_head->no_order),
                    'so' => $pesanan->so,
                    'po' => $pesanan->no_po,
                    'tgl_po' => $pesanan->tgl_po,
                    'customer' => $c,
                    'jenis_pemilik' => $jnis,
                    'nama' => $ujilab_head->nama,
                    'alamat' => $ujilab_head->alamat,
                    'customer' => $ujilab_head->nama,
                    'status' =>  intval(($ujilab_head->GetUji()) / $ujilab_head->GetJumlah() * 100),
                ),
                'produk' => $produks
            );
        }

        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $data,
        ], 200);
    }

    public function kalibrasi_data()
    {
        $ujilab = UjiLab::addSelect([
            'ok' => function ($q) {
                $q->selectRaw('SUM(CASE WHEN status = "ok" THEN 1 ELSE 0 END) ')
                    ->from('uji_lab_detail')
                    ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
            },
            'nok' => function ($q) {
                $q->selectRaw('SUM(CASE WHEN status = "nok" THEN 1 ELSE 0 END)')
                    ->from('uji_lab_detail')
                    ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
            },
            'uji' => function ($q) {
                $q->selectRaw('coalesce(SUM(CASE WHEN status != "belum" THEN 1 ELSE 0 END),0)')
                    ->from('uji_lab_detail')
                    ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
            },
            'belum' => function ($q) {
                $q->selectRaw(' coalesce(SUM(CASE WHEN status = "belum" THEN 1 ELSE 0 END),0)')
                    ->from('uji_lab_detail')
                    ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
            },
            'jumlah' => function ($q) {
                $q->selectRaw('coalesce(count(uji_lab_detail.id),0)')
                    ->from('uji_lab_detail')
                    ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
            },
        ])
            ->havingRaw('belum != 0')
            ->with(['Pesanan.Spa.Customer', 'Pesanan.Spb.Customer', 'Pesanan.Ekatalog.Customer'])
            ->get();


        if ($ujilab->isEmpty()) {
            $data = array();
        } else {
            foreach ($ujilab as $u) {
                if ($u->Pesanan->Spa) {
                    $c =  $u->Pesanan->Spa->Customer->nama;
                }
                if ($u->Pesanan->Ekatalog) {
                    $c =  $u->Pesanan->Ekatalog->Customer->nama;
                }
                if ($u->Pesanan->Spb) {
                    $c =  $u->Pesanan->Spb->Customer->nama;
                }
                $data[] = array(
                    'id' => $u->id,
                    'no_order' => 'LAB-' . sprintf("%04d",  $u->no_order),
                    'pemilik' => $u->jenis_pemilik_id != NULL ? $u->JenisPemilik->nama : '-',
                    'pemilik_sertif' => $u->nama,
                    'customer' => $c,
                    'status' => intval(($u->uji) / $u->jumlah * 100)
                );
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $data,
        ], 200);
    }
    public function transfer_detail_seri($id)
    {

        $dpp = DetailPesananProduk::find($id);

        $get_dpp = DetailPesananProduk::whereHas("DetailPesanan", function ($q) use ($dpp) {
            $q->where('pesanan_id', $dpp->DetailPesanan->pesanan_id);
        })->where('detail_pesanan_produk.gudang_barang_jadi_id', $dpp->gudang_barang_jadi_id)->pluck('detail_pesanan_produk.id')->toArray();

        $detail = UjiLabDetail::with('NoseriDetailPesanan.NoseriTGbj.NoseriBarangJadi')
            ->addSelect([
                'ok' => function ($q) {
                    $q->selectRaw('coalesce(count(uji.id),0)')
                        ->from('uji_lab_detail as uji')
                        ->where('uji.status', 'ok')
                        ->where('uji.is_ready', 1)
                        ->whereColumn('uji.id', 'uji_lab_detail.id');
                },
                'nok' => function ($q) {
                    $q->selectRaw('coalesce(count(uji.id),0)')
                        ->from('uji_lab_detail as uji')
                        ->where('uji.status', 'nok')
                        ->where('uji.is_ready', 1)
                        ->whereColumn('uji.id', 'uji_lab_detail.id');
                },
            ])
            ->havingRaw('ok > 0 OR nok > 0')
            ->whereIN('detail_pesanan_produk_id', $get_dpp)
            ->get();
        if ($detail->isEmpty()) {
            $seri = array();
        } else {
            foreach ($detail as $d) {
                $seri[] = array(
                    'id' => $d->id,
                    'no_seri' => $d->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri,
                    'tgl_masuk' => $d->tgl_masuk,
                    'status' => $d->status == 'ok' ? 'ok' : 'not_ok',
                );
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $seri,
        ], 200);
    }
    public function transfer_detail($id)
    {
        $ujilab = UjiLabDetail::select('uji_lab_detail.id as lab_id', 'detail_pesanan_produk.gudang_barang_jadi_id', 'produk.nama as tipe', 'kode_lab.nama as nama', 'gdg_barang_jadi.nama as variasi', 'detail_pesanan_produk.id as dpp_id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'uji_lab_detail.detail_pesanan_produk_id')
            ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
            ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
            ->leftJoin('kode_lab', 'kode_lab.id', '=', 'produk.kode_lab_id')
            ->where('uji_lab_id', $id)
            ->where('is_ready', 1)
            ->whereRaw("uji_lab_detail.status != 'belum'")
            ->get();

        if ($ujilab->isEmpty()) {
            $produks = array();
        } else {
            $produks = [];
            foreach ($ujilab as $d) {
                $gudang_barang_jadi_id = $d['gudang_barang_jadi_id'];
                if (!isset($produks[$gudang_barang_jadi_id])) {
                    $produks[$gudang_barang_jadi_id] = array(
                        'id' => $d->dpp_id,
                        "nama" => $d->nama,
                        "tipe" => $d->tipe . $d->variasi,
                        "jumlah" => 0
                    );
                }
                $produks[$gudang_barang_jadi_id]["jumlah"] += 1;
            }
            $produks = array_values($produks);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $produks,
        ], 200);
    }

    public function transfer_store(Request $request)
    {
        //dd($request->all());
        DB::beginTransaction();
        $obj =  json_decode(json_encode($request->all()), FALSE);
        try {
            //code...
            foreach ($obj->produk as $r) {
                for ($j = 0; $j < count($r->noseri); $j++) {
                    $ujilab = UjiLabDetail::find($r->noseri[$j]->id);
                    UjiLabDetail::where('id', $r->noseri[$j]->id)
                        ->update([
                            'is_ready' => 0,
                        ]);

                    NoseriDetailPesanan::where('id', $ujilab->noseri_id)
                        ->update([
                            'is_lab' => 0,
                        ]);
                }
            }

            $getPesananId = UjiLab::find($obj->header->id)->pesanan_id;
            $pesanan = Pesanan::find($getPesananId);

            $header = (object)[
                'id' => $obj->header->id,
                'so' => $pesanan->so,
                'po' => $pesanan->no_po,
                'no_order' => $obj->header->no_order,
                'pemilik' => $obj->header->pemilik,
                'pemilik_sertif' => $obj->header->pemilik_sertif,
                'customer' => $obj->header->customer,
                'status' => $obj->header->status
            ];
            $res = array(
                'header' => $header,
                'produk' => $obj->produk
            );

            RiwayatTf::create([
                'dari' => 22,
                'ke' => 23,
                'jenis' => 'transfer',
                'isi' => json_encode($res)
            ]);
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'ok',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 404,
                'message' => $th,
            ], 500);
        }
    }
    public function transfer_riwayat(Request $request)
    {
        $years = $request->years;
        $data = RiwayatTf::where('dari', 22)->whereYear('created_at', $years)->get();
        $setData = array();
        foreach ($data as $d) {
            $e = json_decode($d->isi);
            $setData[] = array(
                'id' => $d->id,
                'so' => $e->header->so,
                'no_po' => $e->header->po,
                'no_order' => $e->header->no_order,
                'pemilik' => $e->header->pemilik,
                'pemilik_sertif' => $e->header->pemilik_sertif,
                'tgl_transfer' => $d->created_at->format('Y-m-d'),
                'customer' => $e->header->customer,
                'detail' => $e->produk
            );
        }
        return response()->json($setData);
    }

    public function transfer_data()
    {
        $ujilab = UjiLab::addSelect([
            // 'ok' => function ($q) {
            //     $q->selectRaw('SUM(CASE WHEN status = "ok" THEN 1 ELSE 0 END) ')
            //         ->from('uji_lab_detail')
            //         ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
            // },
            // 'nok' => function ($q) {
            //     $q->selectRaw('SUM(CASE WHEN status = "nok" THEN 1 ELSE 0 END)')
            //         ->from('uji_lab_detail')
            //         ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
            // },
            'uji' => function ($q) {
                $q->selectRaw('coalesce(SUM(CASE WHEN status != "belum" THEN 1 ELSE 0 END),0)')
                    ->from('uji_lab_detail')
                    ->where('uji_lab_detail.is_ready', 1)
                    ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
            },
            'belumuji' => function ($q) {
                $q->selectRaw('coalesce(SUM(CASE WHEN status = "belum" THEN 1 ELSE 0 END),0)')
                    ->from('uji_lab_detail')
                    ->where('uji_lab_detail.is_ready', 1)
                    ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
            },
            'tf' => function ($q) {
                $q->selectRaw('coalesce(count(uji_lab_detail.id),0)')
                    ->from('uji_lab_detail')
                    ->where('uji_lab_detail.is_ready', 0)
                    ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
            },
            // 'belum' => function ($q) {
            //     $q->selectRaw(' coalesce(SUM(CASE WHEN status = "belum" THEN 1 ELSE 0 END),0)')
            //         ->from('uji_lab_detail')
            //         ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
            // },
            'jumlah' => function ($q) {
                $q->selectRaw('coalesce(count(uji_lab_detail.id),0)')
                    ->from('uji_lab_detail')
                    ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
            },
        ])
            ->with(['Pesanan.Spa.Customer', 'Pesanan.Spb.Customer', 'Pesanan.Ekatalog.Customer'])
            ->havingRaw('uji > 0')
            // ->havingRaw('belum > 0 || belum > uji')
            ->get();

        if ($ujilab->isEmpty()) {
            $data = array();
        } else {
            foreach ($ujilab as $u) {
                if ($u->Pesanan->Spa) {
                    $c =  $u->Pesanan->Spa->Customer->nama;
                }
                if ($u->Pesanan->Ekatalog) {
                    $c =  $u->Pesanan->Ekatalog->Customer->nama;
                }
                if ($u->Pesanan->Spb) {
                    $c =  $u->Pesanan->Spb->Customer->nama;
                }
                $data[] = array(
                    'id' => $u->id,
                    'no_order' => 'LAB-' . sprintf("%04d",  $u->no_order),
                    'pemilik' => $u->jenis_pemilik_id != NULL ? $u->JenisPemilik->nama : '-',
                    'pemilik_sertif' => $u->nama,
                    'customer' => $c,
                    'status' => intval($u->tf / $u->jumlah * 100)
                );
            }
        }


        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $data,
        ], 200);
    }
    public function lab_data($filter)
    {
        if ($filter == 'semua') {
            $ujilab = UjiLab::addSelect([
                // 'ok' => function ($q) {
                //     $q->selectRaw('SUM(CASE WHEN status = "ok" THEN 1 ELSE 0 END) ')
                //         ->from('uji_lab_detail')
                //         ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
                // },
                // 'nok' => function ($q) {
                //     $q->selectRaw('SUM(CASE WHEN status = "nok" THEN 1 ELSE 0 END)')
                //         ->from('uji_lab_detail')
                //         ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
                // },
                'uji' => function ($q) {
                    $q->selectRaw('coalesce(SUM(CASE WHEN status != "belum" THEN 1 ELSE 0 END),0)')
                        ->from('uji_lab_detail')
                        ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
                },
                // 'belum' => function ($q) {
                //     $q->selectRaw(' coalesce(SUM(CASE WHEN status = "belum" THEN 1 ELSE 0 END),0)')
                //         ->from('uji_lab_detail')
                //         ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
                // },
                'jumlah' => function ($q) {
                    $q->selectRaw('coalesce(count(uji_lab_detail.id),0)')
                        ->from('uji_lab_detail')
                        ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
                },
            ])
                ->with(['Pesanan.Spa.Customer', 'Pesanan.Spb.Customer', 'Pesanan.Ekatalog.Customer'])
                ->havingRaw('jumlah = uji ')
                ->get();
        } elseif ($filter == 'sebagian') {
            $ujilab = UjiLab::addSelect([
                // 'ok' => function ($q) {
                //     $q->selectRaw('SUM(CASE WHEN status = "ok" THEN 1 ELSE 0 END) ')
                //         ->from('uji_lab_detail')
                //         ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
                // },
                // 'nok' => function ($q) {
                //     $q->selectRaw('SUM(CASE WHEN status = "nok" THEN 1 ELSE 0 END)')
                //         ->from('uji_lab_detail')
                //         ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
                // },
                'uji' => function ($q) {
                    $q->selectRaw('coalesce(SUM(CASE WHEN status != "belum" THEN 1 ELSE 0 END),0)')
                        ->from('uji_lab_detail')
                        ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
                },
                'belum' => function ($q) {
                    $q->selectRaw(' coalesce(SUM(CASE WHEN status = "belum" THEN 1 ELSE 0 END),0)')
                        ->from('uji_lab_detail')
                        ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
                },
                'jumlah' => function ($q) {
                    $q->selectRaw('coalesce(count(uji_lab_detail.id),0)')
                        ->from('uji_lab_detail')
                        ->whereColumn('uji_lab_detail.uji_lab_id', 'uji_lab.id');
                },
            ])
                ->with(['Pesanan.Spa.Customer', 'Pesanan.Spb.Customer', 'Pesanan.Ekatalog.Customer'])
                ->havingRaw('belum > 0 AND uji != 0  ')
                ->get();
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Data Kosong',
            ], 200);
        }



        if ($ujilab->isEmpty()) {
            $data = array();
        } else {
            foreach ($ujilab as $u) {
                if ($u->Pesanan->Spa) {
                    $c =  $u->Pesanan->Spa->Customer->nama;
                }
                if ($u->Pesanan->Ekatalog) {
                    $c =  $u->Pesanan->Ekatalog->Customer->nama;
                }
                if ($u->Pesanan->Spb) {
                    $c =  $u->Pesanan->Spb->Customer->nama;
                }
                $data[] = array(
                    'id' => $u->id,
                    'no_order' => 'LAB-' . sprintf("%04d",  $u->no_order),
                    'pemilik' =>  $u->jenis_pemilik_id != NULL ? $u->JenisPemilik->nama : '-',
                    'pemilik_sertif' =>  $u->nama,
                    'customer' => $c,
                    'status' => intval(($u->uji) / $u->jumlah * 100)
                );
            }
        }


        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $data,
        ], 200);
    }

    public function lab_data_detail_seri($id)
    {
        $dpp = DetailPesananProduk::find($id);

        $get_dpp = DetailPesananProduk::whereHas("DetailPesanan", function ($q) use ($dpp) {
            $q->where('pesanan_id', $dpp->DetailPesanan->pesanan_id);
        })->where('detail_pesanan_produk.gudang_barang_jadi_id', $dpp->gudang_barang_jadi_id)->pluck('detail_pesanan_produk.id')->toArray();

        $detail = UjiLabDetail::with('NoseriDetailPesanan.NoseriTGbj.NoseriBarangJadi')
            ->addSelect([
                'belum' => function ($q) {
                    $q->selectRaw('coalesce(count(uji.id),0)')
                        ->from('uji_lab_detail as uji')
                        ->where('uji.status', 'belum')
                        ->where('uji.is_ready', 1)
                        ->whereColumn('uji.id', 'uji_lab_detail.id');
                },
            ])
            ->havingRaw('belum = 0')
            ->whereIN('detail_pesanan_produk_id', $get_dpp)
            ->get();
        if ($detail->isEmpty()) {
            $object = array();
        } else {

            foreach ($detail as $d) {
                $item[] = array(
                    'id' => $d->id,
                    'no' => $d->no,
                    'nomor_sertifikat' => $d->no_sertifikat,
                    'no_seri' => $d->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri,
                    'tgl_masuk' => $d->tgl_masuk,
                    'tgl_sertif' => $d->tgl_kalibrasi,
                    'akhir_sertifikat' => Carbon::parse($d->tgl_kalibrasi)->addDays(365)->format('Y-m-d'),
                    'teknisi' => $d->pemeriksa_id != NULL ? $d->Karyawan->nama : '-',
                    'metode' => $d->metode_id != NULL ? $d->DetailMetodeLab->MetodeLab->metode : '-',
                    'ruang_kalibrasi' =>  $d->metode_id != NULL ? $d->DetailMetodeLab->RuangKalibrasi->nama : '-',
                    'hasil' => $d->status == 'ok' ? 'ok' : 'not_ok',
                );
            }

            $object = new stdClass();
            $object->nama =  $d->DetailPesananProduk->GudangBarangJadi->Produk->KodeLab->nama;
            $object->tipe =  $d->DetailPesananProduk->GudangBarangJadi->Produk->nama;
            $object->seri =  $item;
        }
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $object,
        ], 200);
    }

    public function lab_data_detail($id)
    {
        $ujilab_head = UjiLab::find($id);

        $pesanan = Pesanan::find($ujilab_head->pesanan_id);


        $ujilab = UjiLabDetail::select('uji_lab_detail.id as lab_id', 'detail_pesanan_produk.gudang_barang_jadi_id', 'produk.nama as tipe', 'kode_lab.nama as nama', 'gdg_barang_jadi.nama as variasi', 'detail_pesanan_produk.id as dpp_id')
            ->selectRaw(
                "(CASE
        WHEN uji_lab_detail.status = 'ok'  THEN 1
        ELSE 0
        END) AS ok",
            )
            ->selectRaw(
                "(CASE
        WHEN uji_lab_detail.status = 'nok'   THEN 1
        ELSE 0
        END) AS nok",
            )
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'uji_lab_detail.detail_pesanan_produk_id')
            ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
            ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
            ->leftJoin('kode_lab', 'kode_lab.id', '=', 'produk.kode_lab_id')
            ->where('uji_lab_id', $id)
            ->whereRaw("uji_lab_detail.status != 'belum'")
            ->get();

        if ($ujilab->isEmpty()) {
            $data = array();
        } else {

            $produks = [];
            foreach ($ujilab as $d) {
                $gudang_barang_jadi_id = $d['gudang_barang_jadi_id'];
                if (!isset($produks[$gudang_barang_jadi_id])) {
                    $produks[$gudang_barang_jadi_id] = array(
                        'id' => $d->dpp_id,
                        "nama" => $d->nama,
                        "tipe" => $d->tipe . $d->variasi,
                        "jumlah_ok" => 0,
                        "jumlah_nok" => 0,
                    );
                }
                $produks[$gudang_barang_jadi_id]["jumlah_nok"] += $d->nok;
                $produks[$gudang_barang_jadi_id]["jumlah_ok"] += $d->ok;
            }
            $produks = array_values($produks);

            if ($pesanan->Spa) {
                $c =  $pesanan->Spa->Customer->nama;
            }
            if ($pesanan->Ekatalog) {
                $c =  $pesanan->Ekatalog->Customer->nama;
            }
            if ($pesanan->Spb) {
                $c =  $pesanan->Spb->Customer->nama;
            }

            $data = array(
                'header' => array(
                    'id' => $pesanan->id,
                    'so' => $pesanan->so,
                    'po' => $pesanan->no_po,
                    'tgl_po' => $pesanan->tgl_po,
                    'no_order' => 'LAB-' . sprintf("%04d",  $ujilab_head->no_order),
                    'jenis_pemilik' => $ujilab_head->jenis_pemilik_id != NULL ? $ujilab_head->JenisPemilik->nama : null,
                    'nama' => $ujilab_head->nama,
                    'alamat' => $ujilab_head->alamat,
                    'customer' => $c,
                    'status' => intval(($ujilab_head->GetUji()) / $ujilab_head->GetJumlah() * 100)
                ),
                'produk' => $produks
            );
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
                'data' => $data,
            ], 200);
        }
    }
}
