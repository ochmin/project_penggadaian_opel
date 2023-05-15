<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penggadaian</title>
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
</head>
<body>
    <h2 class="title-table">Pesan</h2>
<div style="display: flex; justify-content: center; margin-bottom: 30px">
    <a href="/logout" style="text-align: center">Logout</a>
    <div style="margin: 0 10px"> | </div>
    <a href="index.html" style="text-align: center">Home</a>
</div>
<div style="display: flex; justify-content: flex-end; align-items:center">
    
    <form action="" method="GET">
        @csrf
        <input type="text" name="search" placeholder="cari berdasarkan nama">
        <button type="submit" class="btn-login" style="margin-top: -1px">Cari</button>
            
    </form>
    
    <a href="{{route('data')}}" style="margin-left: 10px; margin-top: -2px">Refresh</a>
    <a href="{{route('export-pdf')}}" style="margin-left: 10px; margin-top:-10px;">Cetak PDF</a>
    <a href="{{route('export.excel')}}" style="margin-left: 10px; margin-top:-10px;">Cetak Excel</a>
</div>
<div style="padding: 0 30px">
    <table>
        <thead>
        <tr>
            <th width="5%">No</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Telp</th>
            <th>Pesan</th>
            <th>Gambar</th>
            <th>Status Response</th>
            <th>Pesan Response</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
            @php
            $no = 1;
            @endphp
            @foreach($reports as $report)
            <tr>

                <td>{{$no++}}</td>
                <td>{{$report['nik']}}</td>
                <td>{{$report['nama']}}</td>\
                
                @php
                
$telp = substr_replace($report->no_telp, "62", 0, 1)
@endphp
@php

if ($report->response) {
    $pesanWA= 'Hallo' . $report->nama . '!pengaduan anda di'. 
$report->response['status']. ',berikut pesan untuk anda :'. 
$report->response['pesan'];
}

else{
    $pesanWA = 'belum ada data pegawai';
}
@endphp

<td><a href="https://wa.me/{{$telp}}?text=Hallo,%20{{$report->nama}}%20pengaduan%20anda%20akan%20kami%20cek" target="_blank">{{$telp}}</a></td>
                <td>{{$report['pengaduan']}}</td>

                <td>

                   
                    <a href="../assets/image/{{$report->foto}}"
                        target="_blank">
                        <img src="{{asset('assets/image/'.$report->foto)}}"
                        width="120">
                    </a>
                    <img src="{{asset('assets/image/'.$report->foto)}}"width="120">
                </td>
                <td>
                   
                      @if ($report->response)
                      
                      {{ $report->response['status'] }}
                      @else
                     
                      -
                      @endif
                          
                  </td>
                          
                  <td>
                                          @if ($report->response)
                                       
                                          {{ $report->response['pesan'] }}
                                          @else
                                         
                                          -
                                          @endif
                                              
                                      </td>
                <td>
                    <form action= "{{route('destroy', $report->id)}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Hapus</button>
                    </form>
                    <a href="{{route('print-pdf', $report->id)}}" method="GET" style="margin-top: -33px; margin-right: 3px; margin-left: 5px;">
                        print
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>