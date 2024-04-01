<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TFProduksi extends Model
{
    use HasFactory;

    //protected $table = 't_tfbj';
    protected $connection = 'erp';
    protected $table = 't_gbj';

    protected $fillable = ['ke', 'dari', 'deskripsi', 'status_id','batal_pesanan_id','retur_pesanan_id','pesanan_id', 'retur_penjualan_id', 'tgl_keluar', 'tgl_masuk', 'state_id', 'jenis', 'created_by', 'created_at'];

    function detail()
    {
        return $this->hasMany(TFProduksiDetail::class, 't_gbj_id');
    }

    function his()
    {
        return $this->hasMany(TFProduksiHis::class, 'tfbj_id');
    }

    function divisi()
    {
        return $this->belongsTo(Divisi::class, 'ke');
    }

    function darii()
    {
        return $this->belongsTo(Divisi::class, 'dari');
    }

    function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    function bagian()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    function getJumlahKirim()
    {
        $id = $this->id;
        $detail = TFProduksiDetail::where('t_gbj_id', $id)->get();
        $jumlah = 0;
        foreach ($detail as $d) {
            $jumlah += $d->qty;
        }
        return $jumlah;
    }

    function ReturPenjualan()
    {
        return $this->belongsTo(ReturPenjualan::class);
    }
}
