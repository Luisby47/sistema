<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 */
class CrnubeSpreedsheatConceptos extends Model{
    use HasFactory;

    protected $table = 'crnube_spreadsheet_conceptos';
    final public const DEFAULT_ROLE_ID = 3;
    protected $fillable = ['tipo_concepto','tipo_valor','motivo','valor','observaciones'];

    protected $attributes = [
        'observaciones' => '',
    ];
}
