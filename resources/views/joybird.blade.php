<?php
/**
 * Created by PhpStorm.
 * User: jquijano
 * Date: 2018-12-26
 * Time: 19:13
 */

use App\Models\OrderCart;

$test = OrderCart::where('cart_id', '<', 3623)->get()->toArray();

print_r($test);