<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
/**
 *
 * @property int $id
 */

class ResUser extends Model
{
    protected $table = 'res_users';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $guarded = ['*'];



    public function crnubeSpreadsheetUser(): HasOne
    {
        return $this->hasOne(CrnubeSpreadsheetUser::class,'id', 'id');
    }
}
