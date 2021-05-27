<?php

namespace App\Http\Controllers;

use App\Models\hargaPasir;
use App\Models\upahKasir;
use App\Models\upahPegawai;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class hargaPasirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = hargaPasir::get();
        $response = $result;
        return response()->json($response, Response::HTTP_OK);
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
        $validator = Validator::make($request->all(), [
            'jumlah' => ['required'],
            'harga' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            hargaPasir::create($request->all());
            $response = [
                'message' => "Success",
            ];
            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);
        }
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
        $result = hargaPasir::findOrFail($id)->first();
        // $result1 = hargaPasir::where('id',$id)->first();

        $validator = Validator::make($request->all(), [
            'jumlah' => ['required'],
            'harga' => ['required']
        ]);
        upahKasir::where('satuan', $result->jumlah)->update(['satuan'=>$request->jumlah]);
        upahPegawai::where('satuan', $result->jumlah)->update(['satuan'=>$request->jumlah]);
        // $updateUpahKasir->update([
        //     'satuan' => $request->jumlah,
        // ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {

            // $updateUpahPegawai ->update([
            //     'satuan'=>$request->jumlah,
            // ]);
            $result->update($request->all());
            $response = [
                'message' => "success",
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = hargaPasir::findOrFail($id);
        upahKasir::where('satuan', $result->jumlah)->delete();
        upahPegawai::where('satuan', $result->jumlah)->delete();
        try {
            $result->delete();
            $response = [
                'message' => "Success Delete",
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed" . $e->errorInfo
            ]);
        }
    }
}
