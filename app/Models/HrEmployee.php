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
 * @property string identification_id
 * @property integer department_id
 */

class HrEmployee extends Model
{
    use HasMoonShineSocialite;
    use HasFactory;
    use Notifiable;


    protected $table = 'hr_employee';
    protected $primaryKey = 'id';

    protected $fillable = ['name_related'];


    public function getDepartmentName()
    {
        return HrDepartment::query()->where('id', $this->department_id)->first()->name;
    }
    // Un empleado pertenece a una compañia
    public function department() : BelongsTo
    {
        return $this->belongsTo(HrDepartment::class   );
    }

    // Un empleado pertenece a una puesto
    public function job() : BelongsTo
    {
        return $this->belongsTo(HrJob::class , 'job_id' ,'id'  );
    }

//    public function conceptos(): HasMany
//    {
//        return $this->hasMany(CrnubeSpreedsheatConceptos::class);
//    }

    public function conceptos()
    {
        return $this->belongsToMany(
            CrnubeSpreedsheatConceptos::class, // Modelo de conceptos
            'crnube_spreadsheet_conceptos_employees', // Nombre de la tabla pivot
            'employee_id', // Foreign key de empleado
            'concept_id' // Foreign key de concepto
        )->withPivot('value'); // Columna adicional en el pivot
    }
}
