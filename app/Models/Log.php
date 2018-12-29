<?php
/**
 * Created by PhpStorm.
 * User: jquijano
 * Date: 2018-12-29
 * Time: 15:39
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{

	protected $table = 'log';

	protected $primaryKey = 'logid';

	public $timestamps = false;

	protected $fillable = array('message', 'added');

}