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
    protected $fillable = [' name', 'type', 'value_type', 'value', 'note'];

    protected $attributes = [
        'note' => '',
    ];


    public function employee() : BelongsTo
    {
        return $this->belongsTo(HrEmployee::class);
    }



    public function company() : BelongsTo
    {
        return $this->belongsTo(ResCompany::class, 'company_id', 'id');
    }

}
