<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apotek extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'nama',
        'rujukan',
        'rumah_sakit',
        'obat',
        'harga',
        'total',
        'apoteker',        
    ];

    protected $casts = [
        'obat' => 'array',
        'harga' => 'array',
    ];

    public function getObatAtribute($value){
        return array_map(fn ($item) => trim($item, '"'), explode(',', $value));
    }

    public function getHargaAtribute($value){
        return array_map(fn ($item) => trim($item, '"'), explode(',', $value));
    }
}
