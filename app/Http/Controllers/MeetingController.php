<?php

namespace App\Http\Controllers;

use App\Models\DokumenMeeting;
use App\Models\DokumenPeserta;
use stdClass;
use Carbon\Carbon;
use App\Models\HasilMeeting;
use App\Models\HasilNotulen;
use Illuminate\Http\Request;
use App\Models\JadwalMeeting;
use App\Models\PesertaMeeting;
use App\Models\kesehatan\Karyawan;
use App\Models\LokasiMeet;
use Illuminate\Support\Facades\DB;
use App\Models\RiwayatJadwalMeeting;
use Kreait\Firebase\Exception\FirebaseException;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PDF;
use Illuminate\Support\Str;

class MeetingController extends Controller
{
    //
    public function delete_lokasi_meet(Request $request)
    {
        DB::beginTransaction();
        try {
            $data =  LokasiMeet::find($request->id)->delete();

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Di Hapus',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Gagal Di Hapus',
                'error' => $th->getMessage()
            ], 200);
        }
    }

    public function getKaryawanForNotulen()
    {
        try {
            $karyawan = auth()->user()->Karyawan;

            return response()->json([
                'status' => 200,
                'data' => $karyawan,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal',
            ], 500);
        }
    }

    public function update_lokasi_meet(Request $request)
    {

        DB::beginTransaction();

        try {
            //code...
            $cek = LokasiMeet::where('nama', $request->nama)
                ->whereNotIN('id', [$request->id])
                ->count();


            if ($cek > 0) {
                DB::rollBack();
                return response()->json([
                    'status' => 500,
                    'message' => 'Nama Sudah Terdaftar',
                ], 500);
            } else {
                $data = LokasiMeet::find($request->id);
                $data->nama = $request->nama;
                $data->save();
                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil Di ubah',
                ], 200);
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => 'Gagal Di ubah' . $th->getMessage(),
            ], 500);
        }

        return response()->json($data);
    }
    public function show_lokasi_meet(Request $request)
    {
        $data = array();
        $data = LokasiMeet::all();
        return response()->json($data);
    }

    public function store_lokasi_meet(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            $cek = LokasiMeet::where('nama', $request->nama);
            if ($cek->count() > 0) {

                DB::rollBack();
                return response()->json([
                    'status' => 500,
                    'message' => 'Nama Sudah Terdaftar',
                ], 500);
            } else {
                LokasiMeet::create([
                    'nama' => $request->nama
                ]);

                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil Ditambahkan',
                ], 200);
            }
        } catch (\Throwable $th) {
            //throw $th;
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => 'Gagal Di Tambahkan' . $th->getMessage(),
            ], 500);
        }
    }
    public function store_jadwal_meet(Request $request)
    {

        DB::beginTransaction();
        try {
            //code...
            $max = JadwalMeeting::whereYear('created_at', now()->year)->max('urutan') + 1;
            $jm =   JadwalMeeting::create([
                "urutan" => $max,
                "judul" => $request->judul,
                "tgl_meeting" => $request->tanggal,
                "mulai" =>  $request->mulai,
                "selesai" => $request->selesai,
                "lokasi" =>  $request->lokasi,
                "status" => 'belum',
                "notulen" =>  $request->notulen,
                "moderator" =>  $request->moderator,
                "pimpinan" =>  $request->pimpinan,
                "deskripsi" => $request->deskripsi
            ]);

            if (count($request->peserta) > 0) {
                foreach ($request->peserta as $p) {
                    PesertaMeeting::create([
                        'meeting_id' => $jm->id,
                        'karyawan_id' => $p,
                        'status' => auth()->user()->karyawan_id == $p ? 'hadir' : 'belum',
                        'ket' => NULL,
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Ditambahkan',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 200,
                'message' => 'Gagal Di Tambahkan' . $th->getMessage(),
            ], 500);
        }
    }

    public function verif_notulen_meet(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            $notulen = HasilNotulen::find($request->id);
            $notulen->hasil = $request->status;
            $notulen->verif_id = auth()->user()->karyawan_id;
            $notulen->checked_at = Carbon::now();
            $notulen->ket = isset($request->catatan) ? $request->catatan : NULL;
            $notulen->save();
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Ditambahkan',

            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 200,
                'response' => $th->getMessage(),
                'message' => 'Gagal Di Tambahkan',
            ], 500);
        }
    }
    public function store_notulen_meet(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            HasilNotulen::create([
                'meeting_id' => $request->meeting_id,
                'pic_id' => $request->karyawan_id,
                'uraian' => $request->uraian,
                'hasil' => auth()->user()->karyawan_id ==  $request->karyawan_id ? 'sesuai' : 'belum',
                'verif_id' => auth()->user()->karyawan_id ==  $request->karyawan_id  ? $request->karyawan_id  : NULL,
                'checked_at' => auth()->user()->karyawan_id ==  $request->karyawan_id  ? Carbon::now() : NULL,
            ]);

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Ditambahkan',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 200,
                'response' => $th->getMessage(),
                'message' => 'Gagal Di Tambahkan',
            ], 500);
        }
    }
    public function store_hasil_meet(Request $request)
    {
        $obj =  json_decode(json_encode($request->all()), FALSE);
        DB::beginTransaction();
        try {
            //code...
            HasilMeeting::updateOrCreate([
                'meeting_id' => $request->meeting_id,
                'id' => isset($request->id) ? $request->id : NULL
            ], [
                'isi' => $request->isi
            ]);

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Ditambahkan',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 200,
                'message' => 'Gagal Di Tambahkan',
            ], 500);
        }
    }

    public function update_hasil_meet(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            //code...
            $h = HasilMeeting::find($id);
            $h->isi = $request->isi;
            $h->save();


            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Diubah',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 200,
                'message' => 'Gagal Diubah',
            ], 500);
        }
    }

    public function delete_hasil_meet($id)
    {
        DB::beginTransaction();
        try {
            //code...
            $h = HasilMeeting::find($id)->delete();
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Di hapus',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 200,
                'message' => 'Gagal Di hapus',
            ], 500);
        }
    }

    public function show_hasil_meet($id)
    {
        try {
            //code...
            //code...
            $data = HasilMeeting::where('meeting_id', $id)->get();
            if ($data->isEmpty()) {
                $obj = array();
            } else {
                foreach ($data as $d) {
                    $obj[] = (object)[
                        "id" => $d->id,
                        "isi" => $d->isi,
                    ];
                }
            }
            return response()->json($obj);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 200,
                'message' => 'Gagal',
            ], 500);
        }
    }

    public function edit_hasil_meet($id)
    {
        try {
            $data = HasilMeeting::find($id);
            if (!$data) {
                $data = array();
            }
            return response()->json($data);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 200,
                'message' => 'Gagal' . $th->getMessage(),
            ], 500);
        }
    }

    public function show_peserta($id)
    {
        $data = PesertaMeeting::where(['meeting_id' => $id, 'status' => 'hadir'])->get();
        $obj = array();
        foreach ($data as $d) {
            $obj[] = array(
                'id' => $d->Karyawan->id,
                'nama' => $d->Karyawan->nama,
            );
        }
        return response()->json($obj);
    }

    public function checkApproval($id)
    {
        $data = JadwalMeeting::find($id);
        $userid = auth()->user()->karyawan_id;

        if ($data->pimpinan == $userid) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function update_status_meet($status, Request $request)
    {
        //dd($request->all());
        $obj =  json_decode(json_encode($request->all()), FALSE);
        DB::beginTransaction();
        try {
            //code...
            if ($status == 'batal') {
                //code...
                $data = JadwalMeeting::find($obj->id);
                $data->status = 'batal';
                $data->ket_batal = $obj->ket_batal;
                $data->save();
            } elseif ($status == 'approve_setuju') {
                $data = JadwalMeeting::find($obj->id);
                $data->status = 'terlaksana';
                $data->status_app = 'setuju';
                $data->tgl_app = Carbon::now();
                $data->save();

                foreach ($obj->notulensi as $key => $notulensi) {
                    $n =  HasilNotulen::find($notulensi->id);
                    $n->urutan = $key + 1;
                    $n->save();
                }
            } elseif ($status == 'approve_batal') {
                $data = JadwalMeeting::find($obj->id);
                $data->status = 'menyusun_hasil_meeting';
                $data->status_app = 'tidak setuju';
                $data->tgl_app = Carbon::now();
                $data->save();
            } elseif ($status == 'terlaksana') {

                // if (isset($obj->keteranganketidaksesuaian)) {

                $data = JadwalMeeting::find($obj->id);

                $objs = new stdClass();
                $objs->urutan =  $data->urutan;
                $objs->judul = $data->judul;
                $objs->tgl_meeting =  $data->tgl_meeting;
                $objs->mulai =  $data->mulai;
                $objs->selesai =  $data->selesai;
                $objs->lokasi =  $data->Lokasi->nama;
                $objs->status =  $data->status;
                $objs->notulen =   $data->notulen;
                $objs->pimpinan =   $data->pimpinan;
                $objs->moderator =   $data->moderator;
                $objs->deskripsi =  $data->deskripsi;

                if (count($data->DokumenMeeting) > 0) {
                    $objs->dokumen = $data->DokumenMeeting;
                } else {
                    $objs->dokumen = array();
                }

                $peserta = array();
                if (count($data->PesertaMeeting) > 0) {
                    foreach ($data->PesertaMeeting as $p) {
                        $peserta[] = (object)[
                            'id' => $p->id,
                            'karyawan_id' => $p->karyawan_id,
                            'nama' => $p->Karyawan->nama,
                            'jabatan' => $p->Karyawan->Divisi->nama,
                            'status' => $p->status,
                            'ket' => $p->ket,
                            'dokumen' => $p->DokumenPeserta,
                        ];
                    }


                    $objs->peserta = $peserta;
                    PesertaMeeting::where('meeting_id', $obj->id)->delete();
                } else {
                    $objs->peserta = $peserta;
                }
                //dd($obj);
                RiwayatJadwalMeeting::create([
                    'meeting_id' => $obj->id,
                    'isi' => json_encode($objs),
                    'user_id' =>  auth()->user()->id,
                    'ket' => isset($obj->keteranganketidaksesuaian) ? $obj->keteranganketidaksesuaian : 'NULL'
                ]);


                $data->tgl_meeting =  $obj->tanggal;
                $data->mulai =  $obj->mulai;
                $data->selesai =  $obj->selesai;
                $data->lokasi = $obj->lokasi;
                $data->notulen = $request->notulen;
                $data->moderator = $request->moderator;
                $data->pimpinan = $request->pimpinan;
                $data->status = 'menyusun_hasil_meeting';
                $data->save();

                foreach ($obj->hasil as $hasil) {
                    HasilMeeting::create([
                        'meeting_id' => $obj->id,
                        'isi' => $hasil->isi,
                    ]);
                }

                foreach ($obj->notulensi as $key => $notulensi) {
                    HasilNotulen::create([
                        'meeting_id' => $obj->id,
                        'urutan' => $key + 1,
                        'pic_id' => $notulensi->pic,
                        'uraian' => $notulensi->isi,
                        'hasil' => auth()->user()->karyawan_id ==  $notulensi->pic ? 'sesuai' : 'belum',
                        'verif_id' => auth()->user()->karyawan_id ==  $notulensi->pic ? $notulensi->pic : NULL,
                        'checked_at' => auth()->user()->karyawan_id ==  $notulensi->pic ? Carbon::now() : NULL,
                    ]);
                }

                if (count($obj->peserta) > 0) {

                    foreach ($obj->peserta as $p) {
                        PesertaMeeting::create([
                            'meeting_id' => $obj->id,
                            'karyawan_id' => $p,
                            'status' => auth()->user()->karyawan_id == $p ? 'hadir' : 'belum',
                            'ket' => NULL,
                        ]);
                    }
                }

                if (count($obj->dokumentasi) > 0) {
                    for ($j = 0; $j < count($request->dokumentasi); $j++) {
                        $original               = $request->dokumentasi[$j]['file']->getClientOriginalName();
                        $randomCollectionName = Str::uuid()->toString();
                        $extension = $request->dokumentasi[$j]['file']->getClientOriginalExtension();
                        $file = $randomCollectionName . '.' . $extension;
                        //FTP
                        //Storage::disk('ftp')->put($file, fopen($request->dokumentasi[$j]['file'], 'r+'));
                        ///GCP
                        $storeFile = $request->dokumentasi[$j]['file']->storeAs("test", $file, "gcs");
                        Storage::disk('gcs')->url($storeFile);

                        // $file = $request->file('file');
                        // $file_name = time() . '_' . $file->getClientOriginalName();
                        // $storeFile = $file->storeAs("test", $file_name, "gcs");
                        // $disk = Storage::disk('gcs');
                        // $fetchFile = $disk->url($storeFile);
                        DokumenMeeting::create([
                            'meeting_id' => $obj->id,
                            'nama' => $file,
                            'original' => $original,
                        ]);
                    }
                }
                // }
                // else {
                //     $data = JadwalMeeting::find($obj->id);
                //     $data->status = 'menyusun_hasil_meeting';
                //     $data->save();

                //     foreach ($obj->hasil as $hasil) {
                //         HasilMeeting::create([
                //             'meeting_id' => $obj->id,
                //             'isi' => $hasil->isi,
                //         ]);
                //     }
                //     foreach ($obj->notulensi as $key =>  $notulensi) {
                //         HasilNotulen::create([
                //             'meeting_id' => $obj->id,
                //             'urutan' => $key + 1,
                //             'pic_id' => $notulensi->pic,
                //             'uraian' => $notulensi->isi,
                //             'hasil' => 'belum',
                //         ]);
                //     }

                //     if (count($obj->dokumentasi) > 0) {
                //         for ($j = 0; $j < count($request->dokumentasi); $j++) {
                //             $randomCollectionName = Str::uuid()->toString();
                //             $extension = $request->dokumentasi[$j]['file']->getClientOriginalExtension();
                //             $file = $randomCollectionName . '.' . $extension;
                //             Storage::disk('ftp')->put($file, fopen($request->dokumentasi[$j]['file'], 'r+'));

                //             DokumenMeeting::create([
                //                 'meeting_id' => $obj->id,
                //                 'nama' => $file,
                //             ]);
                //         }
                //     }
                // }
            } elseif ($status == 'selesai') {
                $data = JadwalMeeting::find($obj->id);
                $data->status = 'menunggu_approval_pimpinan';
                $data->save();

                foreach ($obj->notulensi as $key => $notulensi) {
                    $n =  HasilNotulen::find($notulensi->id);
                    $n->urutan = $key + 1;
                    $n->save();
                }
            } else {
                DB::rollback();
                return response()->json([
                    'status' => 200,
                    'message' => 'Gagal',
                ], 500);
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Diubah',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function batal_jadwal_meet(Request $request)
    {
        //  dd($request->all());
        try {
            //code...
            $data = JadwalMeeting::find($request->id);
            $data->status = 'batal';
            $data->save();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Diubah',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 200,
                'message' => 'Gagal',
            ], 500);
        }
    }

    public function update_jadwal_meet(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = JadwalMeeting::find($id);
            if ($data) {

                $obj = new stdClass();
                $obj->urutan =  $data->urutan;
                $obj->judul = $data->judul;
                $obj->tgl_meeting =  $data->tgl_meeting;
                $obj->mulai =  $data->mulai;
                $obj->selesai =  $data->selesai;
                $obj->lokasi =  $data->Lokasi->nama;
                $obj->status =  $data->status;
                $obj->notulen =   $data->notulen;
                $obj->moderator =   $data->moderator;
                $obj->pimpinan =   $data->pimpinan;
                $obj->deskripsi =  $data->deskripsi;

                if (count($data->PesertaMeeting) > 0) {
                    foreach ($data->PesertaMeeting as $p) {
                        $peserta[] = (object)[
                            'id' => $p->id,
                            'karyawan_id' => $p->karyawan_id,
                            'nama' => $p->Karyawan->nama,
                            'jabatan' => $p->Karyawan->Divisi->nama,
                            'status' => $p->status,
                            'ket' => $p->ket,
                            'dokumen' => $p->DokumenPeserta,
                        ];
                        if ($p->DokumenPeserta) {
                            DokumenPeserta::where('peserta_meeting_id', $p->id)->delete();
                        }
                    }
                    $obj->peserta = $peserta;
                    PesertaMeeting::where('meeting_id', $id)->delete();
                } else {
                    $obj->peserta = array();
                }

                RiwayatJadwalMeeting::create([
                    'meeting_id' => $id,
                    'isi' => json_encode($obj),
                    'user_id' => 2,
                    'ket' => $request->alasan
                ]);

                $data->deskripsi =  $request->deskripsi;
                $data->tgl_meeting =  $request->tanggal;
                $data->mulai =  $request->mulai;
                $data->selesai =  $request->selesai;
                $data->lokasi = $request->lokasi;
                $data->notulen = $request->notulen;
                $data->moderator = $request->moderator;
                $data->pimpinan = $request->pimpinan;
                $data->save();

                if (count($request->peserta) > 0) {
                    foreach ($request->peserta as $p) {
                        PesertaMeeting::create([
                            'meeting_id' => $id,
                            'karyawan_id' => $p,
                            'status' => auth()->user()->karyawan_id == $p ? 'hadir' : 'belum',
                            'ket' => NULL,
                        ]);
                    }
                }
                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil Diubah',
                ], 200);
            } else {
                DB::rollBack();
                return response()->json([
                    'status' => 200,
                    'message' => 'Jadwal Tidak ditemukan',
                ], 500);
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 200,
                'message' => 'Gagal Di Ubah' . $th->getMessage(),
            ], 500);
        }
    }

    public function update_hadir_jadwal_meet(Request $request)
    {


        $obj =  json_decode(json_encode($request->all()), FALSE);
        // DB::beginTransaction();
        try {

            if ($request->update_kehadiran == 'status_lalu') {
                $get_history_id = RiwayatJadwalMeeting::where('meeting_id', $request->id)
                    ->latest('id')
                    ->value('id');
                $get_data_history = RiwayatJadwalMeeting::find($get_history_id);
                $data_history = json_decode($get_data_history->isi);
                $data = collect($data_history->peserta)->where('karyawan_id', auth()->user()->karyawan_id);

                $p = PesertaMeeting::where(['karyawan_id' => auth()->user()->karyawan_id, 'meeting_id' => $request->id]);
                $p->update(['status' => $data->pluck('status')->implode(', '), 'ket' =>  $data->pluck('ket')->implode(', ')]);


                if ($data->pluck('status')->implode(', ') == 'belum') {
                    DB::rollBack();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Anda belum mengisi kehadiran sebelumnya, silahkan isi update kehadiran dengan pilihan Ya'
                    ], 500);
                }
            } else {
                $kehadiran = $request->kehadiran == 'hadir' ? 'hadir' : 'tidak_hadir';
                $alasan = isset($request->alasan) ? $request->alasan : NULL;
                $p = PesertaMeeting::where(['karyawan_id' => auth()->user()->karyawan_id, 'meeting_id' => $request->id]);
                $p->update(['status' => $kehadiran, 'ket' => $alasan]);
                if ($request->kehadiran == 'tidak_hadir') {
                    for ($j = 0; $j < count($request->dokumentasi); $j++) {
                        $randomCollectionName = Str::uuid()->toString();
                        $extension = $request->dokumentasi[$j]['file']->getClientOriginalExtension();
                        $file = $randomCollectionName . '.' . $extension;
                        Storage::disk('ftp')->put($file, fopen($request->dokumentasi[$j]['file'], 'r+'));

                        DokumenPeserta::create([
                            'peserta_meeting_id' => $p->first()->id,
                            'nama' => $file,
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Diubah',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 200,
                'message' => 'Gagal Di Ubah',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function show_jadwal_meet_person($status)
    {
        try {
            $id = auth()->user()->karyawan_id;;
            if ($status == 'belum') {
                $data = PesertaMeeting::select(
                    'jadwal_meeting.id',
                    'peserta_meeting.status',
                    'jadwal_meeting.status as status_jadwal',
                    'jadwal_meeting.urutan',
                    'jadwal_meeting.judul',
                    'jadwal_meeting.tgl_meeting',
                    'jadwal_meeting.mulai',
                    'jadwal_meeting.selesai',
                    'jadwal_meeting.lokasi',
                    'jadwal_meeting.moderator',
                    'jadwal_meeting.deskripsi',
                    'lokasi_meeting.nama as lokasi',
                    DB::raw('(SELECT COUNT(*) FROM riwayat_meeting WHERE riwayat_meeting.meeting_id = jadwal_meeting.id) as riwayat_count')
                )
                    ->leftJoin('jadwal_meeting', 'jadwal_meeting.id', '=', 'peserta_meeting.meeting_id')
                    ->leftJoin('lokasi_meeting', 'lokasi_meeting.id', '=', 'jadwal_meeting.lokasi')
                    ->where('karyawan_id', $id)
                    ->whereIN('jadwal_meeting.status', ['belum', 'menyusun_hasil_meeting', 'menunggu_approval_pimpinan'])->get();
            } else if ($status == 'selesai') {
                $data = PesertaMeeting::select(
                    'jadwal_meeting.id',
                    'peserta_meeting.status',
                    'jadwal_meeting.status as status_jadwal',
                    'jadwal_meeting.urutan',
                    'jadwal_meeting.judul',
                    'jadwal_meeting.tgl_meeting',
                    'jadwal_meeting.mulai',
                    'jadwal_meeting.selesai',
                    'jadwal_meeting.lokasi',
                    'jadwal_meeting.moderator',
                    'jadwal_meeting.deskripsi',
                    'lokasi_meeting.nama as lokasi',
                    DB::raw('(SELECT COUNT(*) FROM riwayat_meeting WHERE riwayat_meeting.meeting_id = jadwal_meeting.id) as riwayat_count')
                )
                    ->leftJoin('jadwal_meeting', 'jadwal_meeting.id', '=', 'peserta_meeting.meeting_id')
                    ->leftJoin('lokasi_meeting', 'lokasi_meeting.id', '=', 'jadwal_meeting.lokasi')
                    ->where('karyawan_id', $id)
                    ->whereIN('jadwal_meeting.status', ['batal', 'terlaksana'])->get();
            } else {
                return false;
            }

            if ($data->isEmpty()) {
                $obj = array();
            } else {
                foreach ($data as $d) {
                    $obj[] = (object)[
                        "id" => $d->id,
                        "urutan" => 'Meet-' . $d->urutan,
                        "judul" => $d->judul,
                        "tanggal" => $d->tgl_meeting,
                        "mulai" =>  $d->mulai,
                        "selesai" => $d->selesai,
                        "lokasi" =>  $d->lokasi,
                        "status" =>  $d->status_jadwal,
                        "status_peserta" => $d->status,
                        "moderator" =>  $d->moderator,
                        "deskripsi" => $d->deskripsi,
                        "is_perubahan" => $d->riwayat_count > 0 ? true : false,
                        "is_kehadiran" => $d->status == 'belum' ? true : false,
                    ];
                }
            }
            return response()->json($obj);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 200,
                'message' => 'Gagal',
                'error' => $th->getMessage()
            ], 500);
        }
    }


    public function show_jadwal_meet_id($id)
    {
        $data = JadwalMeeting::find($id);
        if ($data) {
            $data->tanggal = $data->tgl_meeting;
            $data->peserta =  PesertaMeeting::select('karyawan_id')->where('meeting_id', $id)->get()->map(function ($item) {
                return $item->karyawan_id;
            });
        }
        return response()->json($data);
    }

    public function show_jadwal_meet($status)
    {
        try {
            $userid = "";

            $userid = auth()->user()->karyawan_id;
            //code...
            if ($status == 'belum') {
                $data = JadwalMeeting::addSelect([
                    'cpeserta' => function ($q) use ($userid) {
                        $q->selectRaw('coalesce(count(peserta_meeting.id),0)')
                            ->from('peserta_meeting')
                            ->where('peserta_meeting.karyawan_id', $userid)
                            ->whereColumn('peserta_meeting.meeting_id', 'jadwal_meeting.id')
                            ->limit(1);
                    },
                    'cnotulen' => function ($q) use ($userid) {
                        $q->selectRaw('coalesce(count(jm.id),0)')
                            ->from('jadwal_meeting as jm')
                            ->where('jm.notulen', $userid)
                            ->whereColumn('jm.id', 'jadwal_meeting.id')
                            ->limit(1);
                    },
                    'riwayat_count' =>  function ($q) use ($userid) {
                        $q->selectRaw('coalesce(count(riwayat_meeting.id),0)')
                            ->from('riwayat_meeting')
                            ->whereColumn('riwayat_meeting.meeting_id', 'jadwal_meeting.id')
                            ->limit(1);
                    },
                ])
                    ->whereIn('status', ['belum', 'menyusun_hasil_meeting', 'menunggu_approval_pimpinan'])
                    ->havingRaw('cpeserta >0 or cnotulen > 0')
                    // ->where(function ($query) use ($userid) {
                    //     $query->HavingRaw('cpeserta > 0')
                    //         ->orwhere('notulen', '=', $userid);
                    // })
                    ->get();
                // $data2 = JadwalMeeting::whereIn('status', ['menunggu_approval_pimpinan'])->where('notulen', '=', $userid)->get();
                // $data = $data1->merge($data2);
            } else if ($status == 'selesai') {
                $data = JadwalMeeting::whereIN('status', ['terlaksana', 'batal'])
                    ->with('DokumenMeeting')
                    ->get();
            } else if ($status == 'approval') {
                $data = JadwalMeeting::whereIn('status', ['menunggu_approval_pimpinan'])
                    ->where('pimpinan', $userid)
                    ->with('hasilNotulen')
                    ->get();
            } else {
                $data = array();
            }
            // return response()->json($data);
            if ($data->isEmpty()) {
                $obj = array();
            } else {
                foreach ($data as $d) {
                    $peran = array();

                    if ($userid != '') {
                        if ($d->moderator == $userid) {
                            $peran[] = 'moderator';
                        }
                        if ($d->notulen == $userid) {
                            $peran[] = 'notulen';
                        }
                        if ($d->pimpinan == $userid) {
                            $peran[] = 'pimpinan';
                        }

                        // Tambahkan 'peserta' jika peran adalah moderator atau pimpinan
                        if (in_array('moderator', $peran) || in_array('pimpinan', $peran)) {
                            $peran[] = 'peserta';
                        }

                        // Jika pengguna adalah notulen, hapus 'peserta' dari array
                        if (in_array('notulen', $peran)) {
                            $peran = array_diff($peran, ['peserta']);
                        }

                        // jika tidak ada semua maka tambahkan peserta
                        if (count($peran) == 0) {
                            $peran[] = 'peserta';
                        }
                    }

                    $obj[] = (object)[
                        "id" => $d->id,
                        "urutan" => 'Meet-' . $d->urutan,
                        "judul" => $d->judul,
                        "tanggal" => $d->tgl_meeting,
                        "mulai" =>  $d->mulai,
                        "selesai" => $d->selesai,
                        "lokasi_nama" =>  $d->Lokasi->nama ?? '',
                        "lokasi" =>  $d->lokasi,
                        "status" =>  $d->status,
                        "notulen" =>  $d->notulen,
                        "moderator" =>  $d->moderator,
                        "deskripsi" => $d->deskripsi,
                        "peserta" => PesertaMeeting::select('karyawan_id')->where('meeting_id', $d->id)->get()->map(function ($item) {
                            return $item->karyawan_id;
                        }),
                        "hasil_notulen" => $d->HasilNotulen->count() > 0 ? $d->HasilNotulen : [],
                        "dokumen_meet" => $d->DokumenMeeting->count() > 0 ? $d->DokumenMeeting : [],
                        "pimpinan" =>  $d->pimpinan,
                        "peran" =>  $peran,
                        "is_perubahan" => $d->riwayat_count > 0 ? true : false,
                        "status_kehadiran" => $userid != '' ? PesertaMeeting::where(['meeting_id' => $d->id, 'karyawan_id' => $userid])->first()->status : null,
                    ];
                }
            }

            return response()->json($obj);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 200,
                'message' => 'Gagal',
                'error' => $th->getMessage()
            ], 500);
        }
    }


    public function show_notulen_meet($id)
    {
        try {
            //code...
            $data = DB::table('erp_meetings.hasil_notulen')
                ->select('hasil_notulen.id', 'pic.nama as pic', 'verif.nama as verif', 'hasil_notulen.checked_at', 'hasil_notulen.hasil', 'hasil_notulen.uraian', 'hasil_notulen.ket')
                ->leftjoin('erp_kesehatan.karyawans as pic', 'pic.id', '=', 'hasil_notulen.pic_id')
                ->leftjoin('erp_kesehatan.karyawans as verif', 'verif.id', '=', 'hasil_notulen.verif_id')
                ->where('hasil_notulen.meeting_id', $id)
                ->get();
            if ($data->isEmpty()) {
                $obj = array();
            } else {
                foreach ($data as $d) {
                    $obj[] = (object)[
                        "id" => $d->id,
                        "pic" => $d->pic,
                        "uraian" => $d->uraian,
                        "dicek_oleh" => $d->verif,
                        "tgl_cek" => $d->checked_at,
                        "hasil_notulen" => $d->hasil,
                        "ket" => $d->ket,
                    ];
                }
            }

            return response()->json($obj);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 200,
                'message' => 'Gagal',
            ], 500);
        }
    }

    public function detail_jadwal_meet_person($meet_id)
    {
        try {
            //code...
            $jadwal = JadwalMeeting::find($meet_id);
            $karyawan_id = auth()->user()->karyawan_id;
            //Peserta
            $peserta = array();
            $hasil_meet = array();
            $hasil_notulen = array();
            $riwayat = array();
            $kehadiran = '';

            if ($jadwal->PesertaMeeting->count() > 0) {
                foreach ($jadwal->PesertaMeeting as $j) {
                    $peserta[] = array(
                        'id' => $j->id,
                        'nama' => $j->Karyawan->nama,
                        'jabatan' => $j->Karyawan->Divisi->nama,
                        'status' => $j->status,
                        'ket' => $j->ket,
                        'dokumen' => $j->DokumenPeserta
                    );
                    if ($j->karyawan_id == $karyawan_id) {
                        $kehadiran = $j->status;
                    }
                }
            }

            if ($jadwal->HasilMeeting->count() > 0) {
                foreach ($jadwal->HasilMeeting as $j) {
                    $hasil_meet[] = array(
                        'id' => $j->id,
                        'isi' => $j->isi,
                    );
                }
            }

            if ($jadwal->DokumenMeeting->count() > 0) {
                foreach ($jadwal->DokumenMeeting as $j) {
                    $dokumen_meet[] = array(
                        'id' => $j->id,
                        'nama' => $j->nama,

                    );
                }
            } else {
                $dokumen_meet = array();
            }

            if ($jadwal->HasilNotulen->count() > 0) {
                foreach ($jadwal->HasilNotulen as $j) {
                    $hasil_notulen[] = array(
                        'id' => $j->id,
                        'isi' => $j->uraian,
                        'hasil' => $j->hasil,
                        'karyawan_id' => $j->pic_id,
                        'pic' => $j->Karyawan->nama,
                        'divisi' => $j->Karyawan->Divisi->nama,
                        'dicek' => $j->verif_id != NULL ? $j->Karyawan->nama : '',
                        'checked_at' => Carbon::parse($j->checked_at)->format('d/m/Y H:i'),
                        'catatan' => $j->ket,
                        'is_edit' => $j->pic_id == $karyawan_id && $j->hasil == 'belum' &&  $kehadiran == 'hadir' ? true : false,
                    );
                }
                $hasil_notulen = collect($hasil_notulen)->sortBy('urutan')->values()->all();
            }

            if ($jadwal->RiwayatJadwalMeeting->count() > 0) {
                foreach ($jadwal->RiwayatJadwalMeeting as $j) {
                    $x = json_decode($j->isi);
                    $riwayat[] =  array(
                        'urutan' =>  'Meet-' . $x->urutan,
                        'judul' => $x->judul,
                        'tgl_meeting' => $x->tgl_meeting,
                        'mulai' =>  $x->mulai,
                        'selesai' =>  $x->selesai,
                        'lokasi' =>  $x->lokasi,
                        'status' =>  $x->status,
                        'notulen' =>   Karyawan::find($x->notulen)->nama,
                        'moderator' =>   Karyawan::find($x->moderator)->nama,
                        'pimpinan' =>   Karyawan::find($x->pimpinan)->nama,
                        'deskripsi' =>  $x->deskripsi,
                        'peserta' =>  $x->peserta,
                        'alasan_perubahan_meeting' =>  $j->ket,
                    );
                }
            }

            $obj = new stdClass();
            $obj->urutan =  'Meet-' . $jadwal->urutan;
            $obj->judul = $jadwal->judul;
            $obj->tgl_meeting =  $jadwal->tgl_meeting;
            $obj->mulai =  $jadwal->mulai;
            $obj->selesai =  $jadwal->selesai;
            $obj->lokasi =  $jadwal->Lokasi->nama;
            $obj->status =  $jadwal->status;
            $obj->notulen =   Karyawan::find($jadwal->notulen)->nama;
            $obj->moderator =   Karyawan::find($jadwal->moderator)->nama;
            $obj->pimpinan =   Karyawan::find($jadwal->pimpinan)->nama;
            $obj->deskripsi =  $jadwal->deskripsi;
            $obj->peserta =  $peserta;

            $riwayat[] =  $obj;

            $newobj = new stdClass();
            $newobj->urutan =  'Meet-' . $jadwal->urutan;
            $newobj->judul = $jadwal->judul;
            $newobj->tgl_meeting =  $jadwal->tgl_meeting;
            $newobj->mulai =  $jadwal->mulai;
            $newobj->selesai =  $jadwal->selesai;
            $newobj->lokasi =  $jadwal->lokasi;
            $newobj->status =  $jadwal->status;
            $newobj->notulen =   Karyawan::find($jadwal->notulen)->nama;
            $newobj->moderator =   Karyawan::find($jadwal->moderator)->nama;
            $newobj->pimpinan =   Karyawan::find($jadwal->pimpinan)->nama;
            $newobj->deskripsi =  $jadwal->deskripsi;
            $newobj->riwayat =  $riwayat;
            $newobj->hasil_meet =  $hasil_meet;
            $newobj->hasil_notulen =  $hasil_notulen;
            $newobj->dokumen_meet =  $dokumen_meet;


            $data = array(
                'kehadiran' => $newobj
            );

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 200,
                'message' => 'Gagal' . $th->getMessage(),
            ], 500);
        }
    }

    public function detail_jadwal_meet($id)
    {
        try {
            $jadwal = JadwalMeeting::find($id);

            //Peserta
            $peserta = array();
            $hasil_meet = array();
            $hasil_notulen = array();
            $riwayat = array();
            if ($jadwal->PesertaMeeting->count() > 0) {
                foreach ($jadwal->PesertaMeeting as $j) {
                    $peserta[] = array(
                        'id' => $j->id,
                        'nama' => $j->Karyawan->nama,
                        'jabatan' => $j->Karyawan->Divisi->nama,
                        'status' => $j->status,
                        'ket' => $j->ket,
                        'dokumen' => $j->DokumenPeserta
                    );
                }
            }

            if ($jadwal->HasilMeeting->count() > 0) {
                foreach ($jadwal->HasilMeeting as $j) {
                    $hasil_meet[] = array(
                        'id' => $j->id,
                        'isi' => $j->isi,

                    );
                }
            }

            if ($jadwal->HasilNotulen->count() > 0) {
                foreach ($jadwal->HasilNotulen as $j) {
                    $hasil_notulen[] = array(
                        'id' => $j->id,
                        'urutan' => $j->urutan,
                        'isi' => $j->uraian,
                        'hasil' => $j->hasil,
                        'karyawan_id' => $j->pic_id,
                        'pic' => $j->Karyawan->nama,
                        'jabatan' => $j->Karyawan->Divisi->nama,
                        'dicek' => $j->verif_id != NULL ? $j->Karyawan->nama : '',
                        'catatan' => $j->ket,
                        'checked_at' => Carbon::parse($j->checked_at)->format('d/m/Y H:i'),
                    );
                }
                $hasil_notulen = collect($hasil_notulen)->sortBy('urutan')->values()->all();
            }

            if ($jadwal->DokumenMeeting->count() > 0) {
                foreach ($jadwal->DokumenMeeting as $j) {
                    $dokumen_meet[] = array(
                        'id' => $j->id,
                        'nama' => $j->nama,

                    );
                }
            } else {
                $dokumen_meet = array();
            }

            if ($jadwal->RiwayatJadwalMeeting->count() > 0) {
                foreach ($jadwal->RiwayatJadwalMeeting as $j) {
                    $x = json_decode($j->isi);
                    $riwayat[] =  array(
                        'id' =>  $j->id,
                        'urutan' =>  'Meet-' . $x->urutan,
                        'judul' => $x->judul,
                        'tgl_meeting' => $x->tgl_meeting,
                        'mulai' =>  $x->mulai,
                        'selesai' =>  $x->selesai,
                        'lokasi' =>  $x->lokasi,
                        'status' =>  $x->status, //1=belum 2=dilaksanakan
                        'notulen' =>   Karyawan::find($x->notulen)->nama,
                        'moderator' =>   Karyawan::find($x->moderator)->nama,
                        'pimpinan' =>   Karyawan::find($x->pimpinan)->nama,
                        'deskripsi' =>  $x->deskripsi,
                        'peserta' =>  $x->peserta,
                        'alasan_perubahan_meeting' =>  $j->ket,
                    );
                }
            }

            $obj = new stdClass();
            $obj->urutan =  'Meet-' . $jadwal->urutan;
            $obj->judul = $jadwal->judul;
            $obj->tgl_meeting =  $jadwal->tgl_meeting;
            $obj->mulai =  $jadwal->mulai;
            $obj->selesai =  $jadwal->selesai;
            $obj->lokasi =  $jadwal->Lokasi->nama;
            $obj->status =  $jadwal->status; //1=belum 2=dilaksanakan
            $obj->notulen =   Karyawan::find($jadwal->notulen)->nama;
            $obj->moderator =   Karyawan::find($jadwal->moderator)->nama;
            $obj->pimpinan =   Karyawan::find($jadwal->pimpinan)->nama;
            $obj->deskripsi =  $jadwal->deskripsi;
            $obj->peserta =  $peserta;
            $obj->alasan_pembatalan_meeting = $jadwal->ket_batal;

            $riwayat[] =  $obj;

            $newobj = new stdClass();
            $newobj->urutan =  'Meet-' . $jadwal->urutan;
            $newobj->judul = $jadwal->judul;
            $newobj->tgl_meeting =  $jadwal->tgl_meeting;
            $newobj->mulai =  $jadwal->mulai;
            $newobj->selesai =  $jadwal->selesai;
            $newobj->lokasi =  $jadwal->Lokasi->nama;
            $newobj->status =  $jadwal->status; //1=belum 2=dilaksanakan
            $newobj->notulen =   Karyawan::find($jadwal->notulen)->nama;
            $newobj->moderator =   Karyawan::find($jadwal->moderator)->nama;
            $newobj->pimpinan =   Karyawan::find($jadwal->pimpinan)->nama;
            $newobj->deskripsi =  $jadwal->deskripsi;
            $newobj->peserta =  $peserta;
            $newobj->riwayat =  $riwayat;
            $newobj->hasil_meet =  $hasil_meet;
            $newobj->hasil_notulen =  $hasil_notulen;
            $newobj->dokumen_meet =  $dokumen_meet;

            return $newobj;
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 200,
                'message' => 'Gagal' . $th->getMessage(),
            ], 500);
        }
    }

    public function cetakUndangan($id)
    {
        $data = $this->detail_jadwal_meet($id);
        // return view('page.meeting.undangan', compact('data'));
        return PDF::loadView('page.meeting.undangan', compact('data'))->setPaper('a4', 'potrait')->stream('undangan.pdf');
    }

    public function cetakHasil($id, Request $request)
    {
        $jadwal = JadwalMeeting::find($id);

        $imploded = explode(',', $request->dokumen);

        $dokumen_meet = DokumenMeeting::whereIn('id', $imploded)->get();

        $data = array();
        $hasil_meet = array();
        $hasil_notulen = array();

        if ($jadwal->HasilMeeting->count() > 0) {
            foreach ($jadwal->HasilMeeting as $j) {
                $hasil_meet[] = array(
                    'id' => $j->id,
                    'isi' => $j->isi,

                );
            }
        }

        if ($jadwal->HasilNotulen->count() > 0) {
            foreach ($jadwal->HasilNotulen as $j) {
                $hasil_notulen[] = array(
                    'id' => $j->id,
                    'isi' => $j->uraian,
                    'urutan' => $j->urutan,
                    'hasil' => $j->hasil,
                    'pic' => $j->Karyawan->nama,
                    'dicek' => $j->verif_id != NULL ? $j->Karyawan->nama : '',
                    'catatan' => $j->ket,
                    'checked_at' => Carbon::parse($j->checked_at)->format('d/m/Y H:i'),

                );
            }
            $hasil_notulen = collect($hasil_notulen)->sortBy('urutan')->values()->all();
        }

        $data = new stdClass();
        $data->urutan =  'Meet-' . $jadwal->urutan;
        $data->judul = $jadwal->judul;
        $data->tgl_meeting = Carbon::parse($jadwal->tgl_meeting)->locale('id')->isoFormat('dddd, D MMMM Y');
        $data->tgl_simpan =  Carbon::parse($jadwal->updated_at)->locale('id')->isoFormat('dddd, D MMMM Y');
        $data->mulai =  $jadwal->mulai;
        $data->selesai =  $jadwal->selesai;
        $data->lokasi =  $jadwal->Lokasi->nama;
        $data->status =  $jadwal->status; //1=belum 2=dilaksanakan
        $data->notulen =   Karyawan::find($jadwal->notulen)->nama;
        $data->moderator =   Karyawan::find($jadwal->moderator)->nama;
        $data->pimpinan =   Karyawan::find($jadwal->pimpinan)->nama;
        $data->deskripsi =  $jadwal->deskripsi;
        $data->hasil_meet =  $hasil_meet;
        $data->hasil_notulen =  $hasil_notulen;
        $data->pimpinan =  Karyawan::find($jadwal->pimpinan)->nama;
        $data->dokumen_meet =  $dokumen_meet;


        return PDF::loadView('page.meeting.hasil', compact('data'))
            ->setPaper('a4', 'potrait')->stream('undangan.pdf');
        // return view('page.meeting.hasil', compact('data'));
    }


    public function upload_dokumen(Request $request)
    {
        $obj =  json_decode(json_encode($request->all()), FALSE);
        if (count($obj->dokumentasi) > 0) {
            for ($j = 0; $j < count($request->dokumentasi); $j++) {
                $original = $request->dokumentasi[$j]->getClientOriginalName();
                $randomCollectionName = Str::uuid()->toString();
                $extension = $request->dokumentasi[$j]->getClientOriginalExtension();
                $file = $randomCollectionName . '.' . $extension;
                //FTP
                // Storage::disk('ftp')->put($file, fopen($request->dokumentasi[$j], 'r+'));
                //GCS
                $storeFile = $request->dokumentasi[$j]->storeAs("test", $file, "gcs");
                Storage::disk('gcs')->url($storeFile);

                DokumenMeeting::create([
                    'meeting_id' => $obj->id,
                    'nama' => $file,
                    'original' => $original,
                ]);
            }
        }
    }
    // {
    //     foreach ($request->file('image') as $image) {
    //         $randomCollectionName = uniqid('collection_', false);
    //         $randomCollectionName = Str::uuid()->toString();

    //         $set = app('firebase.firestore')->database()->collection($randomCollectionName);
    //         $firebase_storage_path = 'Images/';
    //         $name = $set->id();detail_jadwal_meet
    //         $localfolder = public_path('firebase-temp-uploads') . '/';
    //         $extension = $image->getClientOriginalExtension();
    //         $file      = $name . '.' . $extension;

    //         if ($image->move($localfolder, $file)) {
    //             $uploadedfile = fopen($localfolder . $file, 'r');
    //             $x =  app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);
    //             $downloadUrl[] = $x->signedUrl(new \DateTime('+1 hour'));
    //             unlink($localfolder . $file);
    //         }
    //     }
    //     return $downloadUrl;
    // }

    public function show_dokumen_ftp(Request $request)
    {
        //GCS
        $name = $request->name;
        $filePath = '/test/' . $name; // Path to your file in GCS
        // Stream the file directly using the configured GCS disk
        return Storage::disk('gcs')->response($filePath);

        //FTP
        // $name = $request->name;
        // // Check if the file exists in the storage
        // if (Storage::disk('ftp')->exists($name)) {
        //     $fileStream = Storage::disk('gcs')->readStream($name);
        //     return response()->stream(function () use ($fileStream) {
        //         fpassthru($fileStream);
        //     }, 200, [
        //         'Content-Type' => Storage::disk('ftp')->mimeType($name),
        //         'Content-Disposition' => 'inline; filename="' . $name . '"',
        //     ]);
        // } else {
        //     // If the file doesn't exist, return an appropriate response
        //     return response()->json(['error' => 'File not found.'], 404);
        // }
    }

    // public function upload_dokumen_ftp(Request $request)
    // {

    //     dd($request->file('file'));
    //      foreach ($request->file('file') as $image) {
    //         $randomCollectionName = Str::uuid()->toString();
    //         $extension = $image->getClientOriginalExtension();
    //         $file = $randomCollectionName . '.' . $extension;
    //         Storage::disk('ftp')->put($file, fopen($image, 'r+'));
    //     }
    // }

    // public function upload_dokumen(Request $request)
    // {
    //     dd($request->all());
    //     $image = $request->file('image'); //image file from frontend

    //     $randomCollectionName = uniqid('collection_', false);
    //     $randomCollectionName = Str::uuid()->toString();

    //     $student   = app('firebase.firestore')->database()->collection($randomCollectionName);
    //     $firebase_storage_path = 'Images/';
    //     $name     = $student->id();
    //     $localfolder = public_path('firebase-temp-uploads') .'/';
    //     $extension = $image->getClientOriginalExtension();
    //     $file      = $name. '.' . $extension;
    //     if ($image->move($localfolder, $file)) {
    //       $uploadedfile = fopen($localfolder.$file, 'r');
    //      $x =  app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);
    //      $downloadUrl = $x->signedUrl(new \DateTime('+1 hour'));
    //       unlink($localfolder . $file);

    //     }
    //     return $downloadUrl;
    // }
}
