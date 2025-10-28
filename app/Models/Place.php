<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Place
 * 
 * @property int $id
 * @property int $user_id
 * @property string $residence
 * @property string $block
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|Attendance[] $attendances
 *
 * @package App\Models
 */
class Place extends Model
{
	protected $table = 'places';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'residence',
		'block'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function attendances()
	{
		return $this->hasMany(Attendance::class);
	}
}
