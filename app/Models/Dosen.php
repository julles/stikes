<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $guarded = ['verifikasi_password'];

    protected $primaryKey = "id_dosen";

    public $timestamps = false;

    protected $table = "dosen";

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->flag_login = 1;
            $model->password_plain = $model->password;
            $model->password = \Hash::make($model->password);
        });

        self::updating(function ($model) {
            $model->password_plain = $model->password;
            $model->password = \Hash::make($model->password);
        });
    }
}
