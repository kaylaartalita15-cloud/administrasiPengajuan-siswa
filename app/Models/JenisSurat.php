<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSurat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_surat',
        'keterangan',
        'is_active',
    ];

    public function pengajuans(): HasMany
    {
        return $this->hasMany(Pengajuan::class);
    }
}
