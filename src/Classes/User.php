<?php
namespace Cobonto\Classes;

use Cobonto\Classes\Roles\Role;
use Cobonto\Classes\Traits\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use LaravelArdent\Ardent\Ardent;

class User extends Ardent implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Model,Authenticatable, Authorizable, CanResetPassword;
    public $autoHydrateEntityFromInput = true;    // hydrates on new entries' validation
    public $forceEntityHydrationFromInput = true; // hydrates whenever validation is called
    public $autoPurgeRedundantAttributes = true;
    public static $passwordAttributes  = ['password'];
    public $autoHashPasswordAttributes = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','lastname','mobile','email','password','active','password_confirmation','role_id','last_login'
    ];
    public static $rules = [
        'firstname' => 'required|alpha|between:3,255',
        'lastname' => 'required|alpha|between:3,255',
        'email' => 'required|email',
        'active' => 'required|boolean',
        'password' => 'between:6,300',
        'mobile'=>'string',
        'role_id' => 'required|numeric',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $guarded = [
        'password', 'remember_token',
    ];

    /**
     *  Get user by email
     * @param string $email
     * @param bool|false $returnId
     * @return bool|mixed|static
     */
    public static function getByEmail($email, $returnId=false)
    {
        $result = \DB::table('users')->where('email',$email)->first();
        if($result)
            return ($returnId)? (int)$result->id:  true;

        else
            return false;
    }

    /**
     * Get role object
     * @return Role
     */
    public function role()
    {
        return Role::find($this->role_id);
    }
    /**
     * The roles that belong to the user.
     */
}