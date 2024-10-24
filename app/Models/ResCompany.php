<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use MoonShine\Traits\Models\HasMoonShineSocialite;
/**
 * @property string $name
 */

class ResCompany extends Model
{

    use HasFactory;


    protected $table = 'res_company';
    protected $primaryKey = 'id';



    protected $fillable = ['name'];



    public static function find(mixed $company): ResCompany
    {
      //Obtener el nombre de la empresa con el id

        return self::query()->find($company);

    }
    // Una compañia tiene muchos departamentos
    public function departments(): HasMany
    {
        return $this->hasMany(HrDepartment::class);
    }

    // Una compañia tiene muchos jobs
    public function jobs(): HasMany
    {
        return $this->hasMany(HrJob::class);
    }
}
