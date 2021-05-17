<?php

namespace App\Http\Controllers;

use App\Models\DaftarPegawai;
use App\Models\dompetPegawai;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
// use Illuminate\Support\Facades\Http;
// use PhpParser\Node\Stmt\TryCatch;

class daftarPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = DaftarPegawai::get();
        // $lenght = DaftarPegawai::count();
        // for($i=0;$i<$lenght;$i++){
        //     $resultFind = DaftarPegawai::findOrFail($result[0]["id"]);
        //     $response[$i] =[
        //         'id' => $result[$i]['id'],
        //         'name' => $result[$i]['name'],
        //         'no_hp' => $result[$i]['no_hp'],
        //         'alamat' => $result[$i]['alamat'],
        //         'find' => [$resultFind],
        //     ];  
        // }
        $response =$result;
        // $response =[
        //     'nama'=>$result[0]['name'],
        // ];
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
        $validator = Validator::make($request->all(),[
            'name' => ['required'],
            // 'no_hp' => ['required', 'numeric'],
            // 'alamat' => ['required']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $id_dompet = uniqid();
            DaftarPegawai::create([
                'id_dompet'=>$id_dompet,
                'name'=>$request->name,
                'no_hp'=>$request->no_hp,
                'alamat'=>$request->alamat
            ]);
            dompetPegawai::create([
                'id_dompet'=>$id_dompet,
                'name'=>$request->name 
            ]);
            $response=[
                'message' => "Success",
            ];
            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed".$e->errorInfo
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
        $result=DaftarPegawai::findOrFail($id);
        $response = [$result];
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
        $result = DaftarPegawai::findOrFail($id);
        $result2 = dompetPegawai::where('id_dompet',$result->id_dompet);
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            // 'no_hp' => ['required', 'numeric'],
            // 'alamat' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $result->update($request->all());
            $result2->update([
                'name'=>$request->name
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
        $result = DaftarPegawai::findOrFail($id);
        $result2 = dompetPegawai::where("id_dompet",$result->id_dompet);
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
