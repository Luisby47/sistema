<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use MoonShine\Traits\Models\HasMoonShineSocialite;
/**
 * @property string $name_related
 */

class HrEmployee extends Model
{
    use HasMoonShineSocialite;
    use HasFactory;
    use Notifiable;


    protected $table = 'hr_employee';
    protected $primaryKey = 'id';

    protected $fillable = ['name_related'];
}
