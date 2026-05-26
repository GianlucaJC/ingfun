<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appaltinew_info extends Model
{
    protected $table="appaltinew_info";
    use HasFactory;
    protected $fillable = [
        'id_appalto',
        'm_e',
        'id_box',
        'luogo_incontro',
        'orario_incontro',
        'luogo_destinazione',
        'ora_destinazione',
        'data_servizio',
        'numero_persone',
        'servizi_svolti',
        'nome_salma',
        'note',
        'note_fatturazione',
        'hide',
        'locked',
        'last_wa_message',
    ];
}
