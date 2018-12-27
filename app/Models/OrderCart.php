<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCart extends Model
{

	protected $table = 'order_cart';

	protected $primaryKey = 'cart_id';

	public $timestamps = false;


}
