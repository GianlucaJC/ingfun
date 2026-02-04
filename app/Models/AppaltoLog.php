<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppaltoLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'appalto_id',
        'm_e',
        'box_id',
        'action_type',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appalto()
    {
        return $this->belongsTo(Appaltinew::class);
    }
}