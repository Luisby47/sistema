<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MoonShine\ChangeLog\Traits\HasChangeLog;

/**
 * @property string $name
 */
class CrnubeSpreedsheatConceptos extends Model{
    use HasFactory;
    use HasChangeLog;

    protected $table = 'crnube_spreadsheet_conceptos';
    final public const DEFAULT_ROLE_ID = 3;
    protected $fillable = ['tipo_concepto','tipo_valor','motivo','valor','employee_id','observaciones'];

    protected $attributes = [
        'observaciones' => '',
    ];


    public function employee() : BelongsTo
    {
        return $this->belongsTo(HrEmployee::class);
    }
}
