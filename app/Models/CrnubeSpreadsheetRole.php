<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
/**
 * @property string $name
 */
class CrnubeSpreadsheetRole extends Model
{
    use HasFactory;

    protected $table = 'crnube_spreadsheet_roles';
    final public const DEFAULT_ROLE_ID = 1;
    public $incrementing = true;


    protected $fillable = ['name'];

    public function users(): HasMany
    {
        return $this->hasMany(CrnubeSpreadsheetUser::class);
    }

}
