<?php

namespace App\Http\Controllers;

use App\Helpers\Apiformatter;
use App\Models\Apotek;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class ApotekController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search_nama;
        $limit = $request->limit;

        $apoteks = Apotek::where('nama', 'LIKE', '%' .$search. '%')->limit($limit)->get();
        
        $apoteks = Apotek::all();
        if ($apoteks) {
            # kalo data berhasil diambil
            return Apiformatter::createAPI(200, 'success', $apoteks);
        } else {
            # kalo gagal
            return Apiformatter::createAPI(400, "failed");
        }
        
    }

    public function token()
    {
        return csrf_token();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required',
                'rujukan' => 'required',
                'rumah_sakit' =>  $request->rujukan == 1 ? 'required' : '',
                'obat' => 'required',
                'harga' => 'required',
                'apoteker' => 'required',
            ]);

            $hargaSatuan = explode(',' , $request->harga);
            $totalHarga =0;

            foreach ($hargaSatuan as $hrg) {
                    $hrg = (int) trim($hrg, '"');
                    $totalHarga += $hrg;
            }   
            $apoteks = Apotek::create([
                'nama' => $request->nama,
                'rujukan' => $request->rujukan,
                'rumah_sakit' =>  $request->rujukan == 1 ? $request->rumah_sakit : null,
                'obat' => $request->obat,
                'harga' => $request->harga,
                'total' => $totalHarga,
                'apoteker' => $request->apoteker,
            ]);

            $added = Apotek::where('id', $apoteks->id)->first();

            if($added) {
                return ApiFormatter::createAPI(200, 'success', $apoteks);
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error){
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }



    /**
     * Display the specified resource.
     */
    public function show($id) //masih null
    {
        try {
            $pharmacy = Apotek::find($id);

        if ($pharmacy) {
            return Apiformatter::createAPI(200, 'success', $pharmacy);
        }else {
            return Apiformatter::createAPI(400, 'failed');
        }
        } catch (Exception $error) {
            return Apiformatter::createAPI(400, 'error', $error->getMessage());
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apotek $apotek)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama' => 'required',
                'rujukan' => 'required',
                'rumah_sakit' =>  $request->rujukan == 1 ? 'required' : '',
                'obat' => 'required',
                'harga' => 'required',
                'apoteker' => 'required',
            ]);

            
             $hargaSatuan = explode(',' , $request->harga);
            $totalHarga =0;

            foreach ($hargaSatuan as $hrg) {
                    $hrg = (int) trim($hrg, '"');
                    $totalHarga += $hrg;
            }

            $apotek = Apotek::find($id);
            $apotek->update([
                'nama' => $request->nama,
                'rujukan' => $request->rujukan,
                'rumah_sakit' =>  $request->rujukan == 1 ? $request->rumah_sakit : null,
                'obat' => $request->obat,
                'harga' => $request->harga,
                'total' => $totalHarga,
                'apoteker' => $request->apoteker,
            ]);

            $edited = Apotek::where('id', $apotek->id)->first();
            if ($edited) {
                return Apiformatter::createAPI(200, 'success', $apotek);

            } else {
                return Apiformatter::createAPI(400, 'failed');

            }
            

        } catch (Exception $error) {
            return Apiformatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apotek $apotek, $id)
    {
        try {
            $apotek = Apotek::find($id);
            $deleted = $apotek->delete();

            if ($deleted) {
                return Apiformatter::createAPI(200, 'success', $apotek);

            } else {
                return Apiformatter::createAPI(400, 'failed');

            }
        } catch (Exception $error) {
            return Apiformatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function trash(){
        try {
            $apotek = Apotek::onlyTrashed()->get();

            if ($apotek) {
                return Apiformatter::createAPI(200, 'success', $apotek);

            } else {
                return Apiformatter::createAPI(400, 'failed');

            }
        } catch (Exception $error) {
            return Apiformatter::createAPI(400, 'error', $error->getMessage());

        }
    }

    public function restore($id){
        try {
            $apotek = Apotek::onlyTrashed()->where('id', $id);
            $apotek->restore();

            $restored = Apotek::where('id', $id)->first();

            if ($restored) {
                return Apiformatter::createAPI(200, 'success', $apotek);

            } else {
                return Apiformatter::createAPI(400, 'failed');

            }
        } catch (Exception $error) {
            return Apiformatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function permanentDelete($id){
        try {
            $apotek = Apotek::onlyTrashed()->where('id', $id);
            $deleted = $apotek->forceDelete();
            if ($deleted) {
                return Apiformatter::createAPI(200, 'success', $apotek);

            } else {
                return Apiformatter::createAPI(400, 'failed');

            }
        } catch (Exception $error) {
            return Apiformatter::createAPI(400, 'error', $error->getMessage());
        }
    }
}
