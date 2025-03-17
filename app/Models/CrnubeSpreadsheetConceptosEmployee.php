<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property integer value
 */
class CrnubeSpreadsheetConceptosEmployee extends Model
{
    protected $table = 'crnube_spreadsheet_conceptos_employees';
    protected $primaryKey = ['concepto_id', 'employee_id'];

    protected bool $isAsync = true;
    protected $fillable = [
        'employee_id',
        'concepto_id',
        'company_id',
        'value'
    ];
    public $timestamps = false;
    public $incrementing = false;

    public function conceptos() : HasMany
    {
        return $this->hasMany(CrnubeSpreedsheatConceptos::class   );
    }

    public function concepto() : BelongsTo
    {
        return $this->belongsTo(CrnubeSpreedsheatConceptos::class, 'concepto_id');
    }


}
