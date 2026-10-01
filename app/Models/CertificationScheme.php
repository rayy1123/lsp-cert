<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificationScheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'scheme_code',
        'scheme_name',
        'description',
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class, 'scheme_id');
    }
}
