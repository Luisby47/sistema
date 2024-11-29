<?php

namespace App\Models;

use App\Notifications\PasswordResetNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo; //Importante esto para BelongsTo
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use MoonShine\Traits\Models\HasMoonShineSocialite;
use Illuminate\Auth\Passwords\CanResetPassword;


/**
 * @property mixed $role_id
 * @property mixed $email
 */
class CrnubeSpreadsheetUser extends Authenticatable
{
    use HasMoonShineSocialite;
    use HasFactory;
    use Notifiable;
    use CanResetPassword;


    protected $table = 'crnube_spreadsheet_users';
    protected $primaryKey = 'id';


    public $incrementing = false; // Para que no sea autoincremental ya que el sera el id de la tabla res_users

    protected $fillable = [
        'id', 'role_id', 'email', 'password', 'name', 'avatar',
    ];

    protected $hidden = [
        'password', 'remember_token', // Espacio Ocultos
    ];





    public function role() : BelongsTo //Importante esto para BelongsTo
    {
        return $this->belongsTo(CrnubeSpreadsheetRole::class , 'role_id' ,'id'  ); // Se debe cambiar el nombre de la clase
    }

    public function isSuperAdmin(): bool
    {
        return $this->role_id ===  CrnubeSpreadsheetRole::DEFAULT_ROLE_ID;
    }


    public function resUser() : BelongsTo
    {
        return $this->belongsTo(ResUser::class, 'id', 'id');
    }






    public function sendPasswordResetNotification($token): void
    {
        $url = route('password.reset', ['token' => $token, 'email' => $this->email]);
        $this->notify(new PasswordResetNotification($url));
    }
}
