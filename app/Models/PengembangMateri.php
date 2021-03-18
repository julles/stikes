<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengembangMateri extends Model
{
    protected $primaryKey = "id_pm";

    protected $table = "pengembang_materi";

    public $timestamps = false;

    protected $guarded = [];

    public function pm_assign()
    {
        return $this->hasOne(PmAssign::class, 'id_pm');
    }
}
