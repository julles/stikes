<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $primaryKey = "id_semester";

    protected $table = "semester";

    public $timestamps = false;
}
