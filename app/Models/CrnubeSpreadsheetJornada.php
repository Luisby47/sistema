<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrnubeSpreadsheetJornada extends Model {
    use HasFactory;

    protected $table = 'crnube_spreadsheet_jornadas';
    final public const DEFAULT_ROLE_ID = 1;

    protected $primaryKey = 'id';

    protected $fillable = ['nombre', 'cant_dias'];


    // Un departamento pertenece a una compañia
    public function company() : BelongsTo
    {
        return $this->belongsTo(ResCompany::class, 'company_id', 'id');
    }
}
