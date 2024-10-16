<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrnubeSpreadsheetJornada extends Model {
    use HasFactory;

    protected $table = 'crnube_spreadsheet_jornadas';
    final public const DEFAULT_ROLE_ID = 1;

    protected $primaryKey = 'id';

    protected $fillable = ['nombre', 'cant_dias'];
}
