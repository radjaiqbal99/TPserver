<?php

namespace App\Http\Controllers;

use App\Models\DaftarKasir;
use App\Models\dompetKasir;
use Illuminate\Http\Request;
// use App\Models\daftarKasir;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
// use Illuminate\Support\Facades\Http;
// use PhpParser\Node\Stmt\TryCatch;

class daftarKasirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = DaftarKasir::get();
        $response = $result;
        return response()->json($response, Response::HTTP_OK);
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
            'name' => ['required'],
            'username' => ['required'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $id_dompet = uniqid();
            DaftarKasir::create([
                'id_dompet' => $id_dompet,
                'name' => $request->name,
                'username' => $request->username,
                'password' => $request->password
            ]);
            dompetKasir::create([
                'id_dompet' => $id_dompet,
                'name' => $request->name,
                'saldo'=>0
            ]);
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
        $result = DaftarKasir::findOrFail($id);
        $response = [
            'message' => "Success Get Data",
            'data' => $result
        ];
        return response()->json($response, Response::HTTP_OK);
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
        $result = DaftarKasir::findOrFail($id);
        $result2 = dompetKasir::where('id_dompet', $result->id_dompet);
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'username' => ['required'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $result->update($request->all());
            $result2->update([
                'name' => $request->name
            ]);
            $response = [
                'message' => "Success Update",
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
        $result = DaftarKasir::findOrFail($id);
        $result2 = dompetKasir::where("id_dompet", $result->id_dompet);
        try {
            $result->delete();
            $result2->delete();
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
