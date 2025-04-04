<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrnubeSpreadsheetHeader extends Model
{
    protected $table = 'crnube_spreadsheet_headers';

    protected $primaryKey = 'id';

    public $incrementing = true;



    public function company() : BelongsTo
    {
        return $this->belongsTo(ResCompany::class, 'company_id', 'id');
    }
}
