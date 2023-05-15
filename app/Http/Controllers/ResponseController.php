<?php

namespace App\Http\Controllers;

use App\Models\Response;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function show(Response $response)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function edit($report_id)
    {
        //ambil data response yang bakal di munculin data yang di ambil data response yang report_id nya sama kaya report_id dari path dinamis {report_id}
        //kalu ada , datanya di ambil 1 atau first()
        //kenapa g pake firstofail() karena nanti bakal munculin not found view, kalau pake first() view nya bakal tetep di tampilin
        $report = Response::where('report_id', $report_id)->first();
        //karena mau kirim data {report_id} buat route updatenya jadi biar bisa dipake di balde kita simpen data patch dnamis $report_id nya ke variable baru yang bakal di compact dan di panggil di bladenya
        $reportId = $report_id;
        return view('response', compact('report', 'reportId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $report_id)
    {
        $request->validate([
            'status' => 'required',
            'pesan' => 'required',
        ]);

        //updateorcreate() fungsinya untuk melakukan update data kalo emng d db responsenya udah ada data yang punya report_id sama dengan $report_id dari path dinamis kalau gada data itu maka d create 
        //array pertama, acuan cari datanya 
        //array ke2 data yang dikirim
        //kenapa pake updateorcreate? karena response ini  kan kalo tadinya gada mau ditambahin tp kalo ada mau di update aj    

        Response::updateOrCreate(
            [
                'report_id'=> $report_id,
            ],
            [
                'status' => $request->status,
                'pesan' => $request->pesan,
            ]
            );
            //setelah berhasil arahkan ke rote yg name data petugas dengan pesan alart
            return redirect()->route('data.petugas')->with('responseSucces', 'berhasil mengubah response!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function destroy(Response $response)
    {
        //
    }
}
