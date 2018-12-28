<?php
/**
 * Created by PhpStorm.
 * User: jquijano
 * Date: 2018-12-26
 * Time: 20:39
 */
namespace App\Http\Controllers;
// use App\Models\OrderCart;
use Illuminate\Support\Facades\DB;

class JoybirdController extends Controller
{
	public function getSalesByPage(int $pageNum = 1, int $pageSize = 25) : string
	{
		$beg = ($pageNum - 1) * $pageSize;
		$end = $pageNum * $pageSize;

		$result = DB::select("CALL joybird.JoybirdSales($beg, $end)");

		return json_encode($result);
	}
}