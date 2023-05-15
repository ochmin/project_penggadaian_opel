<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use Excel;
use App\Exports\ReportsExport;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function exportPDF()
    {
        //ambil data yang akan di tampilkan pada pdf , bisa juga dengan where atau dengan eloquent lainnya dan jangan gunakan pagination
        //jangan lupa konvert data jadi array dengan toArray()
        $data = Report::with('response')->get()->toArray();
        //kirim dta yang di ambil kepada view yang akan di tampilkan , kirim dengan install
        view()->share('reports', $data);
        //panggil view vlade yang akan di cetak pdf serta data yang akan digunakan
        $pdf = PDF::loadView('print', $data)->setPaper('a4', 'landscape');
        //download pdf file dengan nama tertentu
        return $pdf->download('data_pengaduan_keseluruhan.pdf');
    }

    public function printPDF($id)
    {
        // ambil data dari database, ambil data yg akan ditampilkan pada pdf, bisa juga dengan where atau eloquent lainnya dan jangan gunakan pagination
        // jangan lupa konvert data jadi array dengan toArray()
        $data = Report::where('id', $id)->get()->toArray();
         // kirim data yg diambil kepada view yg akan ditampilkan, kirim dengan inisial
        view()->share('reports', $data);
        // panggil view blade yang akan dicetak pdf serta data yg akan digunakan
        $pdf = PDF::loadView('print', $data);
        // download PDF file dengan nama tertentu
        return $pdf->download('data_pengaduan_keseluruhan.pdf');
    }

    public function exportExcel()
    {
        //nama file yang akan terdownload
        //selain .xlsx juga bisa .csv
        $file_name =
        'data_keseluruhan_pengaduan.xlsx';
        //memanggil file ReportExport dan mendownloadnya dengan nama seperti $file_name
        return Excel::download(new ReportsExport, $file_name);
    }
    
     public function landing()

    {
        // ASC : ascending -> terkecil
        // terbesar 1-100 / a-z
        // DESC : descending -> terbesar terkecil 100-1 /z-a

        $reports = Report::orderBy('created_at', 'DESC')->simplePaginate(2);
        return view('landing', compact('reports'));
    }
// request $request ditambahkan karena pada halaman data ada fitur search nya dan akan mengambil teks yg di imput search
    public function data(Request $request)
        {
//ambil data yang di input ke input yang name nya search
            $search = $request->search;
            //where akan mencari data berdasarkan column nama 
            //data yang d ambil adalah daa 'LIKE' (terdapat) teks yang di masukan ke input search
            // contoh : ngisi input search dengan 'fem'
            // bakal nyari ke db yg column nama nya ada isi 'fem' nya 
            $reports = Report::with('response')->where('name', 'LIKE', '%' . $search . '%')->orderBy
            ('created_at', 'DESC')->get();
            return view('data', compact('reports'));
        }
    
        public function dataPetugas(Request $request)
        {
            $search = $request->search; 
            $reports = Report::with('response')->where('name', 'LIKE', '%' . $search . '%')->orderBy
            ('created_at', 'DESC')->get();
            return view('data_petugas', compact('reports'));
        }


    public function auth(Request $request)
    {
//validasi 
$request->validate([
    'email' => 'required|email:dns',
    'password' => 'required',

]);

//ambil data dan simpan di variabel
$user = $request->only('email', 'password');
//simpan data ke auth dengan auth::attempt
//cek proses penyimpanan ke auth berhasil ato tidak lewat if else
if (Auth::attempt($user)) {
    if (Auth::user()->role == 'admin') {
        return redirect()->route('data');
    }elseif (Auth::user()->role == 'petugas') {
        return redirect()->route('data.petugas');
    }
}else{
    return redirect()->back()->with('gagal', 'Gagal login, coba lagi!');
}
}
    public function logout(){
        Auth::logout();return redirect('/login');
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
        //validasi
        $request->validate([
            'nik' => 'required',
           'umur' => 'required' ,
           'email' => 'required' ,
            'name' => 'required',
            'no_telp' => 'required',
            'pesan' => 'required',
            'foto' => 'required|image|mimes:jpeg,jpg,png,svg',
        ]);

//pindah foto ke folder public
        $patch = public_path('assets/image/');
        $image = $request->file('foto');
        $imgName = rand() .'.' . $image->extension(); //foto.jpg : 1234.jpg
        $image->move($patch, $imgName);
        // tambah data ke db
        Report::create([
            'nik' => $request->nik,
            'umur' => $request->umur,
            'email' => $request->email,
            'name' => $request->name,
            'no_telp' => $request->no_telp,
            'pesan' => $request->pesan,
            'foto' => $imgName,
        ]);

        return redirect()->back()->with('success', 'berhasil menambahkan pengaduan');
        }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Report::where('id', $id)->FirstOrfail();
        unlink('assets/image/'.$data['foto']);
        //hapus data dari database
        $data->delete();
        //setelah di kembalikan ke halaman awal
        Response::where('report_id', $id)->delete();
        return redirect()->back();
    }
}
