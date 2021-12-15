@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
    #DataTables_Table_0_filter{
        display: none;
    }
</style>
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Riwayat Perakitan</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row ml-1">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="">Pilih Produk</label>
                    <select name="" id="" class="form-control" id="produk_select" multiple>
                    </select>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="">Tanggal Perakitan</label>
                    <input type="text" name="" id="" class="form-control daterange">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="">Tanggal Pengiriman</label>
                    <input type="text" name="" id="" class="form-control daterange">
                </div>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row text-center">
                            <div class="col-6">
                                <h3 class="font-weight-bold" id="h_rakit">{{ $rakit }}</h3>
                                <h4 class="font-weight-normal text-muted">Perakitan</h4>
                            </div>
                            <div class="col-6">
                                <h3 class="font-weight-bold" id="h_unit">{{ $unit }}</h3>
                                <h4 class="font-weight-normal text-muted">Unit Dirakit</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-history">
                            <thead class="thead-light">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Tanggal & Waktu Perakitan</th>
                                    <th>Waktu1</th>
                                    <th>Tanggal & Waktu Pengiriman</th>
                                    <th>Nomor BPPB</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
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
</section>

<!-- Modal -->
<div class="modal fade modal_id" id="modal_id" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Tanggal & Waktu Perakitan</label>
                                <div class="card-group">
                                    <div class="card" style="background-color: #C8E1A7">
                                        <div class="card-body">
                                            <p class="card-text" id="d_rakit">Senin 10-04-2021</p>
                                        </div>
                                    </div>
                                    <div class="card" style="background-color: #C8E1A7">
                                        <div class="card-body">
                                            <p class="card-text" id="t_rakit">08.00 WIB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Tanggal & Waktu Pengiriman</label>
                                <div class="card-group">
                                    <div class="card" style="background-color: #FFECB2">
                                        <div class="card-body">
                                            <p class="card-text" id="d_kirim">Selasa 11-04-2021</p>
                                        </div>
                                    </div>
                                    <div class="card" style="background-color: #FFECB2">
                                        <div class="card-body">
                                            <p class="card-text" id="t_kirim">09.00 WIB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Nomor BPPB</label>
                                <div class="card" style="background-color: #F89F81">
                                    <div class="card-body" id="bppb">
                                        516546546546546
                                    </div>
                                  </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Nama Produk</label>
                                <div class="card" style="background-color: #FCF9C4">
                                    <div class="card-body" id="produk">
                                        Produk 1
                                    </div>
                                  </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Jumlah</label>
                                <div class="card" style="background-color: #FFCC83">
                                    <div class="card-body" id="jml">
                                        100 Unit
                                    </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped scan-produk">
                                    <thead>
                                        <tr>
                                            <th>Nomor Seri</th>
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
@stop

@section('adminlte_js')
<script>

    $(document).on('click', '.detail', function() {
        // console.log('test');
        var id = $(this).data('id');
        var time = $(this).data('tf');

        $.ajax({
            url: "/api/prd/history/header/" + id,
            type: "get",
            success: function(res) {
                console.log(res);
                $('p#d_rakit').text(res[0].day_rakit);
                $('p#t_rakit').text(res[0].time_rakit);
                $('p#d_kirim').text(res[0].day_kirim);
                $('p#t_kirim').text(res[0].time_kirim);
                $('div#bppb').text(res[0].bppb);
                $('div#produk').text(res[0].produk);
                $('div#jml').text(res[0].jml);
            }
        });

        $('#modal_id').modal('show');

        $('.scan-produk').DataTable({
            destroy: true,
            "ordering":false,
            "autoWidth": false,
            "lengthChange": false,
            "pageLength": 10,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/api/prd/historySeri/" + id + "/" + time,
            },
            columns: [
                {data: 'no_seri'}
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
    })
    $('.daterange').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY'
        }
    });
    var groupColumn = 0;
    var groupColumn1 = 2;
    var table = $('.table-history').DataTable({
        "columnDefs": [
            { "visible": false, "targets": groupColumn },
            { "visible": false, "targets": groupColumn1 }
        ],
        destroy: true,
        "lengthChange": false,
        "ordering": false,
        "info": false,
        "responsive": true,
        "order": [[ groupColumn, 'asc' ]],
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;

            api.column(0, {page:'current'} ).data().each( function ( group, i ) {

                if (last !== group) {
                    var rowData = api.row(i).data();

                    $(rows).eq(i).before(
                    '<tr class="table-dark text-bold"   ><td colspan="1">' + group + '</td><td colspan="5">'+rowData.day_kirim+'</td></tr>'
                );
                    last = group;
                }
                console.log(rowData);
            });
        },
        autoWidth: false,
        processing: true,
        ajax: {
            url: "/api/prd/ajax_his_rakit",
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        },
        columns: [
            {data: 'day_rakit'},
            {data: 'time_rakit'},
            {data: 'day_kirim'},
            {data: 'time_kirim'},
            {data: 'bppb'},
            {data: 'produk'},
            {data: 'jml'},
            {data: 'action'},
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        },
        initComplete: function () {
            this.api.columns(3).every( function () {
                var column = this;
                var select = $('<select class="form-control"><option value="">Pilih</option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        $('#produk_select').select2();
        }
    });
</script>
@stop
