<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Attendance
 * 
 * @property int $id
 * @property int $user_id
 * @property int $place_id
 * @property Carbon $date
 * @property Carbon|null $check_in
 * @property Carbon|null $check_out
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Place $place
 * @property User $user
 *
 * @package App\Models
 */
class Attendance extends Model
{
	protected $table = 'attendances';

	protected $casts = [
		'user_id' => 'int',
		'place_id' => 'int',
		'date' => 'datetime',
		'check_in' => 'datetime',
		'check_out' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'place_id',
		'date',
		'check_in',
		'check_out'
	];

	public function place()
	{
		return $this->belongsTo(Place::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
