<?php
/**
 * Created by PhpStorm.
 * User: jquijano
 * Date: 2018-12-26
 * Time: 20:39
 */
namespace App\Http\Controllers;
use App\Models\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class JoybirdController extends Controller
{

	private $columnNames = [
		0 => 'bought_number',
		1 => 'bought_item',
		2 => 'sold_number',
		3 => 'sold_item',
		4 => 'default_purchase_price',
		5 => 'container_unit_price',
		6 => 'JAQ',
		7 => 'total_purchased',
		8 => 'vendors'
	];

	public function getChart()
	{
		/* data deferred to client-side call so promise can be used */

		return view('chart');
	}

	public function getChartData() : string
	{
		/* for the sake of brevity, re-using existing SP and getting all rows */
		$sql = "CALL joybird.JoybirdSales(0, 200, 'vendors', 'ASC')";
		$rows = DB::select($sql);

		/* aggregate the data */
		$dto = [];
		foreach ($rows as $row) {
			if ($row->total_purchased != null) { // exclude the null rows
				if (!isset($dto[$row->vendors])) {
					$dto[$row->vendors] = 0;  // ensure we don't operate on an element/value that doesn't exist.
				}

				$dto[$row->vendors] += $row->total_purchased;
			}
		}

		return json_encode($dto);
	}

	public function getReport()
	{
		return view('joybird');
	}

	//Because DataTables passes a query string, we cannot use Laravel routes, i.e. /1/43/67
	public function getSalesByPage(Request $request) : string
	{

		/* pick up the needed values passed by DataTables */
		$start = $request->input('start');
		$draw = $request->input('draw');
		$length = $request->input('length');
		$orderby = $request->input('order');

		/* validate to ensure we have some values so our unit tests will pass */
		$start = empty($start) ? 0 : $start;
		$draw = empty($draw) ? 1 : $draw;
		$length = empty($length) ? 25 : $length;
		$orderby = empty($orderby) ? [0 => ['column' => 0, 'dir' => 'asc']] : $orderby;

		$sql = "CALL joybird.JoybirdSales($start, $length, '" . $this->columnNames[$orderby[0]['column']] . "', '" . strtoupper($orderby[0]['dir']) . "')";

		Log::Create(
			['message' => str_replace("'", "''", $sql), 'added' => date('Y-m-d H:i:s')]
		);

		$rows = DB::select($sql);

		$noRecords = $this->getSalesCount();

		$result = [
			'draw'	=> $draw,
			'recordsTotal' => $noRecords,
			'recordsFiltered' => $noRecords,
			'data' => $this->toArray($rows)
		];

		return json_encode($result);
	}

	public function getSalesCount() : int
	{
		$result = DB::select("CALL joybird.JoybirdSalesCount()");

		return count($result);
	}

	private function toArray($array) : array
	{
		$result = [];
		foreach($array as $row) {
			$item = [];
			foreach($row as $key => $value) {
				$item[] = $value;
			}
			$result[] = $item;
		}

		return $result;
	}
}