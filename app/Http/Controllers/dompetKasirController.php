<?php

namespace App\Http\Controllers;

use App\Models\dompetKasir;
use App\Models\transaksiDompetKasir;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class dompetKasirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = dompetKasir::get();
        // $result2 = transaksiDompetKasir::get();
        for ($i = 0; $i < count($result); $i++) {
            $transaksi = transaksiDompetKasir::orderBy('id', 'DESC')->where('id_dompet', $result[$i]['id_dompet'])->get();
            $response[$i] = [
                'id' => $result[$i]['id'],
                'id_dompet' => $result[$i]['id_dompet'],
                'name' => $result[$i]['name'],
                'saldo' => $result[$i]['saldo'],
                'transaksi' => $transaksi
            ];
        };
        $Respon = $response;
        return response()->json($Respon, Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
