<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'scheme_id',
        'registration_number',
        'full_name',
        'email',
        'phone_number',
        'address',
        'status',
    ];

    public function scheme()
    {
        return $this->belongsTo(CertificationScheme::class, 'scheme_id');
    }
}
