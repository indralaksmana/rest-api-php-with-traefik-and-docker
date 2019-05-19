<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use ResponseHTTP\Response\Traits\ModelREST;
use Spatie\Permission\Traits\HasRoles;  
use Tymon\JWTAuth\Contracts\JWTSubject;


/**
 * Class User
 * @package App
 */
class User extends Model implements
	AuthenticatableContract,
	AuthorizableContract,
	JWTSubject
{
	use Authenticatable, Authorizable, HasRoles, ModelREST;

	protected $table = 'users';

	const CONST_WORD = 'password';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'email', 'name', self::CONST_WORD, 'username', 'phone', 'address'
	];
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [self::CONST_WORD, 'remember_token', 'created_at', 'updated_at'];

	public function __construct(array $attributes = [])
	{
		$this->bootREST();
		parent::__construct($attributes);
	}

	private function bootREST()
	{
		$this->setBasicPath();
		$this->setLinks([
			[
				'self',
				$this->href(env('USER_SERVICE_URL', 'user.lm.local', true, true)),
				$this->method('GET')
			]
		]);
	}

	public function setPasswordAttribute($value)
	{
		$this->attributes[ self::CONST_WORD ] = Hash::make($value);
	}

	/**
	 * Get the identifier that will be stored in the subject claim of the JWT.
	 *
	 * @return mixed
	 */
	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Return a key value array, containing any custom claims to be added to the JWT.
	 *
	 * @return array
	 */
	public function getJWTCustomClaims()
	{
		return [];
	}
}
