@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="card-title">Produk Gudang Barang Jadi</h3>
                            </div>
                            <div class="col-4 d-flex justify-content-end">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <span class="float-right">
                                            <button type="button" class="btn btn-info" data-toggle="modal"
                                                data-target="#modal-create">
                                                <i class="fas fa-plus"></i>&nbsp;Tambah
                                            </button>
                                        </span>
                                        <span class="dropdown float-right" id="semuaprodukfilter"
                                            style="margin-right: 5px">
                                            <button class="btn btn-outline-info dropdown-toggle" type="button"
                                                id="semuaprodukfilter" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false"><i class="fas fa-filter"></i>&nbsp;
                                                Filter
                                            </button>
                                            <div class="dropdown-menu p-3 text-nowrap"
                                                aria-labelledby="semuaprodukfilter">
                                                <div class="dropdown-header">Kelompok Produk</div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="sp_kelompok"
                                                            value="alat_kesehatan" />
                                                        <label class="form-check-label" for="sp_kelompok">
                                                            Alat Kesehatan
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="sp_kelompok"
                                                            value="sarana_kesehatan" />
                                                        <label class="form-check-label" for="sp_kelompok">
                                                            Sarana Kesehatan
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered datatable" id="gudang-barang">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Produk</th>
                                    <th>Stok Gudang</th>
                                    <th>Stok</th>
                                    <th>Kelompok</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Create --}}
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="modal-create" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Produk GBJ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <label for="">Nama Produk</label>
                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Produk">
                    </div>
                    <div class="col">
                        <label for="">Stok</label>
                        <input type="text" name="stok" id="stok" class="form-control" placeholder="Stok">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" id="deskripsi" cols="5" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="">Dimensi</label>
                    <div class="d-flex justify-content-between">
                        <input type="text" class="form-control" name="dim_p" id="dim_p" placeholder="Panjang">&nbsp;
                        <input type="text" class="form-control" name="dim_l" id="dim_l" placeholder="Lebar">&nbsp;
                        <input type="text" class="form-control" name="dim_t" id="dim_t" placeholder="Tinggi">&nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Produk</label>
                            <select name="produk_id" id="produk_id" class="form-control produk-add">

                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <label for="">Layout</label>
                        <select name="layout_id" id="layout_id" class="form-control layout-add">

                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" name="gambar" class="custom-file-input" id="inputGroupFile02"/>
                        <label class="custom-file-label" for="inputGroupFile02">Pilih File</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary" id="Submitmodalcreate">Kirim</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<!-- Button trigger modal -->

<!-- Modal -->

<div class="modal" id="EditArticleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div id="EditArticleModalBody">

            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modelClose" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary" id="Submitmodaledit">Kirim</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal View --}}

<!-- Modal -->

<div class="modal" id="DeleteArticleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div id="GetArticleModalBody">

            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default modelCloseq" data-dismiss="modal">Keluar</button>
            </div>
        </div>
    </div>
</div>
<style>
    img {
        width: 100%;
    }

</style>
@stop

@section('adminlte_js')
{{-- <script src="{{ asset('native/js/gbj/produk.js') }}"></script> --}}
<script>
    $('#inputGroupFile02').on('change', function () {
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.produk-add ').select2();
        $('.layout-add').select2();
        // load data
        var datatable = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            searchable: false,
            // pageLength: 5,
            // scrollX: true,
            "order": [
                [0, "desc"]
            ],
            ajax: '{{ route('gbj.get') }}',
            // ajax:{ 'url': '/api/gbj/data'},
            columns: [{
                    data: "DT_RowIndex",
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'kode',
                    name: 'kode'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'satuan',
                    name: 'satuan'
                },
                {
                    data: 'satuan1',
                    name: 'satuan1'
                },
                {
                    data: 'kelompok',
                    name: 'kelompok'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });

        // load layout
        $.ajax({
            url: '{{ route('sel.layout') }}',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if(res) {
                    console.log(res);
                    $("#layout_id").empty();
                    $("#layout_id").append('<option value="">-- Pilih Layout--</option>');
                    $.each(res, function(key, value) {
                        $("#layout_id").append('<option value="'+value.id+'">'+value.ruang+';'+value.lantai+'-'+value.rak+'</option');
                    });
                } else {
                    $("#layout_id").empty();
                }
            }
        });

        // load produk
        $.ajax({
            url: '{{ route('sel.produk') }}',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if(res) {
                    console.log(res);
                    $("#produk_id").empty();
                    $("#produk_id").append('<option value="">-- Pilih Produk--</option>');
                    $.each(res, function(key, value) {
                        $("#produk_id").append('<option value="'+value.id+'">'+value.nama+'</option');
                    });
                } else {
                    $("#produk_id").empty();
                }
            }
        });

        $('#produk_id').change(function(e) {
            var id = $(this).val();
            console.log(id);
            if(id) {
                $.ajax({
                    url: '/api/produk/select-produk/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        if(res) {
                            console.log(res);
                            $('#nama').val(res.nama);
                        } else {
                            // $("#layout_id").empty();
                        }
                    }
                });
            }
        })

        // post
        $('#Submitmodalcreate').click(function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('gbj.post') }}",
                method: "post",
                data: {
                    nama: $('#nama').val(),
                    stok: $('#stok').val(),
                    deskripsi: $('#deskripsi').val(),
                    dim_p: $('#dim_p').val(),
                    dim_l: $('#dim_l').val(),
                    dim_t: $('#dim_t').val(),
                    produk_id: $('#produk_id').val(),
                    layout_id: $('#layout_id').val(),
                    gambar: $('#inputGroupFile02').val()
                },
                success: function (res) {
                    if (res.errors) {
                        console.log('error');
                    } else {
                        console.log('ok');
                        $('.datatable').DataTable().ajax.reload();
                        location.reload();
                    }
                }
            });
        });

        // edit
        $('.modelClose').on('click', function(){
            $('#EditArticleModal').hide();
        });
        var id;
        $('body').on('click', '#getEditArticleData', function(e) {
            // e.preventDefault();
            // $('.alert-danger').html('');
            // $('.alert-danger').hide();
            id = $(this).data('id');
            $.ajax({
                url: "/api/gbj/ubah/" + id,
                method: 'GET',
                // data: {
                //     id: id,
                // },
                success: function(result) {
                    console.log(result);
                    $('#EditArticleModalBody').html(result.html);
                    $('.produk-edit ').select2({
                        placeholder: 'Select an item',
                        ajax: {
                            url: '{{ route('sel.produk') }}',
                            dataType: 'json',
                            delay: 250,
                            processResults: function (data) {
                            return {
                                results:  $.map(data, function (item) {
                                    return {
                                        text: item.nama,
                                        id: item.id
                                    }
                                })
                            };
                            },
                            cache: true
                        }
                    });
                    $('.layout-edit').select2({
                        placeholder: 'Select an item',
                        ajax: {
                            url: '{{ route('sel.layout') }}',
                            dataType: 'json',
                            delay: 250,
                            processResults: function (data) {
                            return {
                                results:  $.map(data, function (item) {
                                    return {
                                        text: item.ruang + item.lantai + item.rak,
                                        id: item.id
                                    }
                                })
                            };
                            },
                            cache: true
                        }
                    });
                    $('#EditArticleModal').show();
                }
            });
        });
        $('#Submitmodaledit').click(function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/api/gbj/edit/"+id,
                method: 'POST',
                data: {
                    nama: $('#editnama').val(),
                    stok: $('#editstok').val(),
                    deskripsi: $('#editdeskripsi').val(),
                    dim_p: $('#editdim_p').val(),
                    dim_l: $('#editdim_l').val(),
                    dim_t: $('#editdim_t').val(),
                    produk_id: $('#editproduk_id').val(),
                    layout_id: $('#editlayout_id').val(),
                    gambar: $('#editinputGroupFile02').val(),
                },
                success: function(result) {
                    if(result.errors) {
                        console.log('error');
                    } else {
                        $('.datatable').DataTable().ajax.reload();
                        $('#EditArticleModal').hide();
                        location.reload();
                    }
                }
            });
        });

        // detail
        $('.modelCloseq').on('click', function(){
            $('#DeleteArticleModal').hide();
        });
        var id;
        $('body').on('click', '#showEditArticleData', function(e) {
            // e.preventDefault();
            // $('.alert-danger').html('');
            // $('.alert-danger').hide();
            id = $(this).data('id');
            $.ajax({
                url: "/api/gbj/view/" + id,
                method: 'GET',
                // data: {
                //     id: id,
                // },
                success: function(result) {
                    console.log(result);
                    $('#GetArticleModalBody').html(result.html);
                    $('#DeleteArticleModal').show();
                }
            });
        });
    });

</script>
@stop
