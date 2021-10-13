<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualanProduk extends Model
{
   protected $table = 'detail_penjualan_produk';
   protected $fillable = ['produk_id', 'penjualan_produk_id', 'jumlah'];

   public function Produk()
   {
      return $this->belongsToMany(Produk::class, 'produk_id');
   }
   public function PenjualanProduk()
   {
      return $this->belongsToMany(PenjualanProduk::class, 'penjualan_produk_id');
   }
}
