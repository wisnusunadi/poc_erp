<?php

namespace App\Http\Controllers;

use App\Models\DetailEkatalog;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;
use App\Models\Ekatalog;
use App\Models\GudangBarangJadi;
use App\Models\GudangBarangJadiHis;
use App\Models\JadwalPerakitan;
use App\Models\JadwalRakitNoseri;
use App\Models\NoseriBarangJadi;
use App\Models\NoseriTGbj;
use App\Models\PenjualanProduk;
use App\Models\Pesanan;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;
use App\Models\TFProduksiHis;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProduksiController extends Controller
{
    function CreateTFItem(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                // 'produk_id' => 'required',
                // 'nama' => 'required',
                // 'stok' => 'required|numeric',
                // 'ke' => 'required',
            ],
            [
                // 'produk_id.required' => 'Produk harus diisi',
                // 'nama.required' => 'Nama harus diisi',
                // 'stok.numeric' => 'Stok harus diisi angka',
                // 'stok.required' => 'Stok harus diisi',
                // 'ke.required' => 'Tujuan harus diisi',
            ]
        );

        if ($validator->fails()) {
            return $validator->errors();
        } else {
            foreach ($request->ke as $key => $value) {
                $tf_prod = new TFProduksi();
                $tf_prod->tgl_keluar = Carbon::now();
                $tf_prod->ke = $value;
                $tf_prod->deskripsi = $request->deskripsi[$key];
                $tf_prod->jenis = 'keluar';
                $tf_prod->created_at = Carbon::now();
                $tf_prod->save();

                $tf_prod_det = new TFProduksiDetail();
                $tf_prod_det->t_gbj_id = $tf_prod->id;
                $tf_prod_det->gdg_brg_jadi_id = $request->gdg_brg_jadi_id[$key];
                $tf_prod_det->qty = $request->qty[$key];
                $tf_prod_det->jenis = 'keluar';
                $tf_prod_det->created_at = Carbon::now();
                $tf_prod_det->save();

                $did = $tf_prod_det->id;
                $checked = $request->noseri_id;

                foreach ($request->noseri_id as $k => $v) {
                    if (in_array($request->noseri_id[$k], $checked)) {
                        $nn = new NoseriTGbj();
                        $nn->t_gbj_detail_id = $did;
                        $ss = implode('|', $request->noseri_id[$k]);
                        $rr = explode('|', $ss);
                        for ($i = 0; $i < count($rr); $i++) {
                            $nn->noseri = $rr[$i];
                        }
                        $nn->layout_id = 1;
                        $nn->status_id = 1;
                        $nn->state_id = 2;
                        $nn->jenis = 'keluar';
                        $nn->created_at = Carbon::now();
                        $nn->save();
                    }
                }
            }

            $gdg = GudangBarangJadi::whereIn('id', $request->gdg_brg_jadi_id)->get()->toArray();
            $i = 0;
            foreach ($gdg as $vv) {
                $i++;
                $vv['stok'] = $vv['stok'] - $request->qty[$i];
                GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
            }

            return response()->json(['msg' => 'Successfully',]);
        }
    }

    function TfbySO(Request $request)
    {
        // dd($request->all());
        // $data = TFProduksi::where('pesanan_id', $request->pesanan_id)->get();
        // if (count($data) > 0) {
        //     // foreach($data as $v) {
        //     //     $v->pesanan_id = $request->pesanan_id;
        //     //     $v->tgl_keluar = Carbon::now();
        //     //     $v->ke = 23;
        //     //     $v->jenis = 'keluar';
        //     //     $v->status_id = 1;
        //     //     $v->created_at = Carbon::now();
        //     //     $v->save();
        //     // }

        //     return response()->json(['msg' => 'Data Sudah Ada']);
        // } else {
        $d = new TFProduksi();
        $d->pesanan_id = $request->pesanan_id;
        $d->tgl_keluar = Carbon::now();
        $d->ke = 23;
        $d->jenis = 'keluar';
        $d->status_id = 1;
        $d->state_id = 2;
        $d->created_at = Carbon::now();
        $d->save();

        foreach ($request->gdg_brg_jadi_id as $key => $value) {
            $dd = new TFProduksiDetail();
            $dd->t_gbj_id = $d->id;
            $dd->gdg_brg_jadi_id = $value;
            $dd->qty = $request->qty[$key];
            $dd->jenis = 'keluar';
            $dd->status_id = 1;
            $dd->state_id = 2;
            $dd->created_at = Carbon::now();
            $dd->save();

            $did = $dd->id;
            $checked = $request->noseri_id;
            //     // $x = explode(", ", $request->noseri_id);


            // return response()->json([
            //     'a' => $d,
            //     'b' => $dd,
            //     'c' => $nn,
            // ]);
            // for ($i=0; $i < count($request->gdg_brg_jadi_id); $i++) {
            //     for ($m=0; $m < count($request->noseri_id); $m++) {
            //         NoseriTGbj::insert([
            //             't_gbj_detail_id' => $dd->id,
            //             'noseri_id' => implode(", ", $request->noseri_id[$value]),
            //             'created_at' => Carbon::now(),
            //         ]);
            //     }
            // }
            // echo implode(', ', $request->noseri_id);
            foreach ($request->noseri_id[$value] as $k => $v) {
                if (in_array($request->noseri_id[$value], $checked)) {
                    $nn = new NoseriTGbj();
                    $nn->t_gbj_detail_id = $did;
                    $ss = implode('|', $request->noseri_id[$value]);
                    $rr = explode('|', $ss);
                    for ($i = 0; $i < count($rr); $i++) {
                        $nn->noseri = $rr[$i];
                        // echo json_encode($rr[$i]);
                    }
                    // $nn->noseri = json_encode(json_decode());
                    $nn->layout_id = 1;
                    $nn->status_id = 1;
                    $nn->state_id = 2;
                    $nn->jenis = 'keluar';
                    $nn->created_at = Carbon::now();
                    $nn->save();
                }
            }
        }

        // kurang stok ??





        return response()->json(['msg' => 'Data Tersimpan ke Rancangan']);
        // }
    }

    // get
    function getNoseri(Request $request, $id)
    {
        $data = NoseriBarangJadi::where('gdg_barang_jadi_id', $id)->get();
        return datatables()->of($data)
            ->addColumn('checkbox', function ($data) {
                return '<input type="checkbox" class="cb-child" name="noseri_id[]" id="" value="' . $data->id . '">';
            })
            ->addColumn('noseri', function ($data) {
                return $data->noseri;
            })
            ->rawColumns(['checkbox'])
            ->make(true);
    }

    function getOutSO()
    {
        $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->Pesanan->so;
            })
            ->addColumn('no_po', function ($data) {
                return $data->Pesanan->no_po;
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('batas_out', function ($d) {
                if (isset($d->tgl_kontrak)) {
                    return $d->tgl_kontrak;
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge badge-danger">Produk belum disiapkan</span>';
            })
            ->addColumn('status_prd', function ($data) {
                return '<span class="badge badge-warning">Pengecekan di Gudang</span>';
            })
            ->addColumn('status1', function ($data) {
                if (isset($data->Pesanan->status_cek)) {
                    return '<span class="badge badge-primary">Sudah Dicek</span>';
                } else {
                    return '<span class="badge badge-danger">Belum Dicek</span>';
                }
            })
            ->addColumn('button', function ($data) {
                $x = $data->getTable();

                return '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-value="' . $x . '"  data-id="' . $data->pesanan_id . '">
                            <button class="btn btn-primary dropdown-item" type="button">
                                <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                            </button>
                        </a>
                        </div>';
            })
            ->addColumn('action', function ($data) {
                $x = $data->getTable();
                return '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-value="' . $x . '" data-id="' . $data->pesanan_id . '">
                            <button class="dropdown-item" type="button">
                                <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                            </button>
                        </a>
                        <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="" data-value="' . $x . '"  data-id="' . $data->pesanan_id . '">
                            <button class="dropdown-item" type="button">
                                <i class="far fa-eye"></i>&nbsp;View
                            </button>
                        </a>
                        </div>';
            })
            ->addColumn('button_prd', function ($d) {
                $x = $d->getTable();
                return '<a data-toggle="modal" data-target="#detailproduk" class="detailproduk" data-attr="" data-value="' . $x . '"  data-id="' . $d->pesanan_id . '">
                            <button class="btn btn-outline-info viewProduk"><i class="far fa-eye"></i>&nbsp;Detail</button>
                        </a>';
            })
            ->rawColumns(['button', 'status', 'action', 'status1', 'status_prd', 'button_prd'])
            ->make(true);
    }

    function getDetailSO(Request $request, $id, $value)
    {
        if ($value == "ekatalog") {
            // $data = Ekatalog::where('id', $id)->get();
            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
        } else if ($value == "spa") {
            // $data = Spa::where('pesanan_id', $id)->get();
            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
        } else if ($value == "spb") {
            // $data = Spb::where('id', $id)->get();
            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
        }

        //return $g;

        return datatables()->of($g)
            ->addIndexColumn()
            ->addColumn('produk', function ($data) {
                if (empty($data->gudangbarangjadi->nama)) {
                    return $data->gudangbarangjadi->produk->nama . '<input type="hidden" name="gdg_brg_jadi_id[]" id="gdg_brg_jadi_id" value="' . $data->gudang_barang_jadi_id . '">';
                } else {
                    return $data->gudangbarangjadi->produk->nama . '-' . $data->gudangbarangjadi->nama . '<input type="hidden" name="gdg_brg_jadi_id[]" id="gdg_brg_jadi_id" value="' . $data->gudang_barang_jadi_id . '">';
                }
            })
            ->addColumn('qty', function ($data) {
                return $data->detailpesanan->jumlah . '<input type="hidden" class="jumlah" name="qty[]" id="qty" value="' . $data->detailpesanan->jumlah . '">';
            })
            ->addColumn('tipe', function ($data) {
                if (empty($data->gudangbarangjadi->nama)) {
                    return $data->gudangbarangjadi->produk->nama;
                } else {
                    return $data->gudangbarangjadi->produk->nama . ' ' . $data->gudangbarangjadi->nama;
                }
            })
            ->addColumn('merk', function ($data) {
                return $data->gudangbarangjadi->produk->merk;
            })
            ->addColumn('ids', function ($d) {
                return '<input type="checkbox" class="cb-child" value="' . $d->gudang_barang_jadi_id . '">';
            })
            ->addColumn('action', function ($data) {
                return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="" data-jml="' . $data->detailpesanan->jumlah . '" data-id="' . $data->gudang_barang_jadi_id . '">
                                <button class="btn btn-primary" data-toggle="modal" data-target=".modal-scan"><i
                                class="fas fa-qrcode"></i> Scan Produk</button>
                                </a>';
            })
            ->addColumn('status', function ($data) {
                if (isset($data->status_cek)) {
                    return '<span class="badge badge-success">Sudah Diinput</span>';
                } else {
                    return '<span class="badge badge-danger">Belum Diinput</span>';
                }
            })
            ->addColumn('status_prd', function ($d) {
                if (isset($d->status_cek)) {
                    return '<span class="badge badge-success">Sudah dicek</span>';
                } else {
                    return '<span class="badge badge-danger">Belum dicek</span>';
                }
            })
            ->addColumn('checkbox', function ($d) {
                return '<input type="checkbox" class="cb-child" value="' . $d->gudang_barang_jadi_id . '">';
            })
            ->rawColumns(['action', 'status', 'produk', 'qty', 'checkbox', 'status_prd', 'ids'])
            ->make(true);
    }

    function headerSo($id, $value)
    {
        if ($value == "ekatalog") {
            $data = Pesanan::with('Ekatalog')->find($id);
            return response()->json([
                'so' => $data->so,
                'po' => $data->no_po,
                'akn' => $data->Ekatalog->no_paket,
                'customer' => $data->Ekatalog->Customer->nama,
            ]);
        } else if ($value == "spa") {
            $data = Pesanan::with('spa')->find($id);
            return response()->json([
                'so' => $data->so,
                'po' => $data->no_po,
                'customer' => $data->spa->Customer->nama,
            ]);
        } else if ($value == "spb") {
            $data = Pesanan::with('spb')->find($id);
            return response()->json([
                'so' => $data->so,
                'po' => $data->no_po,
                'customer' => $data->spb->Customer->nama,
            ]);
        }
    }

    function getHistorybyProduk()
    {
        $data = GudangBarangJadi::with('produk', 'satuan')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('stock', function ($d) {
                return $d->stok . ' ' . $d->satuan->nama;
            })
            ->addColumn('kelompok', function ($d) {
                return $d->produk->kelompokproduk->nama;
            })
            ->addColumn('product', function ($d) {
                return $d->produk->nama . ' ' . $d->nama;
            })
            ->addColumn('kode_produk', function ($d) {
                return $d->produk->product->kode . '' . $d->produk->kode;
            })
            ->addColumn('action', function ($d) {
                return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $d->id . '">
                            <button class="btn btn-info" data-toggle="modal" data-target=".modal-detail"><i
                            class="far fa-eye"></i> Detail</button>
                            </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function getNoseriSO(Request $request)
    {
        $data = NoseriBarangJadi::where('gdg_barang_jadi_id', $request->gdg_barang_jadi_id)->get();
        return datatables()->of($data)
            ->addColumn('seri', function ($d) {
                return $d->noseri . '';
            })
            ->addColumn('checkbox', function ($d) {
                return '<input type="checkbox" class="cb-child" name="noseri_id[]"  value="' . $d->id . '">';
            })
            ->RawColumns(['seri', 'checkbox'])
            ->make(true);
    }

    // check
    function checkStok(Request $request)
    {
        $gdg = GudangBarangJadi::where('id', $request->gdg_brg_jadi_id)->first();
        return $gdg;
    }

    function test()
    {
        // $data = TFProduksi::where('pesanan_id', 2)->get();
        // if (count($data) > 0) {
        //     return 'Data Sudah ada';
        // } else {
        //     return 'Data belum ada';
        // }
        // // return $data;

        $a = [];
    }


    // dashboard produksi
    // sale
    function h_minus10()
    {
        $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })
            ->where('tgl_kontrak', '>=', Carbon::now()->subDays(10)->format('Y-m-d'))
            ->get());
        $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return count($data);
    }

    function h_minus5()
    {
        $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })
            ->where('tgl_kontrak', '>=', Carbon::now()->subDays(5)->format('Y-m-d'))
            ->get());
        $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return count($data);
    }

    function h_exp()
    {
        $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })
            ->where('tgl_kontrak', '<', Carbon::now()->format('Y-m-d'))
            ->get());
        $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return count($data);
    }

    function minus5()
    {
        $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })
            ->where('tgl_kontrak', '>=', Carbon::now()->subDays(5)->format('Y-m-d'))
            ->get());
        $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->Pesanan->so;
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('batas_out', function ($d) {
                if (isset($d->tgl_kontrak)) {
                    return $d->tgl_kontrak;
                } else {
                    return '-';
                }
            })
            ->addColumn('button', function ($data) {
                $x = $data->getTable();

                return '<a data-toggle="modal" data-target="#minus5" class="minus5" data-attr="" data-value="' . $x . '"  data-id="' . $data->pesanan_id . '">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </a>';
            })
            ->rawColumns(['button'])
            ->make(true);
    }

    function minus10()
    {
        $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })
            ->where('tgl_kontrak', '>=', Carbon::now()->subDays(10)->format('Y-m-d'))
            ->get());
        $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->Pesanan->so;
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('batas_out', function ($d) {
                if (isset($d->tgl_kontrak)) {
                    return $d->tgl_kontrak;
                } else {
                    return '-';
                }
            })
            ->addColumn('button', function ($data) {
                $x = $data->getTable();

                return '<a data-toggle="modal" data-target="#minus10" class="minus10" data-attr="" data-value="' . $x . '"  data-id="' . $data->pesanan_id . '">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </a>';
            })
            ->rawColumns(['button'])
            ->make(true);
    }

    function expired()
    {
        $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })
            ->where('tgl_kontrak', '<', Carbon::now()->format('Y-m-d'))
            ->get());
        $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());
        $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->Pesanan->so;
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('batas_out', function ($d) {
                if (isset($d->tgl_kontrak)) {
                    return $d->tgl_kontrak;
                } else {
                    return '-';
                }
            })
            ->addColumn('button', function ($data) {
                $x = $data->getTable();

                return '<a data-toggle="modal" data-target="#expired" class="expired" data-attr="" data-value="' . $x . '"  data-id="' . $data->pesanan_id . '">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </a>';
            })
            ->rawColumns(['button'])
            ->make(true);
    }
    // rakit

    function exp_rakit()
    {
        $data = JadwalPerakitan::whereDate('tanggal_selesai', '>', Carbon::now()->subDays(10))->whereNotIn('status_tf', [14])->get();
        // $data = JadwalPerakitan::whereBetween('tanggal_selesai',[$end, Carbon::now()->format('Y-m-d')])->get();
        return datatables()->of($data)
            ->addColumn('start', function ($d) {
                return date('Y-m-d', strtotime($d->tanggal_mulai));
            })
            ->addColumn('end', function ($d) {
                $x = Carbon::now()->diffInDays($d->tanggal_selesai);
                // return $x;

                if ($x <= 10 && $x > 5) {
                    return date('Y-m-d', strtotime($d->tanggal_selesai)) . '<br> <span class="badge badge-info">Kurang ' . $x . ' Hari</span>';
                } elseif ($x <= 5 && $x >= 2) {
                    return date('Y-m-d', strtotime($d->tanggal_selesai)) . '<br> <span class="badge badge-warning">Kurang ' . $x . ' Hari</span>';
                } elseif ($x < 2 && $x >= 0) {
                    return date('Y-m-d', strtotime($d->tanggal_selesai)) . '<br> <span class="badge badge-danger">Kurang ' . $x . ' Hari</span>';
                }
            })
            ->addColumn('no_bppb', function ($d) {
                return '-';
            })
            ->addColumn('produk', function ($d) {
                return $d->produk->produk->nama . '-' . $d->produk->nama;
            })
            ->addColumn('jml', function ($d) {
                return $d->jumlah . ' ' . $d->produk->satuan->nama;
            })
            ->addColumn('button', function ($d) {
                return '<a href="' . url('produksi/jadwal_perakitan') . '" class="btn btn-outline-primary"><i
                        class="fas fa-paper-plane">';
            })
            ->addColumn('button1', function ($d) {
                if ($d->status_tf == 12) {
                    return '<a href="' . url('produksi/pengiriman') . '" class="btn btn-outline-primary"><i
                        class="fas fa-paper-plane">';
                } elseif ($d->status_tf == 11) {
                    return '<a href="' . url('produksi/jadwal_perakitan') . '" class="btn btn-outline-primary"><i
                        class="fas fa-paper-plane">';
                } else {
                }
            })
            ->rawColumns(['button', 'end', 'button1'])
            ->make(true);
    }

    function exp_rakit_h()
    {
        $data = JadwalPerakitan::whereDate('tanggal_selesai', '>', Carbon::now()->subDays(10))->whereNotIn('status_tf', [14])->get();
        return count($data);
    }

    function exp_jadwal()
    {
        $data = JadwalPerakitan::where('state', 'perubahan')->whereNotIn('status_tf', [14])->get();
        return datatables()->of($data)
            ->addColumn('start', function ($d) {
                return date('Y-m-d', strtotime($d->tanggal_mulai));
            })
            ->addColumn('end', function ($d) {
                return date('Y-m-d', strtotime($d->tanggal_selesai));
            })
            ->addColumn('no_bppb', function ($d) {
                return '-';
            })
            ->addColumn('produk', function ($d) {
                return $d->produk->produk->nama . '-' . $d->produk->nama;
            })
            ->addColumn('jml', function ($d) {
                return $d->jumlah . ' ' . $d->produk->satuan->nama;
            })
            ->addColumn('button', function ($d) {
                return '<a href="' . url('produksi/jadwal_perakitan') . '" class="btn btn-outline-primary"><i
                        class="fas fa-paper-plane">';
            })
            ->rawColumns(['button', 'end'])
            ->make(true);
    }

    function exp_jadwal_h()
    {
        $data = JadwalPerakitan::where('state', 'perubahan')->whereNotIn('status_tf', [14])->get();
        return count($data);
    }

    // perakitan
    function plan_rakit()
    {
        $data = JadwalPerakitan::whereMonth('tanggal_mulai', '>', Carbon::now()->format('m'))->get();
        $res = datatables()->of($data)
            ->addColumn('start', function ($d) {
                if (isset($d->tanggal_mulai)) {
                    return date('d-m-Y', strtotime($d->tanggal_mulai));
                } else {
                    return '-';
                }
            })
            ->addColumn('end', function ($d) {
                if (isset($d->tanggal_selesai)) {
                    return date('d-m-Y', strtotime($d->tanggal_selesai));
                } else {
                    return '-';
                }
            })
            ->addColumn('produk', function ($d) {
                if (isset($d->produk_id)) {
                    return $d->produk->produk->nama . '-' . $d->produk->nama;
                }
            })
            ->addColumn('jml', function ($d) {
                return $d->jumlah . ' ' . $d->produk->satuan->nama;
            })
            ->make(true);
        return $res;
    }

    function calender_plan()
    {
        $data = JadwalPerakitan::with('Produk.Produk')->whereMonth('tanggal_mulai', '>', Carbon::now()->format('m'))->get();
        return response()->json($data);
    }

    function calender_current()
    {
        $data = JadwalPerakitan::with('Produk.Produk')->whereMonth('tanggal_mulai', '=', Carbon::now()->format('m'))->get();
        return response()->json($data);
    }

    function on_rakit()
    {
        $data = JadwalPerakitan::whereMonth('tanggal_mulai', '=', Carbon::now()->format('m'))->get();
        $res = datatables()->of($data)
            ->addColumn('start', function ($d) {
                if (isset($d->tanggal_mulai)) {
                    return date('d-m-Y', strtotime($d->tanggal_mulai));
                } else {
                    return '-';
                }
            })
            ->addColumn('end', function ($d) {
                if (isset($d->tanggal_selesai)) {
                    return date('d-m-Y', strtotime($d->tanggal_selesai));
                } else {
                    return '-';
                }
            })
            ->addColumn('produk', function ($d) {
                if (isset($d->produk_id)) {
                    return $d->produk->produk->nama . '-' . $d->produk->nama;
                }
            })
            ->addColumn('jml', function ($d) {
                return $d->jumlah . ' ' . $d->produk->satuan->nama;
            })
            ->addColumn('action', function ($d) {
                return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $d->id . '" data-jml="' . $d->jumlah . '">
                                <button class="btn btn-outline-info"><i class="far fa-edit"></i> Rakit Produk</button>
                            </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        return $res;
    }

    function getSelesaiRakit()
    {
        $data = JadwalPerakitan::where('status_tf', 11)->where('status', 'pelaksanaan')->get();
        return datatables()->of($data)
            ->addColumn('start', function ($d) {
                if (isset($d->tanggal_mulai)) {
                    return date('d-m-Y', strtotime($d->tanggal_mulai));
                } else {
                    return '-';
                }
            })
            ->addColumn('end', function ($d) {
                if (isset($d->tanggal_selesai)) {
                    return date('d-m-Y', strtotime($d->tanggal_selesai));
                } else {
                    return '-';
                }
            })
            ->addColumn('produk', function ($d) {
                if (isset($d->produk_id)) {
                    return $d->produk->produk->nama . '-' . $d->produk->nama;
                }
            })
            ->addColumn('jml', function ($d) {
                return $d->jumlah . ' ' . $d->produk->satuan->nama;
            })
            ->addColumn('action', function ($d) {
                return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $d->id . '" data-jml="' . $d->jumlah . '" data-prd="' . $d->produk_id . '">
                                <button class="btn btn-outline-success"><i class="far fa-edit"></i> Transfer</button>
                            </a>';
            })
            ->addColumn('status', function ($d) {
                if ($d->status_tf == 'Belum Terkirim') {
                    return '<span class="belum_diterima">Belum Diterima</span>';
                } else {
                    return '<span class="sudah_diterima">Sudah Diterima</span>';
                    // return 'xx';
                }
            })
            ->addColumn('no_bppb', function () {
                return '-';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    function detailRakitHeader($id)
    {
        $d = JadwalPerakitan::find($id);
        return response()->json([
            'no_bppb' => '-',
            'produk' => $d->produk->produk->nama . '-' . $d->produk->nama,
            'kategori' => $d->produk->produk->KelompokProduk->nama,
            'jml' => $d->jumlah . ' ' . $d->produk->satuan->nama,
            'start' => date('d-m-Y', strtotime($d->tanggal_mulai)),
            'end' => date('d-m-Y', strtotime($d->tanggal_selesai)),
        ]);
    }

    function storeRakitNoseri(Request $request)
    {
        // dd($request->all());
        foreach ($request->noseri as $key => $value) {
            $seri = new JadwalRakitNoseri();
            $seri->jadwal_id = $request->jadwal_id;
            $seri->noseri = $value;
            $seri->status = 11;
            $seri->save();
            // echo $seri;
        }

        $d = JadwalPerakitan::find($request->jadwal_id);
        $d->status_tf = 12;
        $d->save();

        return response()->json(['msg' => 'Successfully']);
    }

    function getHeaderSeri($id)
    {
        $jadwal = JadwalPerakitan::find($id);
        $gdg = GudangBarangJadi::find($jadwal->produk_id);

        return response()->json([
            'bppb' => '-',
            'produk' => $gdg->produk->nama . ' ' . $gdg->nama,
            'kategori' => $gdg->produk->KelompokProduk->nama,
            'jumlah' => $jadwal->jumlah . ' ' . $gdg->satuan->nama,
            'start' => date('d-m-Y', strtotime($jadwal->tanggal_mulai)),
            'end' => date('d-m-Y', strtotime($jadwal->tanggal_selesai)),
        ]);
    }

    function detailSeri($id)
    {
        // $data = JadwalRakitNoseri::where('jadwal_id', $id)->limit(10)->get();
        $data = JadwalRakitNoseri::whereHas('header', function ($q) use ($id) {
            $q->where('produk_id', $id);
        })->get();
        // return datatables()->of($data)
        //     ->addColumn('checkbox', function ($d) {
        //         return '<input type="checkbox" value="' . $d->noseri . '">';
        //     })
        //     ->addColumn('no_seri', function ($d) {
        //         return $d->noseri;
        //     })
        //     ->make(true);

        // return response()->json($data);
    }

    function detailSeri1($id)
    {
        // $data = JadwalRakitNoseri::where('jadwal_id', $id)->limit(10)->get();
        $data = JadwalRakitNoseri::whereHas('header', function ($q) use ($id) {
            $q->where('produk_id', $id);
        })->get();
        return datatables()->of($data)
            ->addColumn('checkbox', function ($d) {
                return '<input type="checkbox" class="cb-child" value="' . $d->noseri . '">';
            })
            ->addColumn('no_seri', function ($d) {
                return $d->noseri;
            })
            ->rawColumns(['checkbox'])
            ->make(true);
    }

    function kirimseri(Request $request)
    {
        $header = new TFProduksi();
        $header->tgl_masuk = Carbon::now();
        $header->dari = 17;
        $header->jenis = 'masuk';
        $header->created_at = Carbon::now();
        $header->save();

        $detail = new TFProduksiDetail();
        $detail->t_gbj_id = $header->id;
        $detail->gdg_brg_jadi_id = $request->gbj_id;
        $detail->qty = $request->qty;
        $detail->jenis = 'masuk';
        $detail->created_at = Carbon::now();
        $detail->save();

        $check_array = $request->noseri;
        foreach ($request->noseri as $key => $value) {
            if (in_array($request->noseri[$key], $check_array)) {
                $seri = new NoseriBarangJadi();

                $seri->dari = 17;
                $seri->gdg_barang_jadi_id = $request->gbj_id;
                $seri->noseri = $request->noseri[$key];
                $seri->jenis = 'MASUK';
                $seri->is_aktif = 0;
                $seri->created_at = Carbon::now();
                $seri->save();

                $serit = new NoseriTGbj();
                $serit->t_gbj_detail_id = $detail->id;
                $serit->noseri_id = $seri->id;
                $serit->layout_id = 1;
                $serit->jenis = 'MASUK';
                $serit->created_at = Carbon::now();
                $serit->save();
            }
        }

        $rakitseri = JadwalPerakitan::find($request->jadwal_id);
        $rakitseri->status_tf = 14;
        $rakitseri->save();

        JadwalRakitNoseri::where('jadwal_id', $request->jadwal_id)->update(['waktu_tf' => Carbon::now()]);

        // dd($request->all());
    }

    // gbj
    function terimaseri(Request $request)
    {
        // dd($request->all());
        // NoseriTGbj::where('id',$request->seri)->update(['status_id' => 3]);
        $seri = NoseriTGbj::whereIn('id', $request->seri)->get()->toArray();
        $i = 0;
        foreach($seri as $s) {
            // echo $s['noseri_id'];
            $i++;
            NoseriTGbj::find($s['id'])->update(['status_id' => 3]);
            NoseriBarangJadi::find($s['noseri_id'])->update(['is_aktif' => 1]);
            // GudangBarangJadi::find($s[])


        }
        return response()->json(['msg' => 'Successfully']);
    }

    // riwayat rakit
    function h_rakit()
    {
        $data = JadwalPerakitan::where('status_tf', 14)->get()->count('produk_id');
        return $data;
    }

    function h_unit()
    {
        // $data = JadwalPerakitan::where('status_tf', 14)->get()->sum('jumlah');
        // return $data;
        $data = JadwalPerakitan::with('noseri', 'produk.produk')->where('status_tf', 14)->get();
        return $data;
    }

    function his_rakit()
    {
        $rakit = JadwalPerakitan::where('status_tf', 14)->get()->count('produk_id');
        $unit = JadwalPerakitan::where('status_tf', 14)->get()->sum('jumlah');
        $data = JadwalPerakitan::where('status_tf', 14)->get();
        $detail = JadwalPerakitan::with('noseri', 'produk.produk')->where('status_tf', 14)->get();

        return view('page.produksi.riwayat_perakitan', compact('rakit', 'unit', 'data', 'detail'));
    }

    function header_his_rakit($id)
    {
        $detail = JadwalPerakitan::with('noseri', 'produk.produk')->where('status_tf', 14)->where('produk_id', $id)->get();
        $a = [];
        foreach ($detail as $d) {
            $a[] = [
                'day_rakit' => Carbon::createFromFormat('Y-m-d', $d->tanggal_mulai)->format('l, d-m-Y'),
                'time_rakit' => Carbon::createFromFormat('Y-m-d', $d->tanggal_mulai)->format('H:i'),
                'day_kirim' => Carbon::createFromFormat('Y-m-d H:i:s', $d->updated_at)->format('l, d-m-Y'),
                'time_kirim' => Carbon::createFromFormat('Y-m-d H:i:s', $d->updated_at)->format('H:i'),
                'bppb' => '-',
                'produk' => $d->produk->produk->nama . ' ' . $d->produk->nama,
                'jml' => $d->jumlah . ' Unit',
            ];
        }
        return $a;
        // $data = JadwalPerakitan::find($id);
    }
    function ajax_history_rakit()
    {
        $data = JadwalPerakitan::where('status_tf', 14)->get();
        return datatables()->of($data)
            ->addColumn('day_rakit', function ($d) {
                $dd = JadwalRakitNoseri::where('jadwal_id', $d->id)->first();
                return Carbon::createFromFormat('Y-m-d H:i:s', $dd->created_at)->format('l, d-m-Y');
            })
            ->addColumn('day_kirim', function ($d) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $d->updated_at)->format('l, d-m-Y');
            })
            ->addColumn('time_rakit', function ($d) {
                $dd = JadwalRakitNoseri::where('jadwal_id', $d->id)->first();
                return Carbon::createFromFormat('Y-m-d H:i:s', $dd->created_at)->format('H:i');
            })
            ->addColumn('time_kirim', function ($d) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $d->updated_at)->format('H:i');
            })
            ->addColumn('bppb', function ($d) {
                return '-';
            })
            ->addColumn('produk', function ($d) {
                return $d->produk->produk->nama . ' ' . $d->produk->nama;
            })
            ->addColumn('jml', function ($d) {
                return $d->jumlah . ' Unit';
            })
            ->addColumn('kosong', function () {
                return '';
            })
            ->addColumn('action', function ($d) {
                return '<button class="btn btn-outline-secondary detail" data-id="' . $d->produk_id . '"><i class="far fa-eye"></i> Detail</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function testing()
    {
        // $x = array('a');
        // $xx = explode(", ", $x);
        // echo $xx;
    }
}
