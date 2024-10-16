<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MoonShine\ChangeLog\Traits\HasChangeLog;

/**
 * @property string $name
 */

/**
 * @property string value
 */

class CrnubeSpreadsheetTax extends Model
{
    use HasFactory;
    use HasChangeLog;

    protected $table = 'crnube_spreadsheet_taxes';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'value'];


    private mixed $value;

    public function getFormattedValueAttribute(): string
    {
        return number_format($this->value, 2, '.', ',');
    }
}
