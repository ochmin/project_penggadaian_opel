<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Report;

class Response extends Model
{
    use HasFactory;
    protected $fillable = [
        'report_id',
        'status',
        'pesan',
    ];
// belongTo: disambungkan dengan nama (pk nya ada dimana)
// table berperan sebagai pk
// nama fungsi == nama model fk


    public function report()
    {
        return $this->belongTo
        (Report::class);
    }
}
