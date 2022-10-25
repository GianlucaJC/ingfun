<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class story_all extends Model
{
    protected $table="story_all";
	protected $casts = [
    'created_at' => "datetime:Y-m-d H:i:s",
];
	use HasFactory;
}
