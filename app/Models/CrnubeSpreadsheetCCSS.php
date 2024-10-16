<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MoonShine\ChangeLog\Traits\HasChangeLog;

/**
 * @property string $id
 */
class CrnubeSpreadsheetCCSS extends Model
{
    use HasFactory;
    use HasChangeLog;

    protected $table = 'crnube_spreadsheet_c_c_s_s';
    protected $primaryKey = 'id';
    protected $fillable = ['tax_range','col_range','percentage'];

    protected $attributes = [
        'percentage' => 0,
    ];

}
