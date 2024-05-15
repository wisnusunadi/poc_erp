@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Penjualan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->divisi_id == '26')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="/penjualan/transaksi">Penjualan</a></li>
                        <li class="breadcrumb-item active">Edit Ekatalog</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->

@stop

@section('adminlte_css')
    <style>
        table>tbody>tr>td>.form-group>.select2>.selection>.select2-selection--single {
            height: 100% !important;
        }

        table>tbody>tr>td>.form-group>.select2>.selection>.select2-selection>.select2-selection__rendered {
            word-wrap: break-word !important;
            text-overflow: inherit !important;
            white-space: normal !important;
        }

        .hide {
            display: none !important;
        }

        .margin {
            margin: 5px;
        }

        .margin-top {
            margin-top: 5px;
        }

        .align-center {
            text-align: center;
        }

        legend {
            font-size: 14px;
        }

        filter {
            margin: 5px;
        }

        .blue-bg {
            background-color: #ffeab8;
        }

        #produktable {
            width: 1371px !important;
        }

        .removeshadow {
            box-shadow: none;
        }

        @media screen and (min-width: 1220px) {

            body {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }

            .labelket {
                text-align: right;
            }

            .cust {
                max-width: 40%;
            }

        }

        @media screen and (max-width: 1219px) {

            /* label,
                                                                                                                                                                                                                                                                                                                                                                                                                    .row {
                                                                                                                                                                                                                                                                                                                                                                                                                        font-size: 12px;
                                                                                                                                                                                                                                                                                                                                                                                                                    }

                                                                                                                                                                                                                                                                                                                                                                                                                    h4 {
                                                                                                                                                                                                                                                                                                                                                                                                                        font-size: 20px;
                                                                                                                                                                                                                                                                                                                                                                                                                    } */
            body {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .labelket {
                text-align: right;
            }

            .cust {
                max-width: 40%;
            }
        }

        @media screen and (max-width: 991px) {

            /* label,
                                                                                                                                                                                                                                                                                                                                                                                                                    .row {
                                                                                                                                                                                                                                                                                                                                                                                                                        font-size: 12px;
                                                                                                                                                                                                                                                                                                                                                                                                                    }

                                                                                                                                                                                                                                                                                                                                                                                                                    h4 {
                                                                                                                                                                                                                                                                                                                                                                                                                        font-size: 20px;
                                                                                                                                                                                                                                                                                                                                                                                                                    } */
            body {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .labelket {
                text-align: left;
            }
        }

        .autocomplete {
            position: relative;
            display: inline-block;

        }

        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            /*position the autocomplete items to be the same width as the container:*/
            top: 100%;
            left: 10px;
            right: 10px;
        }

        .autocomplete-items div {
            padding: 5px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }

        /*when hovering an item:*/
        .autocomplete-items div:hover {
            background-color: #ffdb4d;
        }

        /*when navigating through the items using the arrow keys:*/
        .autocomplete-active {
            background-color: #e6c300 !important;
        }

        .select_item .select2-selection--single {
            height: 100% !important;
        }

        .select_item .select2-selection__rendered {
            word-wrap: break-word !important;
            text-overflow: inherit !important;
            white-space: normal !important;
        }
    </style>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    @php
                        $years = \App\Models\AktifPeriode::first()->tahun;
                        $isOpen = false;
                        if ($years != \Carbon\Carbon::now()->year) {
                            $isOpen = true;
                        }
                        $maxDate = $isOpen ? \Carbon\Carbon::parse($years . '-12-31')->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d');
                    @endphp
                    @if ($isOpen)
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            Periode yang dibuka saat ini adalah periode {{ $years }}
                        </div>
                    @endif
                    <div class="card" id="ekatalog">
                        <div class="card-body">
                            <h4 class="margin">Data Penjualan </h4>
                            <div class="row">
                                <div class="col-lg-11 col-md-12">
                                    <div class="row d-flex justify-content-between">
                                        <div class="p-2 cust">
                                            <div>
                                                <div class="margin">
                                                    <small class="text-muted">Info Instansi</small>
                                                </div>
                                                <div class="margin"><b>{{ $e->instansi }}</b></div>
                                                <div class="margin"><b>{{ $e->satuan }}</b></div>
                                                <div class="margin"><b>
                                                        @if (!empty($e->Provinsi))
                                                            {{ $e->Provinsi->nama }}
                                                        @endif
                                                    </b></div>
                                            </div>
                                            <div class="margin-top">
                                                <div class="margin">
                                                    <small class="text-muted">Info Customer</small>
                                                </div>
                                                <div class="margin"><b>{{ $e->customer->nama }}</b></div>
                                                <div class="margin"><b>{{ $e->customer->alamat }}</b></div>
                                                <div class="margin"><b>
                                                        @if (!empty($e->Customer->Provinsi))
                                                            @if ($e->Customer->nama != 'Belum diketahui')
                                                                {{ $e->Customer->Provinsi->nama }}
                                                            @endif
                                                        @endif

                                                    </b></div>
                                                <div class="margin"><b>{{ $e->Customer->telp }}</b></div>
                                            </div>
                                        </div>
                                        <div class="p-2">
                                            <div class="margin">
                                                <div><small class="text-muted">No SO</small></div>
                                                <div>
                                                    @if ($e->Pesanan)
                                                        @if (!empty($e->Pesanan->so))
                                                            <b>{{ $e->Pesanan->so }}</b>
                                                        @else
                                                            <b>-</b>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">No AKN</small></div>
                                                <div><b>{{ $e->no_paket }}</b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Tanggal Order</small>
                                                </div>
                                                <div><b>{{ date('d-m-Y', strtotime($e->tgl_buat)) }}</b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Tanggal Batas Kontrak</small>
                                                </div>
                                                <div><b>
                                                        @if (!empty($e->tgl_kontrak))
                                                            {{ date('d-m-Y', strtotime($e->tgl_kontrak)) }}
                                                        @else
                                                            -
                                                        @endif
                                                    </b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Status</small></div>
                                                @if ($e->status == 'batal')
                                                    <div class="badge red-text">Batal</div>
                                                @elseif($e->status == 'negosiasi')
                                                    <div class="badge yellow-text">Negosiasi</div>
                                                @elseif($e->status == 'sepakat')
                                                    <div class="margin-top"><b><span class="green-text">Sepakat</span></b>
                                                    </div>
                                                @elseif($e->status == 'draft')
                                                    <div class="badge blue-text">Draft</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="p-2">
                                            <div class="margin">
                                                <div><small class="text-muted">No PO</small></div>
                                                <div>
                                                    @if ($e->Pesanan)
                                                        @if (!empty($e->Pesanan->no_po))
                                                            <b>{{ $e->Pesanan->no_po }}</b>
                                                        @else
                                                            <b>-</b>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Tanggal PO</small>
                                                </div>
                                                <div><b>
                                                        @if ($e->Pesanan)
                                                            @if (!empty($e->Pesanan->tgl_po))
                                                                {{ date('d-m-Y', strtotime($e->Pesanan->tgl_po)) }}
                                                            @else
                                                                -
                                                            @endif
                                                        @endif
                                                    </b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">No DO</small></div>
                                                <div><b>
                                                        @if ($e->Pesanan)
                                                            @if (!empty($e->Pesanan->no_do))
                                                                {{ $e->Pesanan->no_do }}
                                                            @else
                                                                -
                                                            @endif
                                                        @endif
                                                    </b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Tanggal DO</small>
                                                </div>
                                                <div><b>
                                                        @if ($e->Pesanan)
                                                            @if (!empty($e->Pesanan->tgl_do))
                                                                {{ date('d-m-Y', strtotime($e->Pesanan->tgl_do)) }}
                                                            @else
                                                                -
                                                            @endif
                                                        @endif
                                                    </b>
                                                </div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Cetak SPPB</small>
                                                </div>
                                                @if ($e->status == 'sepakat' && ($e->Pesanan->no_po != null && $e->Pesanan->tgl_po != null))
                                                    <div>
                                                        <a target="_blank"
                                                            href="{{ route('penjualan.penjualan.cetak_surat_perintah', [$e->Pesanan->id]) }}">
                                                            <button class="btn btn-outline-primary btn-sm" type="button">
                                                                <i class="fas fa-print"></i>
                                                                SPPB
                                                            </button>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div>-</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header bg-warning">
                            <div class="card-title">Form Ubah Data</div>
                        </div>
                        <div class="card-body">
                            @if (session()->has('error') || count($errors) > 0)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Gagal mengubah data!</strong> Periksa
                                    kembali data yang diinput
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @elseif(session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Berhasil mengubah data</strong>,
                                    Terima kasih
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <div class="content">
                                <form method="post" autocomplete="off"
                                    action="{{ route('penjualan.penjualan.update_ekatalog', ['id' => $e->id]) }}"
                                    id="edit_penjualan">
                                    {{ csrf_field() }}
                                    {{ method_field('PUT') }}
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-lg-11 col-md-12">
                                            <h4>Info Customer</h4>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-horizontal">
                                                        <div class="form-group row">
                                                            <label for=""
                                                                class="col-form-label col-lg-5 col-md-12 labelket">Nama
                                                                Customer / Distributor</label>
                                                            <div class="col-lg-5 col-md-12 col-form-label">
                                                                <div class="form-check form-check-inline " id="sudah_dsb">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="namadistributor" id="namadistributor1"
                                                                        value="sudah" />
                                                                    <label class="form-check-label"
                                                                        for="namadistributor1">Sudah Diketahui</label>
                                                                </div>
                                                                <div class="form-check form-check-inline " id="belum_dsb">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="namadistributor" id="namadistributor2"
                                                                        value="belum" />
                                                                    <label class="form-check-label"
                                                                        for="namadistributor2">Belum Diketahui</label>
                                                                </div>
                                                                <div class="invalid-feedback" id="msgnamadistributor">
                                                                    @if ($errors->has('namadistributor'))
                                                                        {{ $errors->first('namadistributor') }}
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for=""
                                                                class="col-form-label col-lg-5 col-md-12 labelket"></label>
                                                            <div class="col-lg-5 col-md-12">
                                                                <select name="customer_id" id="customer_id"
                                                                    class="form-control customer_id custom-select @error('customer_id') is-invalid @enderror">
                                                                    <option value="{{ $e->customer_id }}" selected>
                                                                        {{ $e->customer->nama }}</option>
                                                                </select>
                                                                <div class="invalid-feedback" id="msgcustomer_id">
                                                                    @if ($errors->has('customer_id'))
                                                                        {{ $errors->first('customer_id') }}
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for=""
                                                                class="col-form-label col-lg-5 col-md-12 labelket">Alamat</label>
                                                            <div class="col-lg-7 col-md-12">
                                                                <textarea class="form-control col-form-label @error('alamat') is-invalid @enderror" name="alamat"
                                                                    id="alamat_customer" readonly>{{ $e->customer->alamat }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for=""
                                                                class="col-form-label col-lg-5 col-md-12 labelket">Telepon</label>
                                                            <div class="col-lg-5 col-md-12">
                                                                <input type="text" class="form-control col-form-label"
                                                                    name="telepon" id="telepon_customer" readonly
                                                                    value="{{ $e->customer->telp }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex justify-content-center" id="akn">
                                        <div class="col-lg-11 col-md-12">
                                            <h4>Info AKN</h4>
                                            <div class="card">
                                                <div class="card-body">
                                                    <ul class="nav nav-pills mb-3 nav-justified" id="pills-tab"
                                                        role="tablist">
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link active" id="pills-penjualan-tab"
                                                                data-toggle="pill" href="#pills-penjualan" role="tab"
                                                                aria-controls="pills-penjualan"
                                                                aria-selected="true">Deskripsi Ekatalog</a>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link" id="pills-po-ekat-tab" data-toggle="pill"
                                                                href="#pills-po-ekat" role="tab"
                                                                aria-controls="pills-po-ekat"
                                                                aria-selected="false">Purchase Order</a>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link" id="pills-instansi-tab"
                                                                data-toggle="pill" href="#pills-instansi" role="tab"
                                                                aria-controls="pills-instansi"
                                                                aria-selected="false">Instansi</a>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link disabled" id="pills-pengiriman-tab"
                                                                data-toggle="pill" href="#pills-pengiriman"
                                                                role="tab" aria-controls="pills-pengiriman"
                                                                aria-selected="false">Pengiriman</a>
                                                        </li>
                                                        {{-- <li class="nav-item" role="presentation">
                                                            <a class="nav-link" id="pills-produk-tab" data-toggle="pill"
                                                                href="#pills-produk" role="tab"
                                                                aria-controls="pills-produk" aria-selected="false">Rencana
                                                                Penjualan</a>
                                                        </li> --}}
                                                    </ul>

                                                    <div class="form-horizontal">
                                                        <div class="tab-content" id="pills-tabContent">
                                                            <div class="tab-pane fade show active" id="pills-penjualan"
                                                                role="tabpanel" aria-labelledby="pills-penjualan-tab">
                                                                <div class="card removeshadow">
                                                                    <div class="card-header">
                                                                        <h6>Deskripsi Ekatalog</h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        {{-- @if ($e->status == 'draft' || $e->status == 'batal') --}}
                                                                        <div class="form-group row">
                                                                            <label for=""
                                                                                class="col-form-label col-lg-5 col-md-12 labelket">No
                                                                                Paket</label>
                                                                            <div class="col-lg-5 col-md-12 input-group">
                                                                                <div class="input-group-prepend">
                                                                                    <select
                                                                                        class="form-control jenis_paket"
                                                                                        name="jenis_paket"
                                                                                        id="jenis_paket">
                                                                                        <option value="AK1-"
                                                                                            @if (strpos($e->no_paket, 'AK1-') !== false) selected @endif>
                                                                                            AK1-</option>
                                                                                        <option value="FKS-"
                                                                                            @if (strpos($e->no_paket, 'FKS-') !== false) selected @endif>
                                                                                            FKS-</option>
                                                                                        <option value="KLK-"
                                                                                            @if (strpos($e->no_paket, 'KLK-') !== false) selected @endif>
                                                                                            KLK-</option>
                                                                                    </select>
                                                                                </div>
                                                                                <input type="text"
                                                                                    class="form-control col-form-label @error('no_paket') is-invalid @enderror"
                                                                                    name="no_paket" id="no_paket"
                                                                                    aria-label="ket_no_paket"
                                                                                    @if ($e->status == 'draft' || $e->status == 'batal') @if ($e->no_paket == '')
                                                                                    readonly @endif
                                                                                @elseif ($e->status == 'sepakat' || $e->status == 'negosiasi')
                                                                                readonly @else readonly @endif
                                                                                @if ($e->no_paket != '') value="{{ str_replace(['AK1-', 'FKS-', 'KLK-'], '', $e->no_paket) }}" @endif
                                                                                />
                                                                                <div class="input-group-append
                                                                                    @if ($e->status == 'sepakat' || $e->status == 'negosiasi') hide @endif "
                                                                                    id="checkbox_nopaket">
                                                                                    <span class="input-group-text">
                                                                                        <div
                                                                                            class="form-check form-check-inline">
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox"
                                                                                                name="isi_nopaket"
                                                                                                id="isi_nopaket"
                                                                                                value="true"
                                                                                                @if ($e->no_paket != '') checked @endif />
                                                                                            <label class="form-check-label"
                                                                                                for="isi_nopaket"></label>
                                                                                        </div>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="invalid-feedback"
                                                                                    id="msgno_paket">
                                                                                    @if ($errors->has('no_paket'))
                                                                                        {{ $errors->first('no_paket') }}
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        {{-- @endif --}}
                                                                        <div class="form-group row">
                                                                            <label for=""
                                                                                class="col-form-label col-lg-5 col-md-12 labelket">No
                                                                                Urut</label>
                                                                            <div class="col-lg-2 col-md-4">
                                                                                <input type="number"
                                                                                    class="form-control col-form-label   "
                                                                                    name="no_urut" id="no_urut"
                                                                                    value="{{ $e->no_urut }}" />
                                                                                <div class="invalid-feedback"
                                                                                    id="msgno_urut">
                                                                                    @if ($errors->has('no_urut'))
                                                                                        {{ $errors->first('no_urut') }}
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for=""
                                                                                class="col-form-label col-lg-5 col-md-12 labelket">Status</label>
                                                                            <div class="col-lg-5 col-md-12 col-form-label">
                                                                                <!-- <div class="form-check form-check-inline">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <input class="form-check-input" type="radio" name="status_akn" id="status_akn4" value="draft" />
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <label class="form-check-label" for="status_akn4">Draft</label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div> -->
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio" name="status_akn"
                                                                                        id="status_akn1" value="sepakat"
                                                                                        @if ($e->status == 'sepakat') checked @endif />
                                                                                    <label class="form-check-label"
                                                                                        for="status_akn1">Sepakat</label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio" name="status_akn"
                                                                                        id="status_akn2" value="negosiasi"
                                                                                        @if ($e->status == 'negosiasi') checked @endif />
                                                                                    <label class="form-check-label"
                                                                                        for="status_akn2">Negosiasi</label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio" name="status_akn"
                                                                                        id="status_akn3" value="batal"
                                                                                        @if ($e->status == 'batal') checked @endif />
                                                                                    <label class="form-check-label"
                                                                                        for="status_akn3">Batal</label>
                                                                                </div>
                                                                                @if ($e->status == 'draft')
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="status_akn"
                                                                                            id="status_akn3"
                                                                                            value="draft"
                                                                                            @if ($e->status == 'draft') checked @endif />
                                                                                        <label class="form-check-label"
                                                                                            for="status_akn3">Draft</label>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row  @if ($e->status == 'sepakat' || $e->status == 'negosiasi') hide @endif"
                                                                            id="isi_produk_input">
                                                                            <label for=""
                                                                                class="col-form-label col-lg-5 col-md-12 labelket"></label>
                                                                            <div class="col-lg-6 col-md-12 col-form-label">
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="checkbox" name="isi_produk"
                                                                                        id="isi_produk" value="isi"
                                                                                        @if (count($e->Pesanan->DetailPesanan) > 0) checked @endif />
                                                                                    <label class="form-check-label"
                                                                                        for="isi_produk">Isi Produk</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for=""
                                                                                class="col-form-label col-lg-5 col-md-12 labelket">Tanggal
                                                                                Buat</label>
                                                                            <div class="col-lg-4 col-md-4">
                                                                                <input type="date"
                                                                                    class="form-control col-form-label @error('tgl_buat') is-invalid @enderror"
                                                                                    name="tgl_buat" id="tgl_buat"
                                                                                    max="{{ $maxDate }}"
                                                                                    value="{{ $e->tgl_buat }}" />
                                                                                <div class="invalid-feedback"
                                                                                    id="msgtgl_buat">
                                                                                    @if ($errors->has('tgl_buat'))
                                                                                        {{ $errors->first('tgl_buat') }}
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for=""
                                                                                class="col-form-label col-lg-5 col-md-12 labelket">Tanggal
                                                                                Edit</label>
                                                                            <div class="col-lg-4 col-md-4">
                                                                                <input type="date"
                                                                                    class="form-control col-form-label @error('tgl_edit') is-invalid @enderror"
                                                                                    name="tgl_edit" id="tgl_edit"
                                                                                    max="{{ $maxDate }}"
                                                                                    value="{{ $e->tgl_edit }}" />
                                                                                <div class="invalid-feedback"
                                                                                    id="msgtgl_edit">
                                                                                    @if ($errors->has('tgl_edit'))
                                                                                        {{ $errors->first('tgl_edit') }}
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for=""
                                                                                class="col-form-label col-lg-5 col-md-12 labelket">Tanggal
                                                                                Delivery</label>
                                                                            <div class="col-lg-4 col-md-4">
                                                                                <input type="date"
                                                                                    class="form-control col-form-label @error('batas_kontrak') is-invalid @enderror"
                                                                                    name="batas_kontrak"
                                                                                    id="batas_kontrak"
                                                                                    value="{{ $e->tgl_kontrak }}"
                                                                                    @if ($e->status != 'sepakat') disabled="true" @endif />
                                                                                <div class="invalid-feedback"
                                                                                    id="msgbatas_kontrak">
                                                                                    @if ($errors->has('batas_kontrak'))
                                                                                        {{ $errors->first('batas_kontrak') }}
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade show" id="pills-po-ekat"
                                                                role="tabpanel" aria-labelledby="pills-po-ekat-tab">
                                                                <div class="card removeshadow">
                                                                    <div class="card-header">
                                                                        <h6>Purchase Order</h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="form-group row">
                                                                            <label for="no_po_ekat"
                                                                                class="col-lg-5 col-md-12 col-form-label labelket">No
                                                                                PO</label>
                                                                            <div class="col-lg-5 col-md-12">
                                                                                <input type="text"
                                                                                    class="form-control @error('no_po_ekat') is-invalid @enderror"
                                                                                    value="{{ $e->Pesanan->no_po }}"
                                                                                    placeholder="Masukkan Nomor Purchase Order"
                                                                                    id="no_po_ekat" name="no_po_ekat" />
                                                                                <div class="invalid-feedback"
                                                                                    id="msgno_po_ekat">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for="tanggal_po_ekat"
                                                                                class="col-lg-5 col-md-12 col-form-label labelket">Tanggal
                                                                                PO</label>
                                                                            <div class="col-lg-5 col-md-12">
                                                                                <input type="date"
                                                                                    class="form-control @error('tanggal_po_ekat') is-invalid @enderror"
                                                                                    max="{{ $maxDate }}"
                                                                                    value="{{ $e->Pesanan->tgl_po }}"
                                                                                    placeholder="Masukkan Tanggal Purchase Order"
                                                                                    id="tanggal_po_ekat"
                                                                                    name="tanggal_po_ekat" />
                                                                                <div class="invalid-feedback"
                                                                                    id="msgtanggal_po_ekat">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for=""
                                                                                class="col-form-label col-lg-5 col-md-12 labelket">Delivery
                                                                                Order</label>
                                                                            <div class="col-lg-5 col-md-12 col-form-label">
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio" name="do_ekat"
                                                                                        id="yes" value="yes"
                                                                                        @if ($e->Pesanan->no_do != null) checked @endif />
                                                                                    <label class="form-check-label"
                                                                                        for="yes">Tersedia</label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="radio" name="do_ekat"
                                                                                        id="no" value="no"
                                                                                        @if ($e->Pesanan->no_do == null) checked @endif />
                                                                                    <label class="form-check-label"
                                                                                        for="no">Tidak
                                                                                        tersedia</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row @if ($e->Pesanan->no_do == null) hide @endif"
                                                                            id="do_detail_no_ekat">
                                                                            <label for=""
                                                                                class="col-form-label col-lg-5 col-md-12 labelket">Nomor
                                                                                DO</label>
                                                                            <div class="col-lg-5 col-md-12">
                                                                                <input type="text"
                                                                                    class="form-control col-form-label @error('no_do_ekat') is-invalid @enderror"
                                                                                    id="no_do_ekat" name="no_do_ekat"
                                                                                    value="{{ $e->Pesanan->no_do }}" />
                                                                                <div class="invalid-feedback"
                                                                                    id="msgno_do_ekat">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row @if ($e->Pesanan->tgl_do == null) hide @endif"
                                                                            id="do_detail_tgl_ekat">
                                                                            <label for=""
                                                                                class="col-form-label col-lg-5 col-md-12 labelket">Tanggal
                                                                                DO</label>
                                                                            <div class="col-lg-5 col-md-12">
                                                                                <input type="date"
                                                                                    class="form-control col-form-label @error('tanggal_do_ekat') is-invalid @enderror"
                                                                                    id="tanggal_do_ekat"
                                                                                    name="tanggal_do_ekat"
                                                                                    value="{{ $e->Pesanan->tgl_do }}" />
                                                                                <div class="invalid-feedback"
                                                                                    id="msgtanggal_do_ekat">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for="keterangan_ekat"
                                                                                class="col-lg-5 col-md-12 col-form-label labelket">Keterangan</label>
                                                                            <div class="col-lg-5 col-md-12">
                                                                                <textarea class="form-control" placeholder="Masukkan Keterangan" id="keterangan_ekat" name="keterangan_ekat">{{ $e->Pesanan->ket }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade show" id="pills-instansi"
                                                                role="tabpanel" aria-labelledby="pills-instansi-tab">
                                                                <div class="card removeshadow">
                                                                    <div class="card-header">
                                                                        <h6>Instansi</h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="form-group row">
                                                                            <label for=""
                                                                                class="col-form-label col-lg-5 col-md-12 labelket">Instansi</label>
                                                                            <div class="col-lg-7 col-md-12 autocomplete">
                                                                                <input type="text" name="instansi"
                                                                                    id="instansi"
                                                                                    class="form-control col-form-label @error('instansi') is-invalid @enderror"
                                                                                    value="{{ $e->instansi }}" />
                                                                                <div class="invalid-feedback"
                                                                                    id="msginstansi">
                                                                                    @if ($errors->has('instansi'))
                                                                                        {{ $errors->first('instansi') }}
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for=""
                                                                                class="col-form-label col-lg-5 col-md-12 labelket">Satuan
                                                                                Kerja</label>
                                                                            <div class="col-lg-7 col-md-12">
                                                                                <input type="text"
                                                                                    class="form-control col-form-label @error('satuan_kerja') is-invalid @enderror"
                                                                                    name="satuan_kerja" id="satuan_kerja"
                                                                                    value="{{ $e->satuan }}" />
                                                                                <div class="invalid-feedback"
                                                                                    id="msgsatuan_kerja">
                                                                                    @if ($errors->has('satuan_kerja'))
                                                                                        {{ $errors->first('satuan_kerja') }}
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for=""
                                                                                class="col-form-label col-lg-5 col-md-12 labelket">Alamat
                                                                                Instansi</label>
                                                                            <div class="col-lg-7 col-md-12">
                                                                                <textarea class="form-control col-form-label @error('alamatinstansi') is-invalid @enderror" name="alamatinstansi"
                                                                                    id="alamatinstansi">{{ $e->alamat }}</textarea>
                                                                                <div class="invalid-feedback"
                                                                                    id="msgalamatinstansi">
                                                                                    @if ($errors->has('alamatinstansi'))
                                                                                        {{ $errors->first('alamatinstansi') }}
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for=""
                                                                                class="col-form-label col-lg-5 col-md-12 labelket">Provinsi</label>
                                                                            <div class="col-lg-7 col-md-12">
                                                                                <select name="provinsi" id="provinsi"
                                                                                    class="form-control custom-select provinsi @error('provinsi') is-invalid @enderror"
                                                                                    style="width: 100%;">
                                                                                    @if ($e->provinsi_id != null || $e->provinsi_id != '')
                                                                                        <option
                                                                                            value="{{ $e->provinsi_id }}"
                                                                                            selected>
                                                                                            {{ $e->provinsi->nama }}
                                                                                        </option>
                                                                                    @endif
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for=""
                                                                                class="col-form-label col-lg-5 col-md-12 labelket">Deskripsi</label>
                                                                            <div class="col-lg-5 col-md-12">
                                                                                <textarea class="form-control col-form-label @error('deskripsi') is-invalid @enderror" name="deskripsi"
                                                                                    id="deskripsi">{{ $e->deskripsi }}</textarea>
                                                                                <div class="invalid-feedback"
                                                                                    id="msgdeskripsi">
                                                                                    @if ($errors->has('deskripsi'))
                                                                                        {{ $errors->first('deskripsi') }}
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for="keterangan"
                                                                                class="col-form-label col-lg-5 col-md-12 labelket">Keterangan</label>
                                                                            <div class="col-lg-5 col-md-12">
                                                                                <textarea class="form-control col-form-label" id="keterangan" name="keterangan">{{ $e->ket }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="pills-pengiriman"
                                                                role="tabpanel" aria-labelledby="pills-pengiriman-tab">
                                                                <div class="card removeshadow">
                                                                    <div class="card-header">
                                                                        <h6>Pengiriman</h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="form-group row">
                                                                            <label for=""
                                                                                class="col-lg-5 col-md-12 col-form-label labelket">Alamat
                                                                                Pengiriman</label>
                                                                            <div class="col-lg-6 col-md-12 col-form-label">
                                                                                <div class="form-check form-check-inline">
                                                                                    <input type="radio"
                                                                                        class="form-check-input"
                                                                                        name="pilihan_pengiriman"
                                                                                        id="pengiriman0"
                                                                                        value="distributor" />
                                                                                    <label for="pengiriman0"
                                                                                        class="form-check-label">Sama
                                                                                        dengan Distributor</label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input type="radio"
                                                                                        class="form-check-input"
                                                                                        name="pilihan_pengiriman"
                                                                                        id="pengiriman1"
                                                                                        value="instansi" />
                                                                                    <label for="pengiriman1"
                                                                                        class="form-check-label">Sama
                                                                                        dengan Instansi</label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input type="radio"
                                                                                        class="form-check-input"
                                                                                        name="pilihan_pengiriman"
                                                                                        id="lainnya" value="lainnya" />
                                                                                    <label for="lainnya"
                                                                                        class="form-check-label">Lainnya</label>
                                                                                </div>
                                                                                <input type="text"
                                                                                    name="perusahaan_pengiriman"
                                                                                    value="{{ $e->pesanan->tujuan_kirim }}"
                                                                                    id="perusahaan_pengiriman"
                                                                                    class="form-control col-form-label"
                                                                                    readonly>
                                                                                <input type="text"
                                                                                    value="{{ $e->pesanan->alamat_kirim }}"
                                                                                    class="form-control col-form-label mt-2"
                                                                                    name="alamat_pengiriman"
                                                                                    id="alamat_pengiriman" readonly />
                                                                                <div class="invalid-feedback"
                                                                                    id="msg_alamat_pengiriman">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for=""
                                                                                class="col-lg-5 col-md-12 col-form-label labelket">Kemasan</label>
                                                                            <div class="col-lg-6 col-md-12 col-form-label">
                                                                                <div class="form-check form-check-inline">
                                                                                    <input type="radio"
                                                                                        class="form-check-input"
                                                                                        name="kemasan" id="kemasan0"
                                                                                        value="peti"
                                                                                        @if ($e->pesanan->kemasan == 'peti') checked @endif />
                                                                                    <label for="kemasan0"
                                                                                        class="form-check-label">PETI</label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input type="radio"
                                                                                        class="form-check-input"
                                                                                        name="kemasan" id="kemasan1"
                                                                                        value="nonpeti"
                                                                                        @if ($e->pesanan->kemasan == 'nonpeti') checked @endif />
                                                                                    <label for="kemasan1"
                                                                                        class="form-check-label">NON
                                                                                        PETI</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for=""
                                                                                class="col-lg-5 col-md-12 col-form-label labelket">Ekspedisi</label>
                                                                            <div class="col-lg-6 col-md-12 col-form-label">
                                                                                <select name="ekspedisi" id="ekspedisi"
                                                                                    class="form-control">
                                                                                    @if ($e->pesanan->ekspedisi_id != null || $e->pesanan->ekspedisi_id != '')
                                                                                        <option
                                                                                            value="{{ $e->pesanan->ekspedisi_id }}"
                                                                                            selected>
                                                                                            {{ $e->pesanan->Ekspedisi->nama }}
                                                                                        </option>
                                                                                    @endif
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row d-none">
                                                                            <label for=""
                                                                                class="col-lg-5 col-md-12 col-form-label labelket">Keterangan</label>
                                                                            <div class="col-lg-6 col-md-12 col-form-label">
                                                                                <textarea class="form-control col-form-label" name="keterangan_pengiriman">{{ $e->pesanan->ket_kirim }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade show" id="pills-produk"
                                                                role="tabpanel" aria-labelledby="pills-produk-tab">
                                                                <div class="card removeshadow">
                                                                    <div class="card-header">
                                                                        <h6>Rencana Penjualan</h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="col-lg-12 col-md-12 perencanaan">
                                                                            <div class="table-responsive">
                                                                                <table class="table"
                                                                                    style="text-align: center; width: 100%;"
                                                                                    id="perencanaantable">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>No</th>
                                                                                            <th>Nama Produk</th>
                                                                                            <th>Qty</th>
                                                                                            <th>Realisasi</th>
                                                                                            <th>Harga</th>
                                                                                            <th>Aksi</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>

                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex justify-content-center" id="dataproduk">
                                        <div class="col-lg-11 col-md-12">
                                            <h4>Data Produk</h4>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="table-responsive">
                                                                <table class="table" style="text-align: center;"
                                                                    id="produktable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="100%">
                                                                                <button type="button"
                                                                                    class="btn btn-primary float-right"
                                                                                    id="addrowproduk">
                                                                                    <i class="fas fa-plus"></i>
                                                                                    Produk
                                                                                </button>
                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th width="5%">No</th>
                                                                            <th width="35%">Nama Paket</th>
                                                                            <th width="10%">Jumlah</th>
                                                                            <th width="15%">Harga</th>
                                                                            <th width="15%">Ongkir</th>
                                                                            <th width="15%">Subtotal</th>
                                                                            <th width="15%">Pajak</th>
                                                                            <th width="15%">Req. Kalibrasi</th>
                                                                            <th width="10%">
                                                                                Stok Distributor <br>
                                                                                <input type="checkbox"
                                                                                    class="checkAllDistributor">
                                                                            </th>
                                                                            <th width="5%">Aksi</th>
                                                                        </tr>
                                                                    </thead>

                                                                    <tbody>
                                                                        <?php $produkpenjualan = 0;
                                                                        $no = 1; ?>
                                                                        @if (isset($e->pesanan))
                                                                            @if (count($item) > 0)
                                                                                @foreach ($item as $f)
                                                                                    <tr>
                                                                                        <td>{{ $no++ }}</td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="form-group select_item">
                                                                                                <select
                                                                                                    name="penjualan_produk_id[]"
                                                                                                    id="{{ $no - 2 }}"
                                                                                                    class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror"
                                                                                                    style="width:100%;">
                                                                                                    <option
                                                                                                        value="{{ $f['penjualan_produk_id'] }}"
                                                                                                        selected>
                                                                                                        {{ $f['nama'] }}
                                                                                                    </option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="detail_produk"
                                                                                                id="detail_produk{{ $no - 2 }}">
                                                                                                <fieldset>
                                                                                                    <legend><b>Detail
                                                                                                            Produk</b>
                                                                                                    </legend>
                                                                                                    {{-- sini --}}
                                                                                                    <?php $variasi = 0; ?>
                                                                                                    @foreach ($f['detail'] as $g)
                                                                                                        <div>
                                                                                                            <div
                                                                                                                class="card-body blue-bg">
                                                                                                                <h6>{{ $g['nama'] }}
                                                                                                                </h6>
                                                                                                                <select
                                                                                                                    class="form-control variasi"
                                                                                                                    name="variasi[{{ $produkpenjualan }}][{{ $variasi }}]"
                                                                                                                    id="variasi{{ $produkpenjualan }}{{ $variasi }}"
                                                                                                                    style="width:100%;"
                                                                                                                    data-attr="variasi{{ $variasi }}"
                                                                                                                    data-id="{{ $variasi }}">
                                                                                                                    <option
                                                                                                                        value="{{ $g['gbj_id'] }}">
                                                                                                                        @if (!empty(trim($g['variasi'])))
                                                                                                                            {{ $g['variasi'] }}
                                                                                                                        @else
                                                                                                                            {{ $g['nama'] }}
                                                                                                                        @endif
                                                                                                                    </option>
                                                                                                                </select>
                                                                                                                <span
                                                                                                                    class=" invalid-feedback d-block ketstok"
                                                                                                                    name="ketstok[{{ $produkpenjualan }}][{{ $variasi }}]"
                                                                                                                    id="ketstok{{ $produkpenjualan }}{{ $variasi }}"
                                                                                                                    data-attr="ketstok{{ $variasi }}"
                                                                                                                    data-id="{{ $variasi }}"></span>
                                                                                                                    <small class="text-muted">Jumlah barang dipinjamkan: 10</small>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <?php $variasi = $variasi + 1; ?>
                                                                                                    @endforeach
                                                                                                </fieldset>
                                                                                            </div>
                                                                                            <div class="detailjual"
                                                                                                id="tes0">
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="form-group d-flex justify-content-center">
                                                                                                <div class="input-group">
                                                                                                    <input type="number"
                                                                                                        class="form-control produk_jumlah"
                                                                                                        aria-label="produk_satuan"
                                                                                                        name="produk_jumlah[{{ $produkpenjualan }}]"
                                                                                                        id="produk_jumlah{{ $produkpenjualan }}"
                                                                                                        style="width:100%;"
                                                                                                        value="{{ $f['jumlah'] }}">

                                                                                                </div>
                                                                                                <small
                                                                                                    id="produk_ketersediaan"></small>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="form-group d-flex justify-content-center">

                                                                                                <input type="text"
                                                                                                    class="form-control produk_harga"
                                                                                                    name="produk_harga[{{ $produkpenjualan }}]"
                                                                                                    id="produk_harga{{ $produkpenjualan }}"
                                                                                                    placeholder="Masukkan Harga"
                                                                                                    style="width:100%;"
                                                                                                    aria-describedby="prdhrg"
                                                                                                    value="{{ number_format($f['harga'], 0, ',', '.') }}" />
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="form-group d-flex justify-content-center">

                                                                                                <input type="text"
                                                                                                    class="form-control produk_ongkir"
                                                                                                    name="produk_ongkir[{{ $produkpenjualan }}]"
                                                                                                    id="produk_ongkir{{ $produkpenjualan }}"
                                                                                                    placeholder="Masukkan Harga"
                                                                                                    style="width:100%;"
                                                                                                    aria-describedby="prdong"
                                                                                                    value="{{ number_format($f['ongkir'], 0, ',', '.') }}" />
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="form-group d-flex justify-content-center">

                                                                                                <input type="text"
                                                                                                    class="form-control produk_subtotal"
                                                                                                    name="produk_subtotal[{{ $produkpenjualan }}]"
                                                                                                    id="produk_subtotal{{ $produkpenjualan }}"
                                                                                                    placeholder="Masukkan Subtotal"
                                                                                                    style="width:100%;"
                                                                                                    value="{{ number_format($f['harga'] * $f['jumlah'] + $f['ongkir'], 0, ',', '.') }}"
                                                                                                    aria-describedby="prdsub"
                                                                                                    readonly />
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="custom-control custom-switch">
                                                                                                <input type="checkbox"
                                                                                                    class="custom-control-input custom-control-input-pajak produk_ppn"
                                                                                                    id="produk_ppn{{ $produkpenjualan }}"
                                                                                                    name="produk_ppn[{{ $produkpenjualan }}]"
                                                                                                    value="{{ $f['ppn'] }}"
                                                                                                    @if ($f['ppn'] == 1) checked @endif>

                                                                                                <label
                                                                                                    class="custom-control-label custom-control-label-pajak produk_ppn_label"
                                                                                                    for="produk_ppn{{ $produkpenjualan }}">
                                                                                                    @if ($f['ppn'] == 1)
                                                                                                        PPN
                                                                                                    @else
                                                                                                        Non PPN
                                                                                                    @endif
                                                                                                </label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="custom-control custom-switch">
                                                                                                <input type="checkbox"
                                                                                                    class="custom-control-input custom-control-input-kalibrasi produk_kalibrasi"
                                                                                                    id="produk_kalibrasi{{ $produkpenjualan }}"
                                                                                                    name="produk_kalibrasi[{{ $produkpenjualan }}]"
                                                                                                    value="{{ $f['kalibrasi'] }}"
                                                                                                    data-kalibrasi="{{ $f['is_kalibrasi'] == 1 ? 'true' : 'false' }}"
                                                                                                    @if ($f['is_kalibrasi'] == false) disabled @endif
                                                                                                    @if ($f['kalibrasi'] == 1) checked @endif>

                                                                                                <label
                                                                                                    class="custom-control-label custom-control-label-kalibrasi produk_kalibrasi_label"
                                                                                                    for="produk_kalibrasi{{ $produkpenjualan }}">
                                                                                                    @if ($f['kalibrasi'] == 1)
                                                                                                        Ya
                                                                                                    @else
                                                                                                        Tidak
                                                                                                    @endif
                                                                                                </label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="form-group d-flex align-items-center">
                                                                                                <input type="checkbox"
                                                                                                    class="stok_distributor"
                                                                                                    name="stok_distributor[{{ $produkpenjualan }}]"
                                                                                                    id="stok_distributor{{ $produkpenjualan }}"
                                                                                                    value="{{ $produkpenjualan }}"
                                                                                                    @if ($f['jenis'] == 'dsb') checked @endif
                                                                                                    style="width:100%;" />
                                                                                            </div>
                                                                                            @if ($f['jenis'] == 'dsb')
                                                                                                <button type="button"
                                                                                                    class="btn btn-sm btn-outline-primary btnNoSeri">No
                                                                                                    Seri</button>
                                                                                            @else
                                                                                                <button type="button"
                                                                                                    class="btn btn-sm btn-outline-primary btnNoSeri"
                                                                                                    hidden>No Seri</button>
                                                                                            @endif

                                                                                            <input type="hidden"
                                                                                                name="noSeriDistributor[{{ $produkpenjualan }}]"
                                                                                                class="noSeriDistributor"
                                                                                                value="{{ $f['seri'] }}">
                                                                                        </td>
                                                                                        <td hidden>
                                                                                            <input type="hidden"
                                                                                                class="rencana_id"
                                                                                                name="rencana_id[{{ $produkpenjualan }}]"
                                                                                                id="rencana_id{{ $produkpenjualan }}"
                                                                                                readonly
                                                                                                value="{{ $f['detail_rencana_penjualan_id'] }}">
                                                                                        </td>
                                                                                        <td>
                                                                                            <a id="removerowproduk"><i
                                                                                                    class="fas fa-minus"
                                                                                                    style="color: red"></i></a>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <?php $produkpenjualan = $produkpenjualan + 1; ?>
                                                                                @endforeach
                                                                            @else
                                                                                <tr>
                                                                                    <td>1</td>
                                                                                    <td>
                                                                                        <div
                                                                                            class="form-group select_item">
                                                                                            <select
                                                                                                name="penjualan_produk_id[0]"
                                                                                                id="0"
                                                                                                class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror"
                                                                                                style="width:100%;">
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="detail_produk"
                                                                                            id="detail_produk0">
                                                                                        </div>
                                                                                        <div class="detailjual"
                                                                                            id="tes0">
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div
                                                                                            class="form-group d-flex justify-content-center">
                                                                                            <div class="input-group">
                                                                                                <input type="number"
                                                                                                    class="form-control produk_jumlah"
                                                                                                    aria-label="produk_satuan"
                                                                                                    name="produk_jumlah[]"
                                                                                                    id="produk_jumlah"
                                                                                                    style="width:100%;"
                                                                                                    value="">

                                                                                            </div>
                                                                                            <small
                                                                                                id="produk_ketersediaan"></small>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div
                                                                                            class="form-group d-flex justify-content-center">

                                                                                            <input type="text"
                                                                                                class="form-control produk_harga"
                                                                                                name="produk_harga[0]"
                                                                                                id="produk_harga0"
                                                                                                placeholder="Masukkan Harga"
                                                                                                style="width:100%;"
                                                                                                aria-describedby="prdhrg"
                                                                                                value="" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div
                                                                                            class="form-group d-flex justify-content-center">

                                                                                            <input type="text"
                                                                                                class="form-control produk_ongkir"
                                                                                                name="produk_ongkir[0]"
                                                                                                id="produk_ongkir0"
                                                                                                placeholder="Masukkan Ongkir"
                                                                                                style="width:100%;"
                                                                                                aria-describedby="prdhrg"
                                                                                                value="" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div
                                                                                            class="form-group d-flex justify-content-center">

                                                                                            <input type="text"
                                                                                                class="form-control produk_subtotal"
                                                                                                name="produk_subtotal[0]"
                                                                                                id="produk_subtotal0"
                                                                                                placeholder="Masukkan Subtotal"
                                                                                                style="width:100%;"
                                                                                                value=""
                                                                                                aria-describedby="prdsub"
                                                                                                readonly />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div
                                                                                            class="custom-control custom-switch">
                                                                                            <input type="checkbox"
                                                                                                class="custom-control-input custom-control-input-pajak produk_ppn"
                                                                                                id="produk_ppn0"
                                                                                                name="produk_ppn[0]"
                                                                                                value="1" checked>
                                                                                            <label
                                                                                                class="custom-control-label custom-control-label-pajak produk_ppn_label"
                                                                                                for="produk_ppn0">PPN</label>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div
                                                                                            class="custom-control custom-switch">
                                                                                            <input type="checkbox"
                                                                                                class="custom-control-input custom-control-input-kalibrasi produk_kalibrasi"
                                                                                                id="produk_kalibrasi0"
                                                                                                name="produk_kalibrasi[0]"
                                                                                                value="0">
                                                                                            <label
                                                                                                class="custom-control-label custom-control-label-kalibrasi produk_kalibrasi_label"
                                                                                                for="produk_kalibrasi0">Tidak</label>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div
                                                                                            class="form-group d-flex align-items-center">
                                                                                            <input type="checkbox"
                                                                                                class="stok_distributor"
                                                                                                name="stok_distributor[0]"
                                                                                                id="stok_distributor0"
                                                                                                value="0"
                                                                                                style="width:100%;" />
                                                                                        </div>
                                                                                        <button type="button"
                                                                                            class="btn btn-sm btn-outline-primary btnNoSeri"
                                                                                            hidden>No Seri</button>
                                                                                        <input type="hidden"
                                                                                            name="noSeriDistributor[0]"
                                                                                            class="noSeriDistributor">
                                                                                    </td>
                                                                                    <td hidden><input type="hidden"
                                                                                            class="rencana_id"
                                                                                            name="rencana_id[]"
                                                                                            id="rencana_id0" readonly></td>
                                                                                    <td>
                                                                                        <a id="removerowproduk"><i
                                                                                                class="fas fa-minus"
                                                                                                style="color: red"></i></a>
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                        @else
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>
                                                                                    <div class="form-group select_item">
                                                                                        <select
                                                                                            name="penjualan_produk_id[0]"
                                                                                            id="0"
                                                                                            class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror"
                                                                                            style="width:100%;">
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="detail_produk"
                                                                                        id="detail_produk0">
                                                                                    </div>
                                                                                    <div class="detailjual"
                                                                                        id="tes0">
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div
                                                                                        class="form-group d-flex justify-content-center">
                                                                                        <div class="input-group">
                                                                                            <input type="number"
                                                                                                class="form-control produk_jumlah"
                                                                                                aria-label="produk_satuan"
                                                                                                name="produk_jumlah[0]"
                                                                                                id="produk_jumlah0"
                                                                                                style="width:100%;"
                                                                                                value="">

                                                                                        </div>
                                                                                        <small
                                                                                            id="produk_ketersediaan"></small>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div
                                                                                        class="form-group d-flex justify-content-center">

                                                                                        <input type="text"
                                                                                            class="form-control produk_harga"
                                                                                            name="produk_harga[0]"
                                                                                            id="produk_harga0"
                                                                                            placeholder="Masukkan Harga"
                                                                                            style="width:100%;"
                                                                                            aria-describedby="prdhrg"
                                                                                            value="" />
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div
                                                                                        class="form-group d-flex justify-content-center">

                                                                                        <input type="text"
                                                                                            class="form-control produk_ongkir"
                                                                                            name="produk_ongkir[0]"
                                                                                            id="produk_ongkir0"
                                                                                            placeholder="Masukkan Harga"
                                                                                            style="width:100%;"
                                                                                            aria-describedby="prdhrg"
                                                                                            value="" />
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div
                                                                                        class="form-group d-flex justify-content-center">

                                                                                        <input type="text"
                                                                                            class="form-control produk_subtotal"
                                                                                            name="produk_subtotal[0]"
                                                                                            id="produk_subtotal0"
                                                                                            placeholder="Masukkan Subtotal"
                                                                                            style="width:100%;"
                                                                                            value=""
                                                                                            aria-describedby="prdsub"
                                                                                            readonly />
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div
                                                                                        class="custom-control custom-switch">
                                                                                        <input type="checkbox"
                                                                                            class="custom-control-input custom-control-input-pajak produk_ppn"
                                                                                            id="produk_ppn0"
                                                                                            name="produk_ppn[0]"
                                                                                            value="1" checked>
                                                                                        <label
                                                                                            class="custom-control-label custom-control-label-pajak produk_ppn_label"
                                                                                            for="produk_ppn0">PPN</label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div
                                                                                        class="custom-control custom-switch">
                                                                                        <input type="checkbox"
                                                                                            class="custom-control-input produk_kalibrasi custom-control-input-kalibrasi"
                                                                                            id="produk_kalibrasi0"
                                                                                            name="produk_kalibrasi[0]"
                                                                                            value="0">
                                                                                        <label
                                                                                            class="custom-control-label produk_kalibrasi_label custom-control-label-kalibrasi"
                                                                                            for="produk_kalibrasi0">Tidak</label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div
                                                                                        class="form-group d-flex align-items-center">
                                                                                        <input type="checkbox"
                                                                                            class="stok_distributor"
                                                                                            name="stok_distributor[0]"
                                                                                            id="stok_distributor0"
                                                                                            value="0"
                                                                                            style="width:100%;" />
                                                                                    </div>
                                                                                    <button type="button"
                                                                                        class="btn btn-sm btn-outline-primary btnNoSeri"
                                                                                        hidden>No Seri</button>
                                                                                    <input type="hidden"
                                                                                        name="noSeriDistributor[0]"
                                                                                        class="noSeriDistributor">
                                                                                </td>
                                                                                <td hidden><input type="hidden"
                                                                                        class="rencana_id"
                                                                                        name="rencana_id[]"
                                                                                        id="rencana_id0" readonly></td>
                                                                                <td>
                                                                                    <a id="removerowproduk"><i
                                                                                            class="fas fa-minus"
                                                                                            style="color: red"></i></a>
                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <th colspan="100%" class="text-right">
                                                                                Total Harga
                                                                                <span id="totalhargaprd">
                                                                                    Rp.
                                                                                    @if (isset($e->pesanan))
                                                                                        @if (isset($e->pesanan->detailpesanan))
                                                                                            <?php $x = 0;
                                                                                            foreach ($e->pesanan->detailpesanan as $f) {
                                                                                                $x += $f->harga * $f->jumlah + $f->ongkir;
                                                                                            }
                                                                                            ?>
                                                                                            {{ number_format($x, 0, ',', '.') }}
                                                                                        @endif
                                                                                    @endif
                                                                                </span>
                                                                            </th>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-lg-11 col-md-12">
                                            <span>
                                                <a href="/penjualan/transaksi" type="button"
                                                    class="btn btn-danger">
                                                    Batal
                                                </a>
                                            </span>
                                            <span class="float-right">
                                                <button type="submit" class="btn btn-warning" id="btnsimpan">
                                                    Simpan
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('page.penjualan.penjualan.modalSeriDistributor')
    </section>
@endsection

@section('adminlte_js')
    <script>
        $(document).on('submit', '#edit_penjualan', function(e) {
            e.preventDefault();
            var action = $(this).attr('action');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: action,
                data: $(this).serialize(),
                dataType: 'JSON',
                beforeSend: function() {
                    $('#btnsimpan').attr('disabled', true);
                    $('#btnsimpan').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                success: function(response) {
                    // console.log(response)
                    swal.fire(
                        'Berhasil',
                        'Data Berhasil di Update',
                        'success'
                    ).then(function() {
                        window.location.href = '/penjualan/penjualan/edit_ekatalog/' +
                            {{ $e->id }} + '/ekatalog';
                    });
                },
                error: function(xhr, status, error, response) {
                    // console.log(response)
                    $('#btnsimpan').attr('disabled', false);
                    $('#btnsimpan').html('Simpan');
                    swal.fire(
                        'Gagal',
                        'Cek Form Kembali',
                        'error'
                    );
                }
            });
        });
        $(function() {
            // check input[type="radio"][name="status_akn"]:checked == sepakat
            if ($('input[type="radio"][name="status_akn"]:checked').val() == "sepakat") {
                $('#pills-pengiriman-tab').removeClass('disabled');
            }

            const ekspedisi = (provinsi) => {
                $('#ekspedisi').select2({
                    placeholder: "Pilih Ekspedisi",
                    ajax: {
                        minimumResultsForSearch: 20,
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/logistik/ekspedisi/select/' + provinsi,
                        data: function(params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(obj) {
                                    return {
                                        id: obj.id,
                                        text: obj.nama
                                    };
                                })
                            };
                        },
                    }
                })
            }

            const getekspedisiall = () => {
                $('#ekspedisi').select2({
                    placeholder: "Pilih Ekspedisi",
                    ajax: {
                        minimumResultsForSearch: 20,
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/logistik/ekspedisi/all',
                        data: function(params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(obj) {
                                    return {
                                        id: obj.id,
                                        text: obj.nama
                                    };
                                })
                            };
                        },
                    }
                })
            }

            $('#jenis_paket').select2();
            var nopaketdb = "{{ str_replace(['AK1-', 'FKS-', 'KLK-'], '', $e->no_paket) }}";
            var nopaketubah = false;
            var status_akn = '{{ $e->status }}';
            var jum_produk = '{{ count($e->Pesanan->DetailPesanan) }}';
            $(".os-content-arrange").remove();
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;
            // var instansi_array = [];
            var tanggal_pemesanan = $('#tgl_buat').val();

            var minBatasKontrak = tanggal_pemesanan != null || tanggal_pemesanan != undefined ? tanggal_pemesanan :
                today;


            // $("#tgl_edit").attr("max", today);
            $("#batas_kontrak").attr("min", minBatasKontrak);

            loop();
            load_variasi();

            var penjualan_produk_id = false;
            var variasi = false;
            var produk_jumlah = false;
            var produk_harga = false;
            let nama_customer = '';
            let provinsi_customer = null;
            let provinsi_instansi = $('#provinsi').val();

            let alamat_pengiriman = $('#alamat_pengiriman').val().replace(/\s/g, '');
            let alamat_customer = $('#alamat_customer').val().replace(/\s/g, '');
            let alamat_instansi = $('#alamatinstansi').val().replace(/\s/g, '');

            if (alamat_pengiriman == alamat_customer) {
                $('input[value="distributor"]').prop('checked', true);
                getekspedisiall();
            } else if (alamat_pengiriman == alamat_instansi) {
                $('input[value="instansi"]').prop('checked', true);
                // ekspedisi(provinsi_instansi)
                getekspedisiall();
            } else {
                $('input[value="lainnya"]').prop('checked', true);
                $('#perusahaan_pengiriman').attr('readonly', false);
                $('#alamat_pengiriman').attr('readonly', false);
                getekspedisiall();
            }

            function checkvalidasi() {
                $('#produktable').find('.penjualan_produk_id').each(function() {
                    if ($(this).val() != "") {
                        penjualan_produk_id = true;
                    } else {
                        penjualan_produk_id = false;
                        return false;
                    }
                });

                $('#produktable').find('.variasi').each(function() {
                    if ($(this).val() != "") {
                        variasi = true;
                    } else {
                        variasi = false;
                        return false;
                    }
                });

                $('#produktable').find('.produk_jumlah').each(function() {
                    if ($(this).val() != "") {
                        produk_jumlah = true;
                    } else {
                        produk_jumlah = false;
                        return false;
                    }
                });

                $('#produktable').find('.produk_harga').each(function() {
                    if ($(this).val() != "") {
                        produk_harga = true;
                    } else {
                        produk_harga = false;
                        return false;
                    }
                });

                if ($('input[type="radio"][name="status_akn"]:checked').val() == "sepakat") {
                    if (penjualan_produk_id == true && variasi == true && produk_jumlah == true && produk_harga ==
                        true) {
                        if ($('#no_paket').val() != "" && $('#provinsi').val() != null && $('#tgl_buat').val() !=
                            "" && $('#tgl_edit').val() != "" && $('#batas_kontrak').val() != "" && !$('#no_urut')
                            .hasClass('is-invalid') && $('#instansi').val() != "" && $('#satuan_kerja').val() !=
                            "" && $(
                                '#alamatinstansi').val() != "" && $('#deskripsi').val() != "") {
                            $("#btnsimpan").attr('disabled', false);
                        } else {
                            $("#btnsimpan").attr('disabled', true);
                        }
                    } else {
                        $("#btnsimpan").attr('disabled', true);
                    }
                } else if ($('input[type="radio"][name="status_akn"]:checked').val() == "negosiasi") {
                    if (penjualan_produk_id == true && variasi == true && produk_jumlah == true && produk_harga ==
                        true) {
                        if ($('#no_paket').val() != "" && $('#tgl_buat').val() != "" && $('#tgl_edit').val() !=
                            "" && !$('#no_urut').hasClass('is-invalid') && $('#instansi').val() != "" && $(
                                '#satuan_kerja')
                            .val() != "" && $('#alamatinstansi').val() != "" && $('#deskripsi').val() != "") {
                            $("#btnsimpan").attr('disabled', false);
                        } else {
                            $("#btnsimpan").attr('disabled', true);
                        }
                    } else {
                        $("#btnsimpan").attr('disabled', true);
                    }
                } else if ($('input[type="radio"][name="status_akn"]:checked').val() == "batal" || $(
                        'input[type="radio"][name="status_akn"]:checked').val() == "draft") {
                    if ($('input[type="checkbox"][name="isi_produk"]:checked').length > 0) {
                        if (penjualan_produk_id == true && variasi == true && produk_jumlah == true &&
                            produk_harga == true) {
                            if ($('#tgl_buat').val() != "" && !$('#no_urut').hasClass('is-invalid') && $(
                                    '#instansi').val() !=
                                "" && $('#satuan_kerja').val() != "" && $('#alamatinstansi').val() != "" && $(
                                    '#deskripsi').val() != "") {
                                $("#btnsimpan").attr('disabled', false);
                            } else {
                                $("#btnsimpan").attr('disabled', true);
                            }
                        } else {
                            $("#btnsimpan").attr('disabled', true);
                        }
                    } else {
                        if ($('#tgl_buat').val() != "" && !$('#no_urut').hasClass('is-invalid') && $('#instansi')
                            .val() != "" &&
                            $('#satuan_kerja').val() != "" && $('#alamatinstansi').val() != "" && $('#deskripsi')
                            .val() != "") {
                            $("#btnsimpan").attr('disabled', false);
                        } else {
                            $("#btnsimpan").attr('disabled', true);
                        }
                    }
                }

            }

            // function getinstansi(id){
            //     instansi_array = [];
            //     $.ajax({
            //         url: '/api/customer/get_instansi/' + id+'/'+yyyy,
            //         type: 'POST',
            //         dataType: 'json',
            //         async: false,
            //         success: function(data) {
            //             $.each(data, function( i, val ) {
            //                 instansi_array.push(val);
            //             });
            //         }
            //     });
            // }

            function perencanaan(customer_id, instansi) {
                $('#perencanaantable').DataTable({
                    searching: false,
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/penjualan/rencana/produk/' + customer_id + '/' + instansi + '/' + yyyy,
                        'dataType': 'json',
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            className: 'align-center nowrap-text',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama_produk',
                            className: 'align-center nowrap-text'
                        },
                        {
                            data: 'qty',
                            className: 'align-center nowrap-text'
                        },
                        {
                            data: 'realisasi',
                            className: 'align-center nowrap-text'
                        },
                        {
                            data: 'harga',
                            render: $.fn.dataTable.render.number('.', ',', 0, 'Rp. '),
                            className: 'align-right nowrap-text'
                        },
                        {
                            data: 'aksi',
                            className: 'align-center nowrap-text'
                        },
                    ]
                });
            }

            // getinstansi("{{ $e->customer_id }}");
            perencanaan("{{ $e->customer_id }}", "{{ $e->instansi }}");

            function loop() {
                for (i = 0; i < 20; i++) {
                    select_data(i);
                }
            }

            function cek_stok(id) {
                var jumlah = 0;
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    async: false,
                    url: '/api/produk/variasi_stok/' + id,
                    success: function(data) {
                        jumlah = data;
                    },
                    error: function(data) {
                        jumlah = data;
                    }
                });
                return jumlah;
            }

            $('#customer_id').select2({
                ajax: {
                    minimumResultsForSearch: 20,
                    placeholder: "Pilih Customer",
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/customer/select',
                    data: function(params) {
                        return {
                            term: params.term
                        }
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.nama
                                };
                            })
                        };
                    },
                }
            }).change(function() {
                var id = $(this).val();
                $.ajax({
                    url: '/api/customer/select/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        nama_customer = data[0].nama;
                        provinsi_customer = data[0].id_provinsi;
                        $('#alamat_customer').val(data[0].alamat);
                        $('#telepon_customer').val(data[0].telp);

                        if ($('input[type="radio"][name="pilihan_pengiriman"]:checked').val() ==
                            'distributor') {
                            $('#perusahaan_pengiriman').val(data[0].nama);
                            $('#alamat_pengiriman').val(data[0].alamat);
                        }
                    }
                });

                // getinstansi(id);
                perencanaan(id, document.getElementById("instansi").value);
            });

            $('.provinsi').select2({
                ajax: {
                    minimumResultsForSearch: 20,
                    placeholder: "Pilih Produk",
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/provinsi/select',
                    data: function(params) {
                        return {
                            term: params.term
                        }
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.nama
                                };
                            })
                        };
                    },
                }
            }).change(function() {
                if ($(this).val() != "") {
                    $("#msgprovinsi").text("");
                    $("#provinsi").removeClass('is-invalid');
                    $('#alamat_pengiriman').removeClass('is-invalid');
                    $('#msg_alamat_pengiriman').text('');
                } else {
                    $("#msgprovinsi").text("Provinsi harus diisi");
                    $("#provinsi").addClass('is-invalid');
                    $('#btntambah').attr("disabled", true);
                    $('#alamat_pengiriman').addClass('is-invalid');
                    $('#msg_alamat_pengiriman').text('Provinsi instansi harus diisi');
                }
            });

            fetch('/api/customer/select/' + $('#customer_id').val())
                .then(response => response.json())
                .then(data => {
                    nama_customer = data[0].nama;
                    provinsi_customer = data[0].id_provinsi;
                });

            $(document).on('change', 'input[type="radio"][name="pilihan_pengiriman"]', function() {
                let pilihan_pengiriman = $(this).val();
                let provinsi_instansi = $('#provinsi').val();
                $('#perusahaan_pengiriman').attr('readonly', true);
                $('#alamat_pengiriman').attr('readonly', true);
                $('#perusahaan_pengiriman').val('');
                // add placeholder
                $('#perusahaan_pengiriman').attr('placeholder', 'Masukkan Nama Perusahaan');
                $('#alamat_pengiriman').val('');
                $('#alamat_pengiriman').removeClass('is-invalid');
                // add placeholder
                $('#alamat_pengiriman').attr('placeholder', 'Masukkan Alamat Pengiriman');
                $('#msg_alamat_pengiriman').text('');

                const checkValidasi = (msg) => {
                    $('#alamat_pengiriman').addClass('is-invalid');
                    $('#msg_alamat_pengiriman').text(msg);
                }

                if (pilihan_pengiriman == 'distributor') {
                    $('#perusahaan_pengiriman').val(nama_customer);
                    $('#alamat_pengiriman').val($('#alamat_customer').val());
                    provinsi_customer ? ekspedisi(provinsi_customer) : checkValidasi(
                        'Provinsi Customer harus diisi');
                } else if (pilihan_pengiriman == 'instansi') {
                    $('#perusahaan_pengiriman').val($('#satuan_kerja').val());
                    $('#alamat_pengiriman').val($('#alamatinstansi').val());
                    provinsi_instansi != 'NULL' ? ekspedisi(provinsi_instansi) : checkValidasi(
                        'Provinsi Instansi harus diisi');
                } else {
                    $('#perusahaan_pengiriman').attr('readonly', false);
                    $('#alamat_pengiriman').attr('readonly', false);
                    ekspedisi(provinsi_instansi);
                }
            });

            if ('{{ $e->customer_id }}' == 484) {
                var cust_id = 'belum';
                $("#customer_id").attr('disabled', true);
                $("#customer_id").val("484").trigger('change');
                $("#alamat").val("");
                $("#telepon").val("");
            } else {
                var cust_id = 'sudah';
            }
            if ('{{ $e->provinsi_id }}' == "") {
                $('.provinsi').append($('<option>', {
                    value: 'NULL',
                    text: 'Pilih Provinsi'
                }));
            }

            if (status_akn != 'sepakat') {
                if (status_akn == 'draft' || status_akn == 'batal') {

                    if (jum_produk <= 0) {
                        $("#dataproduk").addClass("hide");
                    }
                    // $("#provinsi").attr('disabled', true);
                    // $("#provinsi").empty().trigger('change')
                    $("#batas_kontrak").attr('disabled', true);
                }
            }

            $('input[type="radio"][name="namadistributor"]').on('change', function() {
                if ($(this).val() != "") {
                    if ($(this).val() == "sudah") {
                        $("#customer_id").attr('disabled', false);
                        var $newOption = $("<option selected='selected'></option>").val("213").text(
                            "PT. EMIINDO Jaya Bersama")
                        $(".customer_id").append($newOption).trigger('change');
                    } else {
                        $("#customer_id").attr('disabled', true);
                        var $newOption = $("<option selected='selected'></option>").val("484").text(
                            "BELUM DIKETAHUI")
                        $(".customer_id").append($newOption).trigger('change');
                        $("#alamat").val("");
                        $("#telepon").val("");
                    }
                } else {
                    $("#msgstatus").text("Status Harus dipilih");
                    $("#status").addClass('is-invalid');
                    $('#btntambah').attr("disabled", true);
                }
                checkvalidasi();
            });

            $('input[name="status_akn"][value={{ $e->status }}]').attr('checked', 'checked');
            $('input[name="namadistributor"][value=' + cust_id + ']').attr('checked', 'checked');
            $('#customer_id').on('keyup change', function() {
                if ($(this).val() != "") {
                    $('#msgcustomer_id').text("");
                    $('#customer_id').removeClass('is-invalid');
                } else if ($(this).val() == "") {
                    $('#msgcustomer_id').text("Silahkan Pilih Customer");
                    $('#customer_id').addClass('is-invalid');
                }
            });

            $('input[type="radio"][name="status_akn"]').on('change', function() {
                $('#isi_produk_input').addClass('hide');

                if ($(this).val() != "") {
                    if ($(this).val() == "sepakat") {
                        $('#checkbox_nopaket').addClass('hide');
                        $('#isi_nopaket').prop("checked", false);
                        $('#no_paket').attr('readonly', true);
                        $("#dataproduk").removeClass("hide");
                        $("#batas_kontrak").attr('disabled', false);
                        $("#provinsi").attr('disabled', false);
                        // var $newOption = $("<option selected='selected'></option>").val("11").text(
                        //     "Jawa Timur")
                        // $(".provinsi").append($newOption).trigger('change');
                        if (nopaketubah == false) {
                            $('#no_paket').val(nopaketdb);
                        }
                        if (jum_produk <= 0) {
                            $("#produktable tbody").empty();
                            $('#produktablve tbody').append(trproduktable());
                        }
                        numberRowsProduk($("#produktable"));
                        $('#pills-pengiriman-tab').removeClass('disabled');
                    } else if ($(this).val() == "draft" || $(this).val() == "batal") {
                        $('#isi_produk_input').removeClass('hide');
                        $('#checkbox_nopaket').removeClass('hide');
                        // $('#no_paket').val("");
                        // $('#no_paket').attr('readonly', true);
                        $("#batas_kontrak").attr('disabled', true);
                        // $("#provinsi").attr('disabled', true);
                        // $("#provinsi").empty().trigger('change');

                        if ($('#no_paket').val() != '') {
                            $('#no_paket').attr('readonly', false);
                            $('#isi_nopaket').prop("checked", true);
                        } else {
                            $('#no_paket').attr('readonly', true);
                        }


                        if (jum_produk <= 0) {
                            $('input[type="checkbox"][name="isi_produk"]').attr('checked', false);
                            $("#produktable tbody").empty();
                            $('#produktable tbody').append(trproduktable());
                            $("#totalhargaprd").text("Rp. 0");
                            $("#dataproduk").addClass("hide");
                            $('#isi_nopaket').prop("checked", false);
                        }
                        if ($('input[type="checkbox"][name="isi_produk"]:checked').length <= 0) {
                            $("#dataproduk").addClass("hide");
                        }
                        $('#pills-pengiriman-tab').addClass('disabled');
                    } else if ($(this).val() == "negosiasi") {
                        $('#checkbox_nopaket').addClass('hide');
                        $('#isi_nopaket').prop("checked", false);
                        $('#no_paket').attr('readonly', true);
                        $("#batas_kontrak").val("");
                        $("#batas_kontrak").attr('disabled', true);
                        $("#dataproduk").removeClass("hide");
                        // $("#provinsi").attr('disabled', true);
                        // $("#provinsi").empty().trigger('change')
                        if (nopaketubah == false) {
                            $('#no_paket').val(nopaketdb);
                        }
                        if (jum_produk <= 0) {
                            $("#produktable tbody").empty();
                            $('#produktable tbody').append(trproduktable());
                        }
                        numberRowsProduk($("#produktable"));
                        $('#pills-pengiriman-tab').addClass('disabled');
                    }
                } else {
                    $('#checkbox_nopaket').addClass('hide');
                    $('#isi_nopaket').prop("checked", false);
                    $('#no_paket').attr('readonly', false);
                    $("#msgstatus").text("Status Harus dipilih");
                    $("#status").addClass('is-invalid');
                    if (nopaketubah == false) {
                        $('#no_paket').val(nopaketdb);
                    }
                }
                checkvalidasi();
            });


            $('input[type="checkbox"][name="isi_nopaket"]').change(function() {
                if ($('input[type="checkbox"][name="isi_nopaket"]:checked').length > 0) {
                    $('#no_paket').attr('readonly', false);
                    if (nopaketubah == false) {
                        $('#no_paket').val(nopaketdb);
                    }
                } else {
                    $('#no_paket').attr('readonly', true);
                    $('#no_paket').val("");
                }
            })
            $('input[type="checkbox"][name="isi_produk"]').change(function() {
                $("#produktable tbody").empty();
                $('#produktable tbody').append(trproduktable());
                numberRowsProduk($("#produktable"));
                $("#totalhargaprd").text("Rp. 0");

                if ($('input[type="checkbox"][name="isi_produk"]:checked').length > 0) {
                    $("#dataproduk").removeClass("hide");
                } else {
                    $("#dataproduk").addClass("hide");
                }
                checkvalidasi();
            });
            $(document).on('keyup', '#no_paket', function() {
                nopaketubah = true;
            })


            $('#tgl_buat').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#msgtgl_buat").text("");
                    $("#tgl_buat").removeClass('is-invalid');
                    $("#batas_kontrak").attr("min", $(this).val());
                } else if ($(this).val() == "") {
                    $("#msgtgl_buat").text("Isi Tanggal Pemesanan");
                    $("#tgl_buat").addClass('is-invalid');
                }
                checkvalidasi();
            });

            $('input[type="radio"][name="do_akn"]').on('change', function() {
                if ($(this).val() == "yes") {
                    $("#do_detail_no_akn").removeClass("hide");
                    $("#do_detail_tgl_akn").removeClass("hide");
                } else if ($(this).val() == "no") {
                    $("#do_detail_no_akn").addClass("hide");
                    $("#do_detail_tgl_akn").addClass("hide");
                }
                checkvalidasi();
            });

            $('#batas_kontrak').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#msgbatas_kontrak").text("");
                    $("#batas_kontrak").removeClass('is-invalid');
                } else if ($(this).val() == "") {
                    $("#msgbatas_kontrak").text("Batas Kontrak Harus diisi");
                    $("#batas_kontrak").addClass('is-invalid');
                }
                checkvalidasi();
            });

            $('#tgl_edit').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#msgtgl_edit").text("");
                    $("#tgl_edit").removeClass('is-invalid');
                } else if ($(this).val() == "") {
                    $("#msgtgl_edit").text("Tanggal Edit Harus diisi");
                    $("#tgl_edit").addClass('is-invalid');
                }
                checkvalidasi();
            });

            $('#pills-produk-tab').on('click', function() {
                var cust = $('#customer_id').val();
                var instansi = $('#instansi').val();
                perencanaan(cust, instansi);
                checkvalidasi();
            });

            $('input[name="instansi"]').on('keyup change', function() {
                if ($(this).val() != "") {
                    var cust = $('.customer_id').val();
                    $("#msginstansi").text("");
                    $("#instansi").removeClass('is-invalid');
                    perencanaan(cust, $(this).val());
                } else if ($(this).val() == "") {
                    $("#msginstansi").text("Instansi Harus diisi");
                    $("#instansi").addClass('is-invalid');
                }
                checkvalidasi();
            });

            $('#deskripsi').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#msgdeskripsi").text("");
                    $("#deskripsi").removeClass('is-invalid');
                } else if ($(this).val() == "") {
                    $("#msgdeskripsi").text("Deskripsi harus diisi");
                    $("#deskripsi").addClass('is-invalid');
                }
                checkvalidasi();
            });

            $('#no_urut').on('keyup change', function() {
                if ($(this).val() != "") {
                    var values = $(this).val();
                    $.ajax({
                        type: 'POST',
                        dataType: 'JSON',
                        url: '/api/penjualan/check_no_urut/' + '{{ $e->id }}' + '/' +
                            values,
                        success: function(data) {
                            if (data > 0) {
                                $("#msgno_urut").text("No Urut tidak boleh sama");
                                $("#no_urut").addClass('is-invalid');
                                $('#btntambah').attr("disabled", true);
                            } else {
                                $("#msgno_urut").text("");
                                $("#no_urut").removeClass('is-invalid');
                            }
                        },
                        error: function(data) {
                            $("#msgno_urut").text("No Urut tidak boleh sama");
                            $("#no_urut").addClass('is-invalid');
                            $('#btntambah').attr("disabled", true);
                        }
                    });
                } else if ($(this).val() == "") {
                    $("#msgno_urut").text("");
                    $("#no_urut").removeClass('is-invalid');
                }
                checkvalidasi();
            });

            $('#no_po_akn').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#msgno_po_akn").text("");
                    $("#no_po_akn").removeClass('is-invalid');
                } else if ($(this).val() == "") {
                    $("#msgno_po_akn").text("Nomor PO Harus diisi");
                    $("#no_po_akn").addClass('is-invalid');
                }
                checkvalidasi();
            });

            $('#tanggal_po_akn').on('keyup', function() {
                if ($(this).val() != "") {
                    $("#msgtanggal_po_akn").text("");
                    $("#tanggal_po_akn").removeClass('is-invalid');
                } else if ($(this).val() == "") {
                    $("#msgtanggal_po_akn").text("Tanggal PO Harus diisi");
                    $("#tanggal_po_akn").addClass('is-invalid');
                }
                checkvalidasi();
            });

            function formatmoney(bilangan) {
                var number_string = bilangan.toString(),
                    sisa = number_string.length % 3,
                    rupiah = number_string.substr(0, sisa),
                    ribuan = number_string.substr(sisa).match(/\d{3}/g);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                return rupiah;
            }

            function replaceAll(string, search, replace) {
                return string.split(search).join(replace);
            }

            function totalhargaprd() {
                var totalharga = 0;
                $('#produktable').find('tr .produk_subtotal').each(function() {
                    var subtotal = replaceAll($(this).val(), '.', '');
                    totalharga = parseInt(totalharga) + parseInt(subtotal);
                    $("#totalhargaprd").text("Rp. " + totalharga.toString().replace(
                        /(\d)(?=(\d{3})+(?!\d))/g, "$1."));
                })
            }

            function numberRowsProduk($t) {
                var c = 0 - 2;
                $t.find("tr").each(function(ind, el) {
                    $(el).find("td:eq(0)").html(++c);
                    var j = c - 1;
                    $(el).find('.penjualan_produk_id').attr('name', 'penjualan_produk_id[' + j + ']');
                    $(el).find('.penjualan_produk_id').attr('id', j);
                    var variasi = $(el).find('.variasi');
                    for (var k = 0; k < variasi.length; k++) {
                        $(el).find('select[name="variasi[' + j + '][' + k + ']"').select2();
                        $(el).find('select[data-attr="variasi' + k + '"]').attr('name', 'variasi[' + j +
                            '][' + k + ']');
                        $(el).find('select[data-attr="variasi' + k + '"]').attr('id', 'variasi' + j + '' +
                            k);
                        $(el).find('span[data-attr="ketstok' + k + '"]').attr('name', 'ketstok[' + j +
                            '][' + k + ']');
                        $(el).find('span[data-attr="ketstok' + k + '"]').attr('id', 'ketstok' + j + '' + k);
                    }
                    $(el).find('.detail_produk').attr('id', 'detail_produk' + j);
                    $(el).find('.produk_harga').attr('id', 'produk_harga' + j);
                    $(el).find('.produk_harga').attr('name', 'produk_harga[' + j + ']');
                    $(el).find('.produk_ongkir').attr('id', 'produk_ongkir' + j);
                    $(el).find('.produk_ongkir').attr('name', 'produk_ongkir[' + j + ']');
                    $(el).find('.produk_jumlah').attr('id', 'produk_jumlah' + j);
                    $(el).find('.produk_jumlah').attr('name', 'produk_jumlah[' + j + ']');
                    $(el).find('.produk_ppn').attr('id', 'produk_ppn' + j);
                    $(el).find('.produk_ppn').attr('name', 'produk_ppn[' + j + ']');
                    $(el).find('.produk_ppn_label').attr('for', 'produk_ppn' + j);
                    $(el).find('.produk_kalibrasi').attr('id', 'produk_kalibrasi' + j);
                    $(el).find('.produk_kalibrasi').attr('name', 'produk_kalibrasi[' + j + ']');
                    $(el).find('.produk_kalibrasi_label').attr('for', 'produk_kalibrasi' + j);
                    $(el).find('.produk_subtotal').attr('id', 'produk_subtotal' + j);
                    $(el).find('.produk_subtotal').attr('name', 'produk_subtotal[' + j + ']');
                    $(el).find('.stok_distributor').attr('name', 'stok_distributor[' + j + ']');
                    $(el).find('.stok_distributor').attr('id', 'stok_distributor' + j);
                    $(el).find('.stok_distributor').attr('value', j);
                    $(el).find('.noSeriDistributor').attr('name', 'noSeriDistributor[' + j + ']');
                    $(el).find('.rencana_id').attr('id', 'rencana_id' + j);
                    $(el).find('.rencana_id').attr('name', 'rencana_id[' + j + ']');
                    $(el).find('.detail_jual').attr('id', 'detail_jual' + j);
                    select_data($(el).find('.penjualan_produk_id').attr('id'));
                });
            }

            $("#produktable").on('keyup change', '.penjualan_produk_id', function() {
                var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
                var harga = $(this).closest('tr').find('.produk_harga').val();
                var ongkir = $(this).closest('tr').find('.produk_ongkir').val();
                var subtotal = $(this).closest('tr').find('.produk_subtotal');
                $(this).closest('tr').find('.rencana_id').val("");
                if (jumlah != "" && harga != "") {
                    var hargacvrt = replaceAll(harga, '.', '');
                    var ongkircvrt = replaceAll(ongkir, '.', '');
                    subtotal.val(formatmoney((jumlah * parseInt(hargacvrt)) + parseInt(ongkircvrt)));
                    totalhargaprd();
                } else {
                    subtotal.val(formatmoney("0"));
                    totalhargaprd();
                }
                checkvalidasi();
            });

            $("#produktable").on('keyup change', '.produk_jumlah', function() {
                var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
                var harga = $(this).closest('tr').find('.produk_harga').val();
                var ongkir = $(this).closest('tr').find('.produk_ongkir').val();
                var subtotal = $(this).closest('tr').find('.produk_subtotal');
                var ketstok = $(this).closest('tr').find('.ketstok');
                var variasi = $(this).closest('tr').find('.variasi');
                var ppid = $(this).closest('tr').find('.penjualan_produk_id').attr('id');
                if (jumlah != "" && harga != "") {
                    var hargacvrt = replaceAll(harga, '.', '');
                    var ongkircvrt = replaceAll(ongkir, '.', '');
                    if (ongkircvrt == "") {
                        ongkircvrt = "0";
                        $(this).closest('tr').find('.produk_ongkir').val("0");
                    }
                    subtotal.val(formatmoney((jumlah * parseInt(hargacvrt)) + parseInt(ongkircvrt)));
                    totalhargaprd();
                    for (var i = 0; i < variasi.length; i++) {
                        var variasires = $('select[name="variasi[' + ppid + '][' + i + ']"]').select2(
                            'data')[0];
                        var kebutuhan = jumlah * variasires.jumlah;
                        if (cek_stok(variasires.id) < kebutuhan) {
                            var jumlah_kekurangan = 0;
                            if (cek_stok(variasires.id) < 0) {
                                jumlah_kekurangan = kebutuhan;
                            } else {
                                jumlah_kekurangan = Math.abs(cek_stok(variasires.id) - kebutuhan);
                            }
                            $('select[name="variasi[' + ppid + '][' + i + ']"]').addClass('is-invalid');
                            $('span[name="ketstok[' + ppid + '][' + i + ']"]').text('Jumlah Kurang ' +
                                jumlah_kekurangan + ' dari Permintaan');
                        } else if (cek_stok(variasires.id) >= kebutuhan) {
                            $('select[name="variasi[' + ppid + '][' + i + ']"]').removeClass('is-invalid');
                            $('span[name="ketstok[' + ppid + '][' + i + ']"]').text('');
                        }
                    }
                } else {
                    subtotal.val(formatmoney("0"));
                    totalhargaprd();
                    variasi.removeClass('is-invalid');
                    ketstok.text('');
                }

                checkvalidasi();
            });

            $('#produktable').on('keyup change', '.variasi', function() {
                $(this).val();
                var name = $(this).attr('name');
                var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
                var ppid = $(this).closest('tr').find('.penjualan_produk_id').attr('id');
                val = $('select[name="' + name + '"]').val();
                id = $('select[name="' + name + '"]').attr('data-id');
                vals = $('select[name="' + name + '"]').select2('data')[0];
                var kebutuhan = jumlah * vals.jumlah;
                if (vals.stok < kebutuhan) {
                    var jumlah_kekurangan = 0;
                    if (vals.stok < 0) {
                        jumlah_kekurangan = kebutuhan;
                    } else {
                        jumlah_kekurangan = Math.abs(vals.stok - kebutuhan);
                    }
                    $('select[name="variasi[' + ppid + '][' + id + ']"]').addClass('is-invalid');
                    $('span[name="ketstok[' + ppid + '][' + id + ']"]').text('Jumlah Kurang ' +
                        jumlah_kekurangan + ' dari Permintaan');
                } else if (vals.stok >= kebutuhan) {
                    $('select[name="variasi[' + ppid + '][' + id + ']"]').removeClass('is-invalid');
                    $('span[name="ketstok[' + ppid + '][' + id + ']"]').text('');
                }
                checkvalidasi();
            });

            $("#produktable").on('keyup change', '.produk_harga', function() {
                var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                $(this).val(result);
                var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
                var harga = $(this).closest('tr').find('.produk_harga').val();
                var ongkir = $(this).closest('tr').find('.produk_ongkir').val();
                var subtotal = $(this).closest('tr').find('.produk_subtotal');
                if (jumlah != "" && harga != "") {
                    var hargacvrt = replaceAll(harga, '.', '');
                    var ongkircvrt = replaceAll(ongkir, '.', '');
                    if (ongkircvrt == "") {
                        ongkircvrt = "0";
                        $(this).closest('tr').find('.produk_ongkir').val("0");
                    }
                    subtotal.val(formatmoney((jumlah * parseInt(hargacvrt)) + parseInt(ongkircvrt)));
                    totalhargaprd();
                } else {
                    subtotal.val(formatmoney("0"));
                    totalhargaprd();
                }

                checkvalidasi();
            });

            $("#produktable").on('keyup change', '.produk_ongkir', function() {
                var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                $(this).val(result);
                var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
                var harga = $(this).closest('tr').find('.produk_harga').val();
                var ongkir = $(this).closest('tr').find('.produk_ongkir').val();
                var subtotal = $(this).closest('tr').find('.produk_subtotal');
                if (jumlah != "" && harga != "") {
                    var hargacvrt = replaceAll(harga, '.', '');
                    var ongkircvrt = replaceAll(ongkir, '.', '');
                    subtotal.val(formatmoney((jumlah * parseInt(hargacvrt)) + parseInt(ongkircvrt)));
                    totalhargaprd();
                } else {
                    subtotal.val(formatmoney("0"));
                    totalhargaprd();
                }
                checkvalidasi();
            });

            function trproduktable() {
                var data = `<tr>
                    <td></td>
                    <td>
                        <div class="form-group select_item">
                            <select name="penjualan_produk_id[]" id="0" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                                <option value=""></option>
                            </select>
                            <div class="detailjual" id="tes0">
                            </div>
                        </div>
                        <div id="detail_produk" class="detail_produk"></div>
                    </td>
                    <td>
                        <div class="form-group d-flex justify-content-center">
                            <div class="input-group">
                                <input type="number" class="form-control produk_jumlah" aria-label="produk_satuan" name="produk_jumlah[]" id="produk_jumlah" style="width:100%;">
                            </div>
                            <small id="produk_ketersediaan"></small>
                        </div>
                    </td>
                    <td>
                        <div class="form-group d-flex justify-content-center">
                            <input type="text" class="form-control produk_harga" name="produk_harga[]" id="produk_harga0" placeholder="Masukkan Harga" style="width:100%;"/>
                        </div>
                    </td>
                    <td>
                        <div class="form-group d-flex justify-content-center">
                            <input type="text" class="form-control produk_ongkir" name="produk_ongkir[]" id="produk_ongkir0" placeholder="Masukkan Harga" style="width:100%;"/>
                        </div>
                    </td>
                    <td>
                        <div class="form-group d-flex justify-content-center">
                            <input type="text" class="form-control produk_subtotal" name="produk_subtotal[]" id="produk_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" readonly/>
                        </div>
                    </td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input custom-control-input-pajak produk_ppn" id="produk_ppn0" name="produk_ppn[]" value="1" checked>
                            <label class="custom-control-label custom-control-label-pajak produk_ppn_label" for="produk_ppn0">PPN</label>
                        </div>
                    </td>
                    <td>
                    <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input-kalibrasi custom-control-input produk_kalibrasi" id="produk_kalibrasi0" name="produk_kalibrasi[0]" value="0" disabled>
                            <label class="custom-control-label-kalibrasi custom-control-label produk_kalibrasi_label" for="produk_kalibrasi0">Tidak</label>
                            </div>
                    </td>

                    <td>
                        <div
                            class="form-group d-flex align-items-center">
                            <input type="checkbox"
                                class="stok_distributor"
                                name="stok_distributor[0]"
                                id="stok_distributor0"
                                value="0"
                                style="width:100%;" />
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary btnNoSeri" hidden>No Seri</button>
                        <input type="hidden" name="noSeriDistributor[0]" class="noSeriDistributor">
                    </td>
                    <td hidden><input type="hidden" class="rencana_id" name="rencana_id[]" id="rencana_id0" readonly></td>
                    <td>
                        <a id="removerowproduk"><i class="fas fa-minus" style="color: red;"></i></a>
                    </td>
                </tr>`;
                return data;
            }

            $('#addrowproduk').on('click', function() {
                if ($('#produktable > tbody > tr').length <= 0) {
                    $('#produktable tbody').append(trproduktable());
                    numberRowsProduk($("#produktable"));
                } else {
                    $('#produktable tbody tr:last').after(trproduktable());
                    numberRowsProduk($("#produktable"));
                }
                checkvalidasi();
            });

            $('#produktable').on('click', '#removerowproduk', function(e) {
                $(this).closest('tr').remove();
                numberRowsProduk($("#produktable"));
                totalhargaprd();
                if ($('#produktable > tbody > tr').length <= 0) {
                    $('#produktable tbody').append(trproduktable());
                    numberRowsProduk($("#produktable"));
                    $("#totalhargaprd").text("Rp. 0");
                }

                checkvalidasi();
            });

            function select_data(i) {
                $('#' + i).select2({
                    placeholder: "Pilih Produk",
                    ajax: {
                        minimumResultsForSearch: 20,
                        dataType: 'json',

                        delay: 250,
                        type: 'GET',
                        url: '/api/penjualan_produk/select_param/ekatalog',
                        data: function(params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(obj) {
                                    return {
                                        id: obj.id,
                                        text: obj.nama
                                    };
                                })
                            };
                        },
                    }
                }).change(function(i) {
                    var index = $(this).attr('id');
                    var id = $(this).val();
                    $.ajax({
                        url: '/api/penjualan_produk/select/' + id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(res) {
                            $('#produk_harga' + index).val(formatmoney(res[0].harga));
                            $('#produk_subtotal' + index).val(formatmoney(res[0].harga * $(
                                '#produk_jumlah' + index).val()));
                            $('#rencana_id' + index).val("");
                            totalhargaprd();
                            var tes = $('#detail_produk' + index);
                            var produkKalibrasi = $('#produk_kalibrasi' + index);
                            tes.empty();
                            var datas = "";
                            tes.append(`<fieldset><legend><b>Detail Produk</b></legend>`);
                            for (var x = 0; x < res[0].produk.length; x++) {
                                if (res[0].is_kalibrasi == true) {
                                    produkKalibrasi.attr('disabled', false);
                                    produkKalibrasi.attr('data-kalibrasi', true);
                                } else {
                                    produkKalibrasi.attr('data-kalibrasi', 'false');
                                    produkKalibrasi.attr('disabled', true);
                                    produkKalibrasi.prop('checked', false);
                                    produkKalibrasi.val("0");
                                    var labelElement = $("label[for='" + produkKalibrasi.attr(
                                            'id') +
                                        "']");
                                    var label = labelElement.text('Tidak');
                                }
                                var data = [];
                                tes.append(`<div>`);
                                tes.append(`<div class="card-body blue-bg">
                                            <h6>` + res[0].produk[x].nama + `</h6>
                                            <select class="form-control variasi" name="variasi[` + index + `][` + x +
                                    `]" id="variasi` + index + `` + x +
                                    `" style="width:100%;" data-attr="variasi` + x +
                                    `" data-id="` + x + `"></select>
                                            <span class="invalid-feedback d-block ketstok" name="ketstok[` + index +
                                    `][` + x + `]" id="ketstok` + index + `` + x +
                                    `" data-attr="ketstok` + x + `" data-id="` + x + `"></span>
                                <small class="text-muted">Jumlah barang dipinjamkan: 10</small>
                                        </div>`);
                                for (var y = 0; y < res[0].produk[x].gudang_barang_jadi
                                    .length; y++) {
                                    var nama_var = "";
                                    if (res[0].produk[x].gudang_barang_jadi[y].nama.trim() !=
                                        "") {
                                        nama_var = res[0].produk[x].gudang_barang_jadi[y].nama;
                                    } else {
                                        nama_var = res[0].produk[x].nama;
                                    }
                                    data.push({
                                        id: res[0].produk[x].gudang_barang_jadi[y].id,
                                        text: nama_var,
                                        jumlah: res[0].produk[x].pivot.jumlah,
                                        qt: res[0].produk[x]
                                            .gudang_barang_jadi[y].stok
                                    });
                                }

                                $(`select[name="variasi[` + index + `][` + x + `]"]`).select2({
                                    placeholder: 'Pilih Variasi',
                                    data: data,
                                    templateResult: function(data) {
                                        var $span = $(
                                            `<div><span class="col-form-label">` +
                                            data.text +
                                            `</span><span class="badge blue-text float-right col-form-label stok" data-id="` +
                                            data.qt + `">` + data.qt +
                                            `</span></div>`);
                                        return $span;
                                    },
                                    templateSelection: function(data) {
                                        var $span = $(
                                            `<div><span class="col-form-label">` +
                                            data.text +
                                            `</span><span class="badge blue-text float-right col-form-label stok" data-id="` +
                                            data.qt + `">` + data.qt +
                                            `</span></div>`);
                                        return $span;
                                    }
                                });

                                $(`select[name="variasi[` + index + `][` + x + `]"]`).trigger(
                                    "change");
                                tes.append(`</div>`)
                            }
                            tes.append(`</fieldset>`);
                        }
                    });
                    checkvalidasi();
                });
            }

            function load_variasi() {
                produk = [];
                produk = <?php echo json_encode($item); ?>;
                // console.log(produk)
                if (produk.length > 0) {
                    for (var w = 0; w < produk.length; w++) {
                        $.ajax({
                            url: '/api/penjualan_produk/select/' + produk[w]['penjualan_produk_id'],
                            type: 'GET',
                            dataType: 'json',
                            async: false,
                            success: function(res) {
                                for (var x = 0; x < res[0].produk.length; x++) {
                                    var data = [];
                                    for (var y = 0; y < res[0].produk[x].gudang_barang_jadi
                                        .length; y++) {
                                        var nama_var = "";
                                        if (res[0].produk[x].gudang_barang_jadi[y].nama.trim() != "") {
                                            nama_var = res[0].produk[x].gudang_barang_jadi[y].nama;
                                        } else {
                                            nama_var = res[0].produk[x].nama;
                                        }
                                        data.push({
                                            id: res[0].produk[x].gudang_barang_jadi[y].id,
                                            text: nama_var,
                                            jumlah: res[0].produk[x].pivot.jumlah,
                                            qt: res[0].produk[x].gudang_barang_jadi[y]
                                                .stok
                                        });
                                    }

                                    $('select[name="variasi[' + w + '][' + x + ']"]').select2({
                                        placeholder: 'Pilih Variasi',
                                        data: data,
                                        templateResult: function(data) {
                                            var $span = $(
                                                `<div><span class="col-form-label">` +
                                                data.text +
                                                `</span><span class="badge blue-text float-right col-form-label stok" data-id="` +
                                                data.qt + `">` + data.qt +
                                                `</span></div>`);
                                            return $span;
                                        },
                                        templateSelection: function(data) {
                                            var $span = $(
                                                `<div><span class="col-form-label">` +
                                                data.text +
                                                `</span><span class="badge blue-text float-right col-form-label stok" data-id="` +
                                                data.qt + `">` + data.qt +
                                                `</span></div>`);
                                            return $span;
                                        }
                                    });

                                    $('select[name="variasi[' + w + '][' + x + ']"]').trigger("change");
                                }
                            }
                        });
                    }
                }

            }

            $("#alamatinstansi").autocomplete({
                source: function(request, response) {

                    $.ajax({
                        dataType: 'json',
                        url: "/api/penjualan/check_alamat",
                        data: {
                            term: request.term
                        },

                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el.alamat,
                                    id: el.id
                                };
                            });
                            response(transformed);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                }
            });

            $("#instansi").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        dataType: 'json',
                        url: '/api/customer/get_instansi/' + $('#customer_id').val() + '/' +
                            yyyy,
                        data: {
                            term: request.term
                        },
                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el,
                                };
                            });
                            response(transformed);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                }
            });

            $("#satuan_kerja").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        dataType: 'json',
                        url: "/api/ekatalog/all_satuan",
                        data: {
                            term: request.term
                        },
                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el.satuan,
                                    id: el.id
                                };
                            });
                            response(transformed);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                }
            });

            $("#deskripsi").autocomplete({
                source: function(request, response) {

                    $.ajax({
                        dataType: 'json',
                        url: "/api/ekatalog/all_deskripsi",
                        data: {
                            term: request.term
                        },

                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el.deskripsi,
                                    id: el.id
                                };
                            });
                            response(transformed);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                }
            });

            $('#perencanaantable').on('click', '#btntransfer', function() {
                var id = $(this).closest('tr').find('#btntransfer').attr('data-id');
                var nama_produk = $(this).closest('tr').find('#btntransfer').attr('data-nama_produk');
                var produk_id = $(this).closest('tr').find('#btntransfer').attr('data-produk');
                var jumlah = $(this).closest('tr').find('#btntransfer').attr('data-jumlah');
                var harga = $(this).closest('tr').find('#btntransfer').attr('data-harga');
                transferproduk(id, nama_produk, produk_id, jumlah, harga);
            });

            $('input[type="radio"][name="do_ekat"]').on('change', function() {
                $('#btntambah').attr("disabled", true);
                $("#no_do_ekat").val("");
                $("#tanggal_do_ekat").val("");
                if ($(this).val() == "yes") {
                    $("#do_detail_no_ekat").removeClass("hide");
                    $("#do_detail_tgl_ekat").removeClass("hide");
                } else if ($(this).val() == "no") {
                    $("#do_detail_no_ekat").addClass("hide");
                    $("#do_detail_tgl_ekat").addClass("hide");
                }
            });

            function transferproduk(id, nama_produk, produk_id, jumlah, harga) {
                var data = `<tr>
                        <td></td>
                        <td>
                            <div class="form-group select_item">
                                <select name="penjualan_produk_id[]" id="0" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                                    <option value="` + produk_id + `">` + nama_produk +
                    `</option>
                                </select>
                                <div class="detailjual" id="tes0">
                                </div>
                            </div>
                            <div id="detail_produk" class="detail_produk"></div>
                        </td>
                        <td>
                            <div class="form-group d-flex justify-content-center">
                                <div class="input-group">
                                    <input type="number" class="form-control produk_jumlah" aria-label="produk_satuan" name="produk_jumlah[]" id="produk_jumlah0" value="` +
                    jumlah +
                    `" style="width:100%;">

                                </div>
                                <small id="produk_ketersediaan"></small>
                            </div>
                        </td>
                        <td>
                            <div class="form-group d-flex justify-content-center">

                                <input type="text" class="form-control produk_harga" name="produk_harga[]" id="produk_harga0" value="` +
                    formatmoney(harga) +
                    `" placeholder="Masukkan Harga" style="width:100%;"/>
                            </div>
                        </td>
                        <td>
                            <div class="form-group d-flex justify-content-center">

                                <input type="text" class="form-control produk_ongkir" name="produk_ongkir[]" id="produk_ongkir0" placeholder="Masukkan Ongkir" style="width:100%;"/>
                            </div>
                        </td>
                        <td>
                            <div class="form-group d-flex justify-content-center">
                                <input type="text" class="form-control produk_subtotal" name="produk_subtotal[]" id="produk_subtotal0" placeholder="Masukkan Subtotal" value="` +
                    formatmoney(jumlah * parseInt(harga)) +
                    `" style="width:100%;" readonly/>
                            </div>
                        </td>
                        <td>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input produk_ppn" id="produk_ppn0" name="produk_ppn[]" value="1" checked>
                                <label class="custom-control-label produk_ppn_label" for="produk_ppn0">PPN</label>
                            </div>
                        </td>
                        <td>
                            <div
                                class="form-group d-flex align-items-center">
                                <input type="checkbox"
                                    class="stok_distributor"
                                    name="stok_distributor[0]"
                                    id="stok_distributor0"
                                    value="0"
                                    style="width:100%;" />
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary btnNoSeri" hidden>No Seri</button>
                            <input type="hidden" name="noSeriDistributor[0]" class="noSeriDistributor">
                        </td>
                        <td hidden><input type="hidden" class="rencana_id" name="rencana_id[]" id="rencana_id0" readonly value="` +
                    id + `"></td>
                        <td>
                            <a id="removerowproduk"><i class="fas fa-minus" style="color: red;"></i></a>
                        </td>
                    </tr>`;

                if ($('#produktable > tbody > tr').length <= 0) {
                    $('#produktable tbody').append(data);
                    select_data();
                    numberRowsProduk($("#produktable"));
                    totalhargaprd();
                } else {
                    $('#produktable tbody tr:last').after(data);
                    select_data();
                    numberRowsProduk($("#produktable"));
                    totalhargaprd();
                }
                var index = $('#produktable > tbody > tr').length - 1;
                $.ajax({
                    url: '/api/penjualan_produk/select/' + produk_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        var tes = $('#detail_produk' + index);
                        tes.empty();
                        var datas = "";
                        tes.append(`<fieldset><legend><b>Detail Produk</b></legend>`);
                        for (var x = 0; x < res[0].produk.length; x++) {
                            var data = [];
                            tes.append(`<div>`);
                            tes.append(`<div class="card-body blue-bg">
                                            <h6>` + res[0].produk[x].nama + `</h6>
                                            <select class="form-control variasi" name="variasi[` + index + `][` + x +
                                `]" style="width:100%;" id="variasi` + index + `` + x +
                                `" data-attr="variasi` + x + `" data-id="` + x + `"></select>
                                            <span class="invalid-feedback d-block ketstok" name="ketstok[` + index +
                                `][` + x + `]" id="ketstok` + index + `` + x +
                                `" data-attr="ketstok` + x + `" data-id="` + x + `"></span>
                                <small class="text-muted">Jumlah barang dipinjamkan: 10</small>
                                        </div>`);

                            for (var y = 0; y < res[0].produk[x].gudang_barang_jadi.length; y++) {
                                var nama_var = "";
                                if (res[0].produk[x].gudang_barang_jadi[y].nama.trim() != "") {
                                    nama_var = res[0].produk[x].gudang_barang_jadi[y].nama;
                                } else {
                                    nama_var = res[0].produk[x].nama;
                                }
                                data.push({
                                    id: res[0].produk[x].gudang_barang_jadi[y].id,
                                    text: nama_var,
                                    jumlah: res[0].produk[x].pivot.jumlah,
                                    qt: res[0].produk[x].gudang_barang_jadi[y].stok
                                });
                            }

                            $(`select[name="variasi[` + index + `][` + x + `]"]`).select2({
                                placeholder: 'Pilih Variasi',
                                data: data,
                                templateResult: function(data) {
                                    var $span = $(`<div><span class="col-form-label">` +
                                        data.text +
                                        `</span><span class="badge blue-text float-right col-form-label stok" data-id="` +
                                        data.qt + `">` + data.qt + `</span></div>`);
                                    return $span;
                                },
                                templateSelection: function(data) {
                                    var $span = $(`<div><span class="col-form-label">` +
                                        data.text +
                                        `</span><span class="badge blue-text float-right col-form-label stok" data-id="` +
                                        data.qt + `">` + data.qt + `</span></div>`);
                                    return $span;
                                }
                            });

                            $(`select[name="variasi[` + index + `][` + x + `]"]`).trigger("change");
                            tes.append(`</div>`)
                        }
                        tes.append(`</fieldset>`);
                        // tes.html(datas);
                    }
                });
            }

            $(document).on('change', '.custom-control-input-pajak', function() {
                var labelElement = $(this).closest('tr').find('.custom-control-label-pajak')
                var label = labelElement.text();
                // not checked

                if ($(this).val() == '0') {
                    $(this).val('1');
                    // change label text
                    label = label.replace('Non PPN', 'PPN');
                } else {
                    $(this).val('0');
                    // change label text
                    label = label.replace('PPN', 'Non PPN');
                }
                labelElement.text(label);
            });

            $(document).on('change', '.custom-control-input-kalibrasi', function() {
                var labelElement = $(this).closest('tr').find('.custom-control-label-kalibrasi')
                var label = labelElement.text();
                // not checked

                if ($(this).val() == '0') {
                    $(this).val('1');
                    // change label text
                    label = label.replace('Tidak', 'Ya');
                } else {
                    $(this).val('0');
                    // change label text
                    label = label.replace('Ya', 'Tidak');
                }
                labelElement.text(label);
            });

            $('.checkAllDistributor').click(function() {

                if ($(this).is(':checked')) {
                    let kalibrasi = $('.custom-control-input-kalibrasi');
                    let labelKalibrasi = $('.custom-control-label-kalibrasi');
                    $('.stok_distributor').prop('checked', true);

                    kalibrasi.prop('checked', false);
                    kalibrasi.val('0');
                    labelKalibrasi.text('Tidak');
                    kalibrasi.prop('disabled', true);
                    // show all button
                    $('.btnNoSeri').prop('hidden', false);
                } else {
                    let kalibrasi = $('#produktable').find('.custom-control-input-kalibrasi');
                    kalibrasi.each(function() {
                        if ($(this).attr('data-kalibrasi') == 'true') {
                            $(this).attr('disabled', false)
                        }
                    });
                    $('.stok_distributor').prop('checked', false);
                    // hide all button
                    $('.btnNoSeri').prop('hidden', true);
                }
            });

            $(document).on('click', '.stok_distributor', function() {
                // check if checkbox is checked
                let kalibrasi = $(this).closest('tr').find('.custom-control-input-kalibrasi');
                let labelKalibrasi = $(this).closest('tr').find('.custom-control-label-kalibrasi');
                if ($(this).is(':checked')) {
                    // check if all checkboxes are selected find button hidden false
                    kalibrasi.prop('checked', false);
                    kalibrasi.val('0');
                    kalibrasi.attr('disabled', true)
                    labelKalibrasi.text('Tidak');
                    $(this).closest('td').find('button').prop('hidden', false);
                } else {
                    // check if all checkboxes are selected find button hidden true
                    $(this).closest('td').find('button').prop('hidden', true);
                    if (kalibrasi.attr('data-kalibrasi') == 'true') {
                        kalibrasi.attr('disabled', false)
                        console.log('kalibrasi', kalibrasi.attr('data-kalibrasi'))
                    }
                }
            });


            $(document).on('click', '.btnNoSeri', function() {
                let indexDistributor = $(this).closest('tr').index();
                $('.indexSeriDistributor').val(indexDistributor);
                let jumlah = $('.produk_jumlah').eq(indexDistributor).val();
                $('.jumlahSeriDistributor').val(jumlah);
                // find index by indexDistributor class noSeriDistributor
                let noSeri = $('.noSeriDistributor').eq(indexDistributor).val();
                $('.indexSeriDistributor').val(indexDistributor);
                // change array to string with comma
                let noSeriArray = noSeri.split(',');
                // remove empty string
                noSeriArray = noSeriArray.filter(function(el) {
                    return el != '';
                });
                // set value to input
                $('.nomorSeriDistributor').val(noSeriArray);
                // open modal Distributor
                $('.modalDistributor').modal('show');

            })
        });
    </script>
@stop
