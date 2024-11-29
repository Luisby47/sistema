<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
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

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (is_null($model->company_id)) {
                $model->company_id = Cache::get('company');
            }
        });
    }

    public function company() : BelongsTo
    {
        return $this->belongsTo(ResCompany::class, 'company_id', 'id');
    }

}
