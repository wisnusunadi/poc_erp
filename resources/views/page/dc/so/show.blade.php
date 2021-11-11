@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Sales Order</h1>
@stop

@section('adminlte_css')
<style>
    .urgent {
        color: #dc3545;
        font-weight: 600;
    }

    .warning {
        color: #FFC700;
        font-weight: 600;
    }

    .info {
        color: #FFC700;
        font-weight: 600;
    }

    .filter {
        margin: 5px;
    }
</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <span class="float-right filter">
                                    <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <div class="dropdown-menu">
                                        <div class="px-3 py-3">
                                            <div class="form-group">
                                                <label for="jenis_penjualan">Jenis Penjualan</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="ekatalog" id="defaultCheck1" />
                                                    <label class="form-check-label" for="defaultCheck1">
                                                        E-Catalogue
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="spa" id="defaultCheck2" />
                                                    <label class="form-check-label" for="defaultCheck2">
                                                        SPA
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="spa" id="defaultCheck2" />
                                                    <label class="form-check-label" for="defaultCheck2">
                                                        SPB
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <span class="float-right">
                                                    <button class="btn btn-primary">
                                                        Cari
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table" style="text-align:center;" id="showtable">
                                        <thead>
                                            <th>No</th>
                                            <th>No SO</th>
                                            <th>No AKN</th>
                                            <th>Batas Kontrak</th>
                                            <th>Distibutor</th>
                                            <th>Customer</th>
                                            <th>Keterangan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>SO/EKAT/X/02/98</td>
                                                <td>AK1-909090-1892180</td>
                                                <td>31-10-2021</td>
                                                <td>CV. Cipta Jaya Mandiri</td>
                                                <td>Pemerintah Kota Gorontalo</td>
                                                <td>-</td>
                                                <td><span class="badge yellow-text">Sebagian Diproses</span></td>
                                                <td><a href="/dc/so/detail/1">
                                                        <i class="fas fa-search"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>SO/EKAT/X/02/100</td>
                                                <td>AK1-909090-1892180</td>
                                                <td>
                                                    <div class="urgent">31-10-2021</div>
                                                    <small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Kontrak</small>
                                                </td>
                                                <td>CV. Cipta Jaya Mandiri</td>
                                                <td>Rumah Sakit Santo Paulus</td>
                                                <td>-</td>
                                                <td><span class="badge red-text">Belum Diproses</span></td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="" data-id="1">
                                                        <i class="fas fa-search"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>SO/SPA/XI/02/01</td>
                                                <td>AK1-909090-1892180</td>
                                                <td>
                                                    <div class="warning">04-11-2021</div>
                                                    <small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa 2 Hari</small>
                                                </td>
                                                <td>PT. Emiindo Jaya Bersama</td>
                                                <td>Pemerintah Kota Padang</td>
                                                <td>-</td>
                                                <td><span class="badge yellow-text">Sebagian Diproses</span></td>
                                                <td><a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="" data-id="1">
                                                        <i class="fas fa-search"></i>
                                                    </a></td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>SO/SPB/XI/02/01</td>
                                                <td>AK1-909090-1892180</td>
                                                <td>
                                                    <div>21-09-2021</div>
                                                    <small>Batas sisa 6 Hari</small>
                                                </td>
                                                <td>PT. Emiindo Jaya Bersama</td>
                                                <td>Rumah Sakit Santo Paulus</td>
                                                <td>-</td>
                                                <td><span class="badge red-text">Belum Diproses</span></td>
                                                <td><a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="" data-id="1">
                                                        <i class="fas fa-search"></i>
                                                    </a></td>
                                            </tr>
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
    <div class="modal fade" id="detailmodal" role="dialog" aria-labelledby="detailmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" style="margin: 10px">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Info</h4>
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
@stop
@section('adminlte_js')
<script>
    $(function() {
        $(document).on('click', '.detailmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            var id = $(this).data('id');
            $.ajax({
                url: "/dc/so/detail/" + id,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#detailmodal').modal("show");
                    $('#detail').html(result).show();
                    console.log(id);
                    // $("#editform").attr("action", href);
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
        // var showtable = $('#showtable').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     ajax: {
        //         'url': '/api/qc/so/data',
        //         'type': 'POST',
        //         'headers': {
        //             'X-CSRF-TOKEN': '{{csrf_token()}}'
        //         }

        //     },
        //     language: {
        //         processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
        //     },
        //     columns: [{
        //         data: 'DT_RowIndex',
        //         className: 'nowrap-text align-center',
        //         orderable: false,
        //         searchable: false
        //     }, {
        //         data: 'no_po',

        //     }, {
        //         data: 'DT_RowIndex',
        //         className: 'nowrap-text align-center',
        //         orderable: false,
        //         searchable: false,
        //     }, {
        //         data: 'nama_customer',

        //     }, {
        //         data: 'DT_RowIndex',
        //         className: 'nowrap-text align-center',
        //         orderable: false,
        //         searchable: false
        //     }, {
        //         data: 'DT_RowIndex',
        //         className: 'nowrap-text align-center',
        //         orderable: false,
        //         searchable: false
        //     }, {
        //         data: 'DT_RowIndex',
        //         className: 'nowrap-text align-center',
        //         orderable: false,
        //         searchable: false
        //     }]
        // })

    })
</script>
@stop