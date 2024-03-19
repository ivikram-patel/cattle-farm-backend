<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListModel extends Model
{
    use HasFactory;

    protected $table = 'list-detail';

    protected $fillable = [
        'id',
        'first_name',
        'middle_name',
        'surname',
        'gender',
        'type',
        'created_at',
        'updated_at'
    ];
}
