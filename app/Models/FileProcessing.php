<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileProcessing extends Model
{
    public $fillable = [
        'name',
        'file_path'
    ];
}
