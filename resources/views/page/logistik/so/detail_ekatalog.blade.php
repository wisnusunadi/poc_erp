@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Sales Order Logistik</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->divisi_id == '15')
                        <li class="breadcrumb-item"><a href="{{ route('logistik.dashboard') }}">Beranda</a></li>
                    @elseif(Auth::user()->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                    @endif
                    <li class="breadcrumb-item"><a href="/logistik/so">Sales Order</a></li>
                    <li class="breadcrumb-item active">Detail</li>

                </ol>
            </div>
        </div>
    </div>
@stop

@section('adminlte_css')
    <style>
        .select-style {
            top: 50% !important;
            margin-top: 10px !important;
        }

        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }

        .ok {
            color: green;
            font-weight: 600;
        }

        .urgent {
            color: #dc3545;
            font-weight: 600;
        }

        .warning {
            color: #FFC700;
            font-weight: 600;
        }

        .info {
            color: #3a7bb0;
        }


        .list-group-item {
            border: 0 none;
        }

        .align-right {
            text-align: right;
        }

        .align-center {
            text-align: center;
        }

        .margin {
            margin-bottom: 5px;
        }

        .filter {
            margin: 5px;
        }

        .hide {
            display: none !important;
        }

        .bgcolor {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }

        .fa-search:hover {
            color: #ADD8E6;
        }

        .fa-search:active {
            color: #808080;
        }

        .nowrap-text {
            white-space: nowrap;
        }

        .removeboxshadow {
            box-shadow: none;
            border: 1px;
        }

        @media screen and (min-width: 1440px) {
            section {
                font-size: 14px;
            }

            .dropdown-item {
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

        @media screen and (max-width: 1439px) {
            section {
                font-size: 12px;
            }

            .dropdown-item {
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

        @media screen and (max-width: 992px) {
            .labelket {
                text-align: left;
            }

            .align-md {
                text-align: center;
            }
        }
    </style>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4>Info Penjualan</h4>
                            @if ($value == 'EKAT')
                                <div class="row">
                                    <div class="col-lg-11 col-md-12">
                                        <div class="row d-flex justify-content-between">
                                            <div class="p-2 cust">
                                                <div class="margin">
                                                    <div><small class="text-muted">Distributor & Instansi</small></div>
                                                </div>
                                                <div class="margin">
                                                    <b id="distributor">{{ $data->customer->nama }}</b><small>
                                                        (Distributor)</small>
                                                </div>
                                                <div class="margin">
                                                    <div><b id="no_akn">{{ $data->satuan }}</b></div>
                                                </div>
                                                <div class="margin">
                                                    <b id="distributor">{{ $data->alamat }}</b>
                                                </div>
                                                <div class="margin">
                                                    <b id="distributor">{{ $data->Provinsi->nama }}</b>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No AKN</small></div>
                                                    <div><b id="no_akn">{{ $data->no_paket }}</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">No SO</small></div>
                                                    <div><b id="no_so">{{ $data->pesanan->so }}</b></div>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No PO</small></div>
                                                    <div><b id="no_so">{{ $data->pesanan->no_po }}</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Batas Pengiriman</small></div>
                                                    <div><b>{!! $tgl_pengiriman !!}</b></div>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">Status</small></div>
                                                    <div class="align-center">{!! $status !!}</div>
                                                </div>
                                                {{-- <div class="margin">
                                                    <button class="btn btn-outline-success">
                                                        <i class="far fa-file-excel"></i>
                                                        Export
                                                    </button>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($value == 'SPA' || $value == 'SPB')
                                <div class="row">
                                    <div class="col-lg-11 col-md-12">
                                        <div class="row  d-flex justify-content-between">
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">Customer</small></div>
                                                </div>
                                                <div class="margin">
                                                    <b id="distributor">{{ $data->customer->nama }}</b>
                                                </div>
                                                <div class="margin">
                                                    <div><b id="no_akn">{{ $data->customer->alamat }}</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><b id="no_akn">{{ $data->customer->Provinsi->nama }}</b></div>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No SO</small></div>
                                                    <div><b id="no_so">{{ $data->pesanan->so }}</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Status</small></div>
                                                    <div class="align-center">{!! $status !!}</div>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No PO</small></div>
                                                    <div><b id="no_so">{{ $data->pesanan->no_po }}</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Tanggal PO</small></div>
                                                    <div><b>{{ date('d-m-Y', strtotime($data->pesanan->tgl_po)) }}</b>
                                                    </div>
                                                </div>
                                                {{-- <div class="margin">
                                                    <button class="btn btn-outline-success">
                                                        <i class="far fa-file-excel"></i>
                                                        Export
                                                    </button>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if ($data->ket != '')
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Catatan: </strong>{{ $data->ket }}
                </div>
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">


                            <div class="row">
                                <div class="col-12">
                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        @if ($proses == 'proses')
                                            <li class="nav-item">
                                                <a class="nav-link active" id="pills-belum_kirim-tab" data-toggle="pill"
                                                    href="#pills-belum_kirim" role="tab"
                                                    aria-controls="pills-belum_kirim" aria-selected="true">Belum Kirim</a>
                                            </li>
                                        @endif
                                        <li class="nav-item">
                                            <a class="nav-link @if ($proses == 'selesai') active @endif"
                                                id="pills-selesai_kirim-tab" data-toggle="pill"
                                                href="#pills-selesai_kirim" role="tab"
                                                aria-controls="pills-selesai_kirim"
                                                @if ($proses == 'selesai') aria-selected="true" @else aria-selected="false" @endif>Sudah
                                                Kirim</a>
                                        </li>
                                        @if ($proses == 'selesai')
                                            <li class="nav-item">
                                                <a class="nav-link" id="pills-surat_jalan-tab" data-toggle="pill"
                                                    href="#pills-surat_jalan" role="tab"
                                                    aria-controls="pills-surat_jalan" aria-selected="false">Surat
                                                    Jalan</a>
                                            </li>
                                        @endif
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">

                                        <div class="tab-pane fade  @if ($proses == 'proses') show active @endif"
                                            id="pills-belum_kirim" role="tabpanel"
                                            aria-labelledby="pills-belum_kirim-tab">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <div class="card removeboxshadow">
                                                                <div class="card-header">
                                                                    <div class="card-title">Produk</div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            @if (Auth::user()->divisi_id == '15')
                                                                                <a data-toggle="modal"
                                                                                    data-target="#editmodal"
                                                                                    class="editmodal" data-attr=""
                                                                                    data-id="">
                                                                                    <span class="float-right filter">
                                                                                        <button class="btn btn-primary"
                                                                                            type="button"
                                                                                            id="kirim_produk"
                                                                                            disabled="true"><i
                                                                                                class="fas fa-plus"></i>
                                                                                            Pengiriman</button>
                                                                                    </span>
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="table-responsive">
                                                                                <table class="table "
                                                                                    style="text-align:center; width:100%;"
                                                                                    id="belumkirimtable">

                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th rowspan="2"
                                                                                                width="8%"
                                                                                                class="align-middle">
                                                                                                <div class="form-check">
                                                                                                    <input
                                                                                                        class="form-check-input"
                                                                                                        type="checkbox"
                                                                                                        value="check_all"
                                                                                                        id="check_all"
                                                                                                        name="check_all" />
                                                                                                    <label
                                                                                                        class="form-check-label"
                                                                                                        for="check_all"></label>
                                                                                                </div>
                                                                                            </th>
                                                                                            <th rowspan="2"
                                                                                                width="8%">No </th>
                                                                                            <th rowspan="2"
                                                                                                width="40%">Nama Produk
                                                                                            </th>
                                                                                            <th colspan="2"
                                                                                                width="30%">Jumlah</th>
                                                                                            <th rowspan="2">Array Check
                                                                                            </th>
                                                                                            <th rowspan="2"
                                                                                                width="14%">Aksi</th>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th width="10%">Diterima
                                                                                            </th>
                                                                                            <th width="20%">Dikirim</th>
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
                                                        <div class="col-5 hide" id="noseridetail">
                                                            <div class="card removeboxshadow">
                                                                <div class="card-header">
                                                                    <div class="d-flex bd-highlight">
                                                                        <div class="p-2 flex-grow-1 bd-highlight">No Seri
                                                                        </div>
                                                                        <div class="p-2 bd-highlight">
                                                                            <button class="btn btn-primary"
                                                                                data-toggle="modal"
                                                                                data-target="#modalautocheckbox">Pilih
                                                                                Nomor Seri Via Text</button>
                                                                        </div>
                                                                    </div>
                                                                    @include('page.logistik.so.modalautocheckbox')
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="table-responsive">
                                                                        <table
                                                                            class="table table-hover table-striped align-center"
                                                                            id="noseritable">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>
                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox"
                                                                                                value="check_all_noseri"
                                                                                                id="check_all_noseri"
                                                                                                name="check_all_noseri" />
                                                                                            <label class="form-check-label"
                                                                                                for="check_all_noseri">
                                                                                            </label>
                                                                                        </div>
                                                                                    </th>
                                                                                    <th>No Seri</th>
                                                                                    <th>Aksi</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody></tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade @if ($proses == 'selesai') show active @endif"
                                            id="pills-selesai_kirim" role="tabpanel"
                                            aria-labelledby="pills-selesai_kirim-tab">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="table-responsive">
                                                                <table class="table"
                                                                    style="text-align:center; width:100%;"
                                                                    id="selesaikirimtable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No</th>
                                                                            <th>Surat Jalan</th>
                                                                            <th>Tanggal Kirim</th>
                                                                            <th>Pengirim / Ekspedisi</th>
                                                                            <th>Nama Produk</th>
                                                                            <th>Jumlah</th>
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

                                        @if ($proses == 'selesai')
                                            <div class="tab-pane fade" id="pills-surat_jalan" role="tabpanel"
                                                aria-labelledby="pills-surat_jalan-tab">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover table-striped align-center"
                                                                id="sjtable" style="width:100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No</th>
                                                                        <th>Surat Jalan</th>
                                                                        <th>Tgl Kirim</th>
                                                                        <th>Resi</th>
                                                                        <th>Ekspedisi / Pengiriman</th>
                                                                        <th>Status</th>
                                                                        <th>Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="editmodal" role="dialog" aria-labelledby="editmodal" aria-hidden="true"
                    data-backdrop="static">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content" style="margin: 10px">
                            <div class="modal-header bg-info">
                                <h5 class="modal-title">Tambah Pengiriman</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="edit">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="detailmodal" role="dialog" aria-labelledby="detailmodal"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content" style="margin: 10px">
                            <div class="modal-header">
                                <h5 class="modal-title">Detail Logistik</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="detail">

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    @include('page/gbj/modalserireworks/detailnoseri')

@stop
@section('adminlte_js')
    <script>
        $(function() {
            var provinsi = <?php if ($value == 'EKAT') {
                echo $data->Provinsi->id;
            } else {
                echo $data->Customer->Provinsi->id;
            } ?>;
            var divisi_id = "{{ Auth::user()->divisi_id }}";
            var jenis_penjualan = "{{ $value }}";
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;

            y = [];
            y = <?php echo json_encode($detail_id); ?>;

            var sjtable = $('#sjtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/logistik/so/data/sj/' + '{{ $data->pesanan_id }}',
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
                }, {
                    data: 'nosurat',
                }, {
                    data: 'tgl_kirim',
                }, {
                    data: 'noresi',
                }, {
                    data: 'ekspedisi_id',
                }, {
                    data: 'status_id',
                }, {
                    data: 'aksi',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                }]
            });

            var belumkirimtable = $('#belumkirimtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: false,
                ajax: {
                    'url': '/api/logistik/so/data/detail/belum_kirim/' + '{{ $data->pesanan_id }}' +
                        "/" +
                        jenis_penjualan,
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
                        data: 'checkbox',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false,
                        visible: divisi_id == 15 ? true : false
                    },
                    {
                        data: 'DT_RowIndex',
                        className: 'align-center nowrap-text align-middle',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_produk',
                        className: 'align-center align-middle',
                    },
                    {
                        data: 'jumlah',
                        className: 'nowrap-text align-center align-middle',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'dikirim',
                        className: 'nowrap-text align-center align-middle',
                        orderable: false,
                        searchable: false,
                        visible: divisi_id == 15 ? true : false
                    }, {
                        data: 'array_check',
                        className: ''
                    },
                    {
                        data: 'button',
                        className: 'nowrap-text align-center align-middle',
                        orderable: false,
                        searchable: false,
                    }
                ],

            });

            var selesaikirimtable = $('#selesaikirimtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/logistik/so/data/detail/selesai_kirim/' + '{{ $data->pesanan_id }}' +
                        '/' + jenis_penjualan,
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
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'no',
                }, {
                    data: 'tgl_kirim',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'pengirim',
                }, {
                    data: 'nama_produk',
                }, {
                    data: 'jumlah',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'button',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false,
                }]
            });

            function validasi() {
                if (($('input[type="radio"][name="no_sj_exist"]:checked').val() == "baru" && ($('#no_invoice')
                            .val() != "" && !$('#no_invoice').hasClass('is-invalid')) && $('#tgl_kirim').val() !=
                        "" &&
                        ((($('input[type="radio"][name="pengiriman"]:checked').val() == "nonekspedisi" && $(
                                '#nama_pengirim').val() != "") ||
                            ($('input[type="radio"][name="pengiriman"]:checked').val() == "ekspedisi" && $(
                                '#ekspedisi_id').val() != "")))) || ($(
                            'input[type="radio"][name="no_sj_exist"]:checked').val() == "lama" && $("#sj_lama")
                        .val() != "")) {
                    $('#btnsimpan').attr('disabled', false);
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            }

            function validasi_checked_produk() {
                var rows = $('#belumkirimtable').DataTable().rows().nodes();
                if ($('.check_detail:checked', rows).length <= 0) {
                    $('#check_all').prop('checked', false);
                    $('#kirim_produk').attr('disabled', true);
                } else {
                    $('#kirim_produk').attr('disabled', false);
                }
            }

            $(document).on('click', '#belumkirimtable .noserishow', function() {
                var array = $(this).closest('tr').find('div[name="array_check[]"]').text();
                if (array == "") {
                    array = "0";
                }
                var data = $(this).attr('data-id');
                idtrf = <?php
                if (!isset($data->pesanan->TFProduksi->id)) {
                    echo 0;
                } else {
                    echo $data->pesanan->TFProduksi->id;
                } ?>;
                $('#belumkirimtable').find('tr').removeClass('bgcolor');
                $(this).closest('tr').addClass('bgcolor');
                $('#noseridetail').removeClass('hide');
                var jumlahtransfer = $(this).closest('tr').find('#jumlah_transfer').text();
                var jumlahkirim = $(this).closest('tr').find('.jumlah_kirim').val();
                if (jumlahtransfer == jumlahkirim) {
                    $('input[name="check_all_noseri"]').prop('checked', true);
                } else {
                    $('input[name="check_all_noseri"]').prop('checked', false);
                }
                noseritable(data, array);
            })

            $('#selesaikirimtable').on('click', '.detailmodal', function() {

                var data = $(this).attr('data-id');
                $('#selesaikirimtable').find('tr').removeClass('bgcolor');
                $(this).closest('tr').addClass('bgcolor');

                $.ajax({
                    url: "/api/logistik/so/noseri/detail/selesai_kirim/" + data,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#detailmodal').modal("show");
                        $('#detail').html(result).show();
                        showtable(data);
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        alert("Page cannot open. Error:" + error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            })

            $(document).on('submit', '#form-logistik-create', function(e) {
                e.preventDefault();
                var produk_no_seri = $('#detailpesanan').DataTable().$('tr').find(
                    'input[name="produk_noseri_id[]"]').serializeArray();
                var data_produk_no_seri = [];

                var produk_id = $('#detailpesanan').DataTable().$('tr').find('input[name="produk_id[]"]')
                    .serializeArray();
                var data_produk_id = [];

                var part_id = $('#detailpesanan').DataTable().$('tr').find('input[name="part_id[]"]')
                    .serializeArray();
                var data_part_id = [];

                var part_jumlah = $('#detailpesanan').DataTable().$('tr').find(
                    'input[name="part_jumlah[]"]').serializeArray();
                var data_part_jumlah = [];

                var no_sj_exist = $('input[name="no_sj_exist"]:checked').val();
                var jenis_sj = $('#jenis_sj').val();
                var no_invoice = $('#no_invoice').val();
                var sj_lama = $('#sj_lama').val();
                var tgl_kirim = $('#tgl_kirim').val();
                var pengiriman = $('input[name="pengiriman"]:checked').val();

                var ekspedisi_id = $('#ekspedisi_id').val();
                var nama_pengirim = $('#nama_pengirim').val();

                var nama_pic = $('#nama_pic').val();
                var telp_pic = $('#telp_pic').val();
                var ekspedisi_terusan = $('#ekspedisi_terusan').val();
                var pilihan_pengiriman = $('input[name="pilihan_pengiriman"]:checked').val();
                var perusahaan_pengiriman = $('#perusahaan_pengiriman').val();
                var alamat_pengiriman = $('#alamat_pengiriman').val();
                var kemasan = $('input[name="kemasan"]:checked').val();
                var dimensi = $('#dimensi').val();
                var keterangan_pengiriman = $('#keterangan_pengiriman').val();


                $.each(produk_no_seri, function() {
                    data_produk_no_seri.push(this.value);
                });

                $.each(produk_id, function() {
                    data_produk_id.push(this.value);
                });

                $.each(part_jumlah, function() {
                    data_part_jumlah.push(this.value);
                });


                $.each(part_id, function() {
                    data_part_id.push(this.value);
                });

                $('#btnsimpan').attr('disabled', true);
                var action = $(this).attr('action');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "PUT",
                    url: action,
                    data: {
                        no_sj_exist: no_sj_exist,
                        jenis_sj: jenis_sj,
                        no_invoice: no_invoice,
                        sj_lama: sj_lama,
                        tgl_kirim: tgl_kirim,
                        pengiriman: pengiriman,
                        ekspedisi_id: ekspedisi_id,
                        nama_pengirim: nama_pengirim,
                        produk_no_seri: data_produk_no_seri,
                        produk_id: data_produk_id,
                        part_id: data_part_id,
                        part_jumlah: data_part_jumlah,
                        nama_pic,
                        telp_pic,
                        no_sj_exist,
                        ekspedisi_terusan,
                        pilihan_pengiriman,
                        perusahaan_pengiriman,
                        alamat_pengiriman,
                        kemasan,
                        dimensi,
                        keterangan_pengiriman
                    },
                    // beforeSend: function() {
                    //     swal.fire({
                    //         title: 'Silahkan Tunggu, Jangan di tutup atau di refresh',
                    //         html: 'Loading...',
                    //         allowOutsideClick: false,
                    //         showConfirmButton: false,
                    //         willOpen: () => {
                    //             Swal.showLoading()
                    //         }
                    //     })
                    // },
                    success: function(response) {
                        if (response['data'] == "success") {
                            swal.fire(
                                'Berhasil',
                                'Berhasil menambahkan Pengiriman',
                                'success'
                            ).then(function() {
                                location.reload(true);
                            });
                            $("#editmodal").modal('hide');
                            $('#belumkirimtable').DataTable().ajax.reload();
                            $('#selesaikirimtable').DataTable().ajax.reload();
                            $('#noseridetail').addClass('hide');
                            $('#check_all').prop('checked', false);

                        } else if (response['data'] == "error") {
                            swal.fire(
                                'Gagal',
                                'Gagal menambahkan Pengiriman',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error, response) {
                        alert(error);
                    }
                });
                return false;
            });

            function detailpesanan(produk_id, part_id, pesanan_id) {
                $('#detailpesanan').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        // 'url': '/api/logistik/so/detail/select/' + produk_id + '/' + part_id + '/' +
                        //     pesanan_id + '/' + jenis_penjualan,
                        'url': '/api/logistik/so/detail/select/' + pesanan_id + '/' + jenis_penjualan,
                        'data': {
                            'produk_id': produk_id,
                            'part_id': part_id
                        },
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
                            className: 'nowrap-text align-center',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama_produk',
                        },
                        {
                            data: 'jumlah',
                            className: 'nowrap-text align-center',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'array',
                            className: 'nowrap-text align-center',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            }

            const header = (pesanan_id) => {
                $.ajax({
                    url: "/api/logistik/so/data/detail/item/" + pesanan_id,
                    'dataType': 'json',
                    success: function(data) {
                        console.log(data)
                        if (data.header.perusahaan_pengiriman && data.header.alamat_pengiriman) {
                            // pilihan pengiriman == penjualan
                            $('#pilihan_pengiriman0').prop('checked', true)
                            $('#perusahaan_pengiriman').attr('readonly', true);
                            $('#alamat_pengiriman').attr('readonly', true);
                            $('input[name="perusahaan_pengiriman"]').val(data.header
                                .perusahaan_pengiriman)
                            $('input[name="alamat_pengiriman"]').val(data.header.alamat_pengiriman)
                        } else {
                            $('#pilihan_pengiriman1').prop('checked', true)
                            $('input[name="perusahaan_pengiriman"]').val('')
                            $('input[name="alamat_pengiriman"]').val('')
                            $('#perusahaan_pengiriman').attr('readonly', false);
                            $('#alamat_pengiriman').attr('readonly', false);
                        }

                        if (data.header.kemasan == 'peti') {
                            $('input[name="kemasan"]').val('peti').prop('checked', true)
                        } else {
                            $('input[name="kemasan"]').val('nonpeti').prop('checked', true)
                        }

                        if (data.header.ekspedisi) {
                            $('#pengiriman1').prop('checked', true)
                            $('#ekspedisi').removeClass('hide')
                            $('#nonekspedisi').addClass('hide')

                            let selectElement = $('.ekspedisi_id');
                            let option = $('<option>', {
                                value: data.header.ekspedisi.id,
                                text: data.header.ekspedisi.nama
                            })
                            selectElement.empty()
                            selectElement.append(option)
                            selectElement.val(data.header.ekspedisi.id)
                        } else {
                            $('#pengiriman2').prop('checked', true)
                            $('#ekspedisi').addClass('hide')
                            $('#nonekspedisi').removeClass('hide')
                            let selectElement = $('.ekspedisi_id');
                            selectElement.empty()
                        }
                    }
                });
            }

            function noseritable(id, array) {
                $('#noseritable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    autowidth: true,
                    ajax: {
                        'url': '/api/logistik/so/noseri/detail/belum_kirim/' + id + '/' + array,
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
                            data: 'checkbox',
                            orderable: false,
                            searchable: false,
                            visible: divisi_id == 15 ? true : false
                        },
                        {
                            data: 'no_seri'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                if (data.cek_rw == 1) {
                                    return `
                                    <button class="btn btn-sm btn-outline-info buttonNoSeriDetail">
                                        <i class="fa fa-info-circle"></i>
                                        Detail No. Seri Produk
                                    </button> &nbsp;
                                `
                                } else {
                                    return ''
                                }
                            }
                        }
                    ]
                });
            }

            function showtable(id) {
                $('#showtable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/logistik/so/noseri/detail/selesai_kirim/data/' + id,
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
                            className: 'nowrap-text align-center',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'no_seri'
                        }
                    ]
                })
            }

            produk_id = [];
            part_id = [];

            $('#belumkirimtable').on('click change', 'input[name="check_all"]', function() {
                var rows = $('#belumkirimtable').DataTable().rows().nodes();

                if ($('input[name="check_all"]:checked').length > 0) {
                    $('.jumlah_kirim').prop('disabled', true);
                    $('.check_detail:not(:disabled)', rows).prop('checked', true);
                    $('#kirim_produk').removeAttr('disabled');
                } else if ($('input[name="check_all"]:checked').length <= 0) {
                    $('.jumlah_kirim').prop('disabled', false);
                    $('.check_detail', rows).prop('checked', false);
                    $('#kirim_produk').prop('disabled', true);
                }

                produk_id = [];
                $.each($(".detail_produk_id:checked", rows), function() {
                    produk_id_arr = {};
                    produk_id_arr.id = $(this).closest('tr').find('.detail_produk_id').attr(
                        'data-id');
                    produk_id_arr.jumlah_kirim = $(this).closest('tr').find('.jumlah_kirim').val();
                    produk_id_arr.array_no_seri = $(this).closest('tr').find(
                        'div[name="array_check[]"]').text();
                    produk_id.push(produk_id_arr);
                });
                part_id = [];
                $.each($(".detail_part_id:checked", rows), function() {
                    part_id_arr = {};
                    part_id_arr.id = $(this).closest('tr').find('.detail_part_id').attr('data-id');
                    part_id_arr.jumlah_kirim = $(this).closest('tr').find('.jumlah_kirim').val();
                    part_id.push(part_id_arr);
                });
                validasi_checked_produk();
            });

            $(document).on('click change', '#noseritable input[name="check_all_noseri"]', function() {
                var rows = $('#noseritable').DataTable().rows().nodes();

                if ($('input[name="check_all_noseri"]:checked').length > 0) {
                    $('.check_noseri', rows).prop('checked', true);
                    $('#belumkirimtable > tbody > tr.bgcolor').find('.jumlah_kirim').removeClass(
                        'is-invalid');
                    $('#belumkirimtable > tbody > tr.bgcolor').find('.check_detail').attr(
                        'disabled', false);

                } else if ($('input[name="check_all_noseri"]:checked').length <= 0) {
                    $('#belumkirimtable > tbody > tr.bgcolor').find('.jumlah_kirim').removeClass(
                        'is-invalid');
                    $('.check_noseri', rows).prop('checked', false);
                    $('#belumkirimtable > tbody > tr.bgcolor').find('.jumlah_kirim').addClass('is-invalid');
                    $('#belumkirimtable > tbody > tr.bgcolor').find('.check_detail').attr(
                        'disabled', true);
                    $('#belumkirimtable > tbody > tr.bgcolor').find('.check_detail').prop(
                        'checked', false);
                }
                checkedAry = [];
                $.each($(".check_noseri:checked", rows), function() {
                    checkedAry.push($(this).closest('tr').find('.check_noseri').attr('data-id'));
                });
                $('#belumkirimtable > tbody > tr.bgcolor').find('div[name="array_check[]"]').text(
                    checkedAry);
                $('#belumkirimtable > tbody > tr.bgcolor').find('.jumlah_kirim').val($(
                        '.check_noseri:checked', rows)
                    .length);
                validasi_checked_produk();
            });


            $('#belumkirimtable').on('click', '.check_detail', function() {
                $(this).closest('tr').find('.jumlah_kirim').prop('disabled', false);
                $('#check_all').prop('checked', false);

                $.each($(".detail_part_id:checked"), function() {
                    $(this).closest('tr').find('.jumlah_kirim').prop('disabled', true);
                });

                if ($('.check_detail').is(':checked')) {
                    $('#kirim_produk').removeAttr('disabled');
                } else {
                    $('#kirim_produk').prop('disabled', true);
                }
                validasi_checked_produk();
            })

            $('#noseritable').on('change ', '.check_noseri', function() {
                $('input[name="check_all_noseri"]:checked').prop('checked', false);
                var rows = $('#noseritable').DataTable().rows().nodes();
                var text = $('#belumkirimtable > tbody > tr.bgcolor').find('div[name="array_check[]"]')
                    .text();
                var array_noseri_produk = [];
                if (text != "") {
                    array_noseri_produk = text.split(',');
                }
                if ($('.check_noseri:checked', rows).length > 0) {

                    if ($(this).is(':checked')) {

                        array_noseri_produk.push($(this).closest('tr').find('.check_noseri').attr(
                            'data-id'));
                        $('#belumkirimtable > tbody > tr.bgcolor').find('div[name="array_check[]"]').text(
                            array_noseri_produk);
                    } else {
                        const index = array_noseri_produk.indexOf($(this).closest('tr').find(
                            '.check_noseri').attr(
                            'data-id'));
                        if (index > -1) {
                            array_noseri_produk.splice(index,
                                1); // 2nd parameter means remove one item only
                        }
                        $('#belumkirimtable > tbody > tr.bgcolor').find('div[name="array_check[]"]').text(
                            array_noseri_produk);
                    }
                    $('#belumkirimtable > tbody > tr.bgcolor').find('.jumlah_kirim').removeClass(
                        'is-invalid');
                    $('#belumkirimtable > tbody > tr.bgcolor').find('.check_detail').attr('disabled',
                        false);
                } else {
                    const index = array_noseri_produk.indexOf($(this).closest('tr').find('.check_noseri')
                        .attr('data-id'));
                    if (index > -1) {
                        array_noseri_produk.splice(index, 1); // 2nd parameter means remove one item only
                    }
                    $('#belumkirimtable > tbody > tr.bgcolor').find('div[name="array_check[]"]').text(
                        array_noseri_produk);
                    $('#belumkirimtable > tbody > tr.bgcolor').find('.jumlah_kirim').addClass('is-invalid');
                    $('#belumkirimtable > tbody > tr.bgcolor').find('.check_detail').attr('disabled', true);
                    $('#belumkirimtable > tbody > tr.bgcolor').find('.check_detail').prop('checked', false);
                }
                $('#belumkirimtable > tbody > tr.bgcolor').find('.jumlah_kirim').val($(
                    '.check_noseri:checked', rows).length);
                validasi_checked_produk();


            });

            $(document).on('change keyup', '#belumkirimtable .jumlah_kirim', function() {
                var jumlah_dikirim = parseInt($(this).closest('tr').find('input[name="jumlah_dikirim[]"]')
                    .val());
                var jumlah_transfer = parseInt($(this).closest('tr').find('#jumlah_transfer').text());
                var produk_ids = $(this).closest('tr').find('.detail_produk_id').attr('data-id');
                if (jumlah_dikirim <= jumlah_transfer && jumlah_dikirim != 0) {
                    $(this).closest('tr').find('input[name="jumlah_dikirim[]"]').removeClass('is-invalid');
                    $(this).closest('tr').find('.check_detail').attr('disabled', false);

                    var this_table = $(this).closest('tr');
                    $.ajax({
                        url: "/api/logistik/so/noseri_array/" + produk_ids + '/' + jumlah_dikirim,
                        type: 'GET',
                        dataType: 'json',
                        success: function(result) {
                            this_table.find('div[name="array_check[]"]').text(result);
                            if (this_table.hasClass('bgcolor')) {
                                $('#noseritable').DataTable().ajax.url(
                                    '/api/logistik/so/noseri/detail/belum_kirim/' +
                                    produk_ids + '/' + result).load();
                            }
                        },
                        error: function(jqXHR, testStatus, error) {
                            console.log(error);
                            alert("Page " + href + " cannot open. Error:" + error);
                            $('#loader').hide();
                        },
                        timeout: 8000
                    });
                } else {
                    $(this).closest('tr').find('input[name="jumlah_dikirim[]"]').addClass('is-invalid');
                    $('#check_all').prop('checked', false);
                    $(this).closest('tr').find('.check_detail').prop('checked', false);
                    $(this).closest('tr').find('.check_detail').attr('disabled', true);
                    $(this).closest('tr').find('div[name="array_check[]"]').text("");
                    if ($(this).closest('tr').hasClass('bgcolor')) {
                        $('#noseritable').DataTable().ajax.url(
                            '/api/logistik/so/noseri/detail/belum_kirim/' + produk_ids + '/0').load();
                    }
                }
                validasi_checked_produk();
            });

            function ekspedisi_select(id) {
                $('.ekspedisi_id').select2({
                    ajax: {
                        minimumResultsForSearch: 20,
                        placeholder: "Pilih Ekspedisi",
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/logistik/ekspedisi/select/' + id,
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
                        $('#ekspedisi_id').removeClass('is-invalid');
                        $('#msgekspedisi_id').text("");
                    } else if ($(this).val() == "") {
                        $('#ekspedisi_id').addClass('is-invalid');
                        $('#msgekspedisi_id').text("No Kendaraan harus diisi");
                    }
                    var value = $(this).val();
                    validasi();
                });
            }

            function max_date() {
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0');
                var yyyy = today.getFullYear();
                today = yyyy + '-' + mm + '-' + dd;
                $("#tgl_kirim").attr("max", today);
            }

            $(document).on('click', '.editmodal', function(event) {
                event.preventDefault();
                var href = $(this).attr('data-attr');
                var id = $(this).data('id');
                var pesanan_id = '{{ $data->pesanan_id }}';
                if ($('.detail_produk_id:checked').length > 0) {
                    //tambahan
                    if ($('.detail_part_id:checked').length <= 0) {
                        part_id.splice(0, part_id.length)
                    }
                    produk_id = [];
                    $.each($(".detail_produk_id:checked"), function() {
                        var produk_id_arr = {};
                        produk_id_arr.id = $(this).closest('tr').find('.detail_produk_id').attr(
                            'data-id');
                        produk_id_arr.jumlah_kirim = $(this).closest('tr').find('.jumlah_kirim')
                            .val();
                        produk_id_arr.array_no_seri = $(this).closest('tr').find(
                            'div[name="array_check[]"]').text();
                        produk_id.push(produk_id_arr);
                    });

                } else if ($('.detail_produk_id:checked').length <= 0) {
                    var produk_id_arr = {};
                    produk_id_arr.id = '0';
                    produk_id_arr.jumlah_kirim = '0';
                    produk_id_arr.array_no_seri = '0';
                    produk_id.push(produk_id_arr);
                }

                if ($('.detail_part_id:checked').length > 0) {
                    //tambahan
                    if ($('.detail_produk_id:checked').length <= 0) {
                        produk_id.splice(0, produk_id.length)
                    }
                    part_id = [];
                    $.each($(".detail_part_id:checked"), function() {
                        var part_id_arr = {};
                        part_id_arr.id = $(this).closest('tr').find('.detail_part_id').attr(
                            'data-id');
                        part_id_arr.jumlah_kirim = $(this).closest('tr').find('.jumlah_kirim')
                            .val();
                        part_id_arr.array_no_seri = '0';
                        part_id.push(part_id_arr);
                    });
                } else if ($('.detail_part_id:checked').length <= 0) {
                    var part_id_arr = {};
                    part_id_arr.id = '0';
                    part_id_arr.jumlah_kirim = '0';
                    part_id_arr.array_no_seri = '0';
                    part_id.push(part_id_arr);
                }


                if (produk_id.length <= 0) {
                    var produk_id_arr = {}
                    produk_id_arr.id = "0";
                    produk_id_arr.jumlah_kirim = "0";
                    produk_id_arr.array_no_seri = "0";
                    produk_id.push(produk_id_arr);
                }
                if (part_id.length <= 0) {
                    var part_id_arr = {}
                    part_id_arr.id = "0";
                    part_id_arr.jumlah_kirim = "0";
                    part_id_arr.array_no_seri = '0';
                    part_id.push(part_id_arr);
                }
                $.ajax({
                    url: "/logistik/so/create/" + jenis_penjualan,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        $('#editmodal').modal("show");
                        $('#edit').html(result).show();
                        select_sj_lama();
                        detailpesanan(produk_id, part_id, pesanan_id);
                        header(pesanan_id);

                        $('.jenis_sj').select2({
                            minimumResultsForSearch: -1
                        });
                        ekspedisi_select(provinsi);
                        $('#tgl_kirim').attr('max', today);
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        alert("Page " + href + " cannot open. Error:" + error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            });

            $(document).on('change', 'input[type="radio"][name="pengiriman"]', function(event) {
                $('#ekspedisi_id').removeClass('is-invalid');
                $('#msgekspedisi_id').text("");
                $('#nama_pengirim').removeClass('is-invalid');
                $('#msgnama_pengirim').text("");

                if ($(this).val() == "ekspedisi") {
                    $('#ekspedisi').removeClass('hide');
                    $('#nonekspedisi').addClass('hide');
                    $('#nama_pengirim').val("");
                } else if ($(this).val() == "nonekspedisi") {
                    $('#ekspedisi').addClass('hide');
                    $('#nonekspedisi').removeClass('hide');
                    $('.ekspedisi_id').val(null).trigger("change");
                }
                validasi();
            });

            $(document).on('change', 'input[type="radio"][name="pilihan_pengiriman"]', function() {
                let pilihan_pengiriman = $(this).val();
                $('#perusahaan_pengiriman').attr('readonly', true);
                $('#alamat_pengiriman').attr('readonly', true);
                // add placeholder
                $('#perusahaan_pengiriman').attr('placeholder', 'Masukkan Nama Perusahaan');
                $('#alamat_pengiriman').removeClass('is-invalid');
                // add placeholder
                $('#alamat_pengiriman').attr('placeholder', 'Masukkan Alamat Pengiriman');

                if (pilihan_pengiriman == 'lainnya') {
                    $('#perusahaan_pengiriman').attr('readonly', false);
                    $('#alamat_pengiriman').attr('readonly', false);
                }
                validasi();
            });

            function check_no_sj(val) {
                var hasil = "";
                $.ajax({
                    type: "POST",
                    url: '/api/logistik/cek/no_sj/0/' + val,
                    dataType: 'json',
                    success: function(data) {
                        hasil = data;
                    },
                    error: function() {
                        alert('Error occured');
                    }
                });
                return hasil;
            }

            $(document).on('change', '#jenis_sj', function(event) {
                if ($(this).val() != "") {
                    var kode = $(this).val();
                    var val = $('#no_invoice').val();
                    if (val != "") {
                        var value = kode + val;
                        $.ajax({
                            type: "POST",
                            url: '/api/logistik/cek/no_sj/0/' + value,
                            dataType: 'json',
                            success: function(data) {
                                if (data > 0) {
                                    $('#no_invoice').addClass('is-invalid');
                                    $('#msgnoinvoice').text("No Surat Jalan sudah terpakai");
                                } else {
                                    $('#no_invoice').removeClass('is-invalid');
                                    $('#msgnoinvoice').text("");
                                }
                                validasi();
                            },
                            error: function() {
                                alert('Error occured');
                            }
                        });
                    }
                }
            });

            $(document).on('change keyup', '#no_invoice', function(event) {
                if ($(this).val() != "") {
                    var kode = $('#jenis_sj').val();
                    var val = $(this).val();
                    var value = kode + val;
                    $.ajax({
                        type: "POST",
                        url: '/api/logistik/cek/no_sj/0/' + value,
                        dataType: 'json',
                        success: function(data) {
                            if (data > 0) {
                                $('#no_invoice').addClass('is-invalid');
                                $('#msgnoinvoice').text("No Surat Jalan sudah terpakai");
                            } else {
                                $('#no_invoice').removeClass('is-invalid');
                                $('#msgnoinvoice').text("");
                            }
                        },
                        error: function() {
                            alert('Error occured');
                        }
                    });
                } else if ($(this).val() == "") {
                    $('#no_invoice').addClass('is-invalid');
                    $('#msgnoinvoice').text("No Surat Jalan tidak boleh kosong");
                }
                validasi();
            });

            $(document).on('change keyup', '#tgl_kirim', function(event) {
                if ($(this).val() != "") {
                    $('#tgl_kirim').removeClass('is-invalid');
                    $('#msgtgl_kirim').text("");
                } else if ($(this).val() == "") {
                    $('#tgl_kirim').addClass('is-invalid');
                    $('#msgtgl_kirim').text("Tanggal Kirim harus diisi");
                }
                validasi();
            });

            $(document).on('change keyup', '#nama_pengirim', function(event) {
                if ($(this).val() != "") {
                    $('#nama_pengirim').removeClass('is-invalid');
                    $('#msgnama_pengirim').text("");
                } else if ($(this).val() == "") {
                    $('#nama_pengirim').addClass('is-invalid');
                    $('#msgnama_pengirim').text("Nama Pengirim harus diisi");
                }
                validasi();
            });

            $(document).on('change', 'input[type="radio"][name="no_sj_exist"]', function() {
                $("#sj_lama").val(null).trigger("change");
                $(".ekspedisi_id").val(null).trigger("change");
                $('#tgl_kirim').val('');
                $('#nama_pengirim').val('');
                $('#no_invoice').val('');
                $('input[name="pengiriman"]').removeAttr('checked');
                $('#ekspedisi_nama').text('');
                if ($(this).val() == "baru") {
                    $('#sj_baru').removeClass('hide');
                    $('.sj_lamas').addClass('hide');

                    $('#tgl_kirim').attr('disabled', false);
                    $('#nama_pengirim').attr('disabled', false);
                    $('.ekspedisi_id').removeAttr('disabled');
                    $('.ekspedisi_id').next(".select2-container").show();
                    $('#ekspedisi_nama').addClass('hide');
                    $('input[name="pengiriman"]').removeAttr('disabled');
                    $('input[name="nama_pic"]').removeAttr('disabled');
                    $('input[name="telp_pic"]').removeAttr('disabled');
                    $('textarea[name="ekspedisi_terusan"]').removeAttr('disabled');
                    $('input[name="pilihan_pengiriman"]').removeAttr('disabled');
                    $('input[name="perusahaan_pengiriman"]').removeAttr('disabled');
                    $('input[name="alamat_pengiriman"]').removeAttr('disabled');
                    $('input[name="kemasan"]').removeAttr('disabled');
                    $('textarea[name="dimensi"]').removeAttr('disabled');
                    $('select[name="keterangan_pengiriman"]').removeAttr('disabled');
                } else if ($(this).val() == "lama") {
                    $('#sj_baru').addClass('hide');
                    $('.sj_lamas').removeClass('hide');

                    $('#tgl_kirim').attr('disabled', true);
                    $('#nama_pengirim').attr('disabled', true);
                    $('.ekspedisi_id').attr('disabled', 'disabled');
                    $('.ekspedisi_id').next(".select2-container").hide();
                    $('#ekspedisi_nama').removeClass('hide');
                    $('input[name="pengiriman"]').attr('disabled', true);
                    $('input[name="pengiriman"]').attr('disabled', true);
                    $('input[name="nama_pic"]').attr('disabled', true);
                    $('input[name="telp_pic"]').attr('disabled', true);
                    $('textarea[name="ekspedisi_terusan"]').attr('disabled', true);
                    $('input[name="pilihan_pengiriman"]').attr('disabled', true);
                    $('input[name="perusahaan_pengiriman"]').attr('disabled', true);
                    $('input[name="alamat_pengiriman"]').attr('disabled', true);
                    $('input[name="kemasan"]').attr('disabled', true);
                    $('textarea[name="dimensi"]').attr('disabled', true);
                    $('select[name="keterangan_pengiriman"]').attr('disabled', true);
                }
                validasi();
            });

            function select_sj_lama() {
                $('#sj_lama').select2({
                    ajax: {
                        minimumResultsForSearch: 20,
                        placeholder: "Pilih No Surat Jalan",
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/logistik/cek/no_sj_belum_kirim/' +
                            '{{ str_replace('/', '!', $data->pesanan->no_po) }}',
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
                                        text: obj.nosurat
                                    };
                                })
                            };
                        },
                    }
                }).change(function() {
                    if ($(this).val() != "") {
                        $('#ekspedisi').addClass('hide');
                        $('#nonekspedisi').addClass('hide');
                        $('input[name="pengiriman"]').removeAttr('checked');
                        $.ajax({
                            type: "GET",
                            url: '/api/logistik/cek/no_sj_detail/' + $(this).val(),
                            dataType: 'json',
                            success: function(data) {
                                $('#tgl_kirim').val(data[0]['tgl_kirim']);
                                if (data[0]['ekspedisi_id'] !== "") {
                                    $('input[name="pengiriman"][value="ekspedisi"]').prop(
                                        "checked", "checked");
                                    $('#ekspedisi').removeClass('hide');
                                    $('.ekspedisi_id').addClass('hide');
                                    $('#ekspedisi_nama').removeClass('hide');
                                    $("#ekspedisi_nama").text(data[0]['ekspedisi']['nama']);
                                } else {
                                    $('input[name="pengiriman"][value="nonekspedisi"]').prop(
                                        "checked", "checked");
                                    $('#nonekspedisi').removeClass('hide');
                                    $('#nama_pengirim').val(data[0]['nama_pengirim']);
                                }
                            },
                            error: function(data) {
                                alert('Error occured');
                            }
                        });
                    }
                    validasi();
                });
            }

            $(document).on('click', '.buttonNoSeriDetail', function() {
                var table = $('#noseritable').DataTable();
                var data = table.row($(this).closest('tr')).data();
                var index = table.row($(this).closest('tr')).index();
                const dateIndo = (date) => {
                    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                    ];
                    const d = new Date(date);
                    return `${d.getDate()} ${monthNames[d.getMonth()]} ${d.getFullYear()}`;
                }

                $('#nomor-seri-reworks').html(data.noseri);
                $('#tgl-dibuat-reworks').html(dateIndo(data.created_at));
                $('#packer-reworks').html(data.packer);
                $('.tableprodukreworks').DataTable().clear().destroy();

                let dataJson = data.item;

                $('.tableprodukreworks').DataTable({
                    data: dataJson,
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    ordering: false,
                    autoWidth: false,
                    columns: [{
                            data: null,
                            // buat index
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return data.produk + ' ' + data.varian;
                            }
                        },
                        {
                            data: 'noseri',
                        }
                    ]
                });
                $('.modalDetailNoSeri').modal('show');
            });
        })
    </script>
@stop
