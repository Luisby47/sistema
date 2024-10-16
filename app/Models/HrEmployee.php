<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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


    // Un empleado pertenece a una compañia
    public function department() : BelongsTo
    {
        return $this->belongsTo(HrDepartment::class  );
    }

    // Un empleado pertenece a una puesto
    public function job() : BelongsTo
    {
        return $this->belongsTo(HrJob::class , 'job_id' ,'id'  );
    }

    public function conceptos(): HasMany
    {
        return $this->hasMany(CrnubeSpreedsheatConceptos::class);
    }
}
