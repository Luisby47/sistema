<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use MoonShine\Traits\Models\HasMoonShineSocialite;
/**
 * @property string $name
 */

class HrJob extends Model
{
    use HasMoonShineSocialite;
    use HasFactory;
    use Notifiable;


    protected $table = 'hr_job';
    protected $primaryKey = 'id';

    protected $fillable = ['name'];

    // Un puesto pertenece a una compañia
    public function company() : BelongsTo
    {
        return $this->belongsTo(ResCompany::class, 'company_id', 'id');
    }

    // Un puesto pertenece a un departamento
    public function department() : BelongsTo
    {
        return $this->belongsTo(HrDepartment::class, 'company_id', 'id');
    }


    // Un Puesto tiene muchos empleados
    public function employees(): HasMany
    {
        return $this->hasMany(HrEmployee::class, 'job_id', 'id');
    }
}
