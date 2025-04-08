<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MoonShine\Fields\Relationships\BelongsToMany;
/**
 * @property string name
 */
class CrnubeSpreadsheetAccount extends Model
{
    protected $table = 'account_account';

    protected $primaryKey = 'id';



    public function concepto() : BelongsTo
    {
        return $this->belongsToMany(CrnubeSpreedsheatConceptos::class, 'crnube_spreadsheet_conceptos' , 'account_id', 'concepto_id');
    }
//    {
//        return $this->belongsTo(CrnubeSpreedsheatConceptos::class, 'concept_id');
//    }

}
