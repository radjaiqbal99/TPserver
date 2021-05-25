<?php

namespace App\Http\Controllers;

use App\Models\bonTruk;
use App\Models\transaksiBonTruk;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class bonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = bonTruk::get();
        // $result2 = transaksibonTruk::get();
        for ($i = 0; $i < count($result); $i++) {
            $transaksi = transaksiBonTruk::where('id_bon', $result[$i]['id_bon'])->get();
            $response[$i] = [
                'id' => $result[$i]['id'],
                'id_dompet' => $result[$i]['id_bon'],
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
