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

class HrDepartment extends Model
{
    use HasMoonShineSocialite;
    use HasFactory;
    use Notifiable;


    protected $table = 'hr_department';
    protected $primaryKey = 'id';

    protected $fillable = ['name'];

    // Un departamento pertenece a una compañia
    public function company() : BelongsTo
    {
        return $this->belongsTo(ResCompany::class, 'company_id', 'id');
    }

    // Un departamento tiene muchos jobs
    public function jobs(): HasMany
    {
        return $this->hasMany(HrJob::class);
    }
}
