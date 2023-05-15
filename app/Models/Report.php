<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'umur',
        'email',
        'nik',
        'no_telp',
        'pesan',
        'foto',
    ];
    // hasOne:one to one
    // table yg berperan sebagai PK
    // nama fungsi == nama model FK
    public function response()
    {
        return $this->hasOne (Response::class);
    }
}