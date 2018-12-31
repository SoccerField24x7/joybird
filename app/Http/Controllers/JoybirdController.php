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

	/**
	 * Main method to load chart
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getChart()
	{
		/* data deferred to client-side call to allow it to be interactive, and to utilize a promise */

		return view('chart');
	}

	/**
	 * Endpoint to return JSON data necessary to build chart
	 *
	 * @param Request $request
	 * @return string
	 */
	public function getChartData(Request $request) : string
	{
		if(!$request->ajax()) {
			return json_encode(['Error' => 'Invalid request.']);
		}

		/* for the sake of brevity, re-using existing SP and getting all rows */
		$sql = "CALL joybird.JoybirdSales(0, 200, 'vendors', 'ASC')";

		try {
			$rows = DB::select($sql);
		} catch (\Exception $ex) {
			$error = $ex->getMessage();
			Log::Create(
				['message' => DB::connection()->getPdo()->quote($error), 'added' => date('Y-m-d H:i:s')]
			);

			return json_encode(['Error' => $error]);
		}

		Log::Create(
			['message' => DB::connection()->getPdo()->quote($sql), 'added' => date('Y-m-d H:i:s')]
		);

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

	/**
	 * Main Method to load Report
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getReport()
	{
		return view('joybird');
	}

	/**
	 *Endpoint to return JSON data necessary to build report
	 *
	 * @param Request $request
	 * @return string
	 */
	public function getSalesByPage(Request $request) : string  //Because DataTables passes a query string, we cannot use Laravel routes, i.e. /1/43/67
	{
		if(!$request->ajax()) {
			return json_encode(['Error' => 'Invalid request.']);
		}

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
			['message' => DB::connection()->getPdo()->quote($sql), 'added' => date('Y-m-d H:i:s')]
		);

		try {
			$rows = DB::select($sql);
		} catch (\Exception $ex) {
			$error = $ex->getMessage();
			Log::Create(
				['message' => DB::connection()->getPdo()->quote($error), 'added' => date('Y-m-d H:i:s')]
			);

			return json_encode(['error' => $error]);
		}

		$noRecords = $this->getSalesCount();

		$result = [
			'draw'	=> $draw,
			'recordsTotal' => $noRecords,
			'recordsFiltered' => $noRecords,
			'data' => $this->toArray($rows)
		];

		return json_encode($result);
	}

	/**
	 * Return the total record count for report data
	 *
	 * @return int
	 */
	public function getSalesCount() : int
	{
		$result = 0;

		try {
			$result = DB::select("CALL joybird.JoybirdSalesCount()");
		} catch (\Exception $ex) {
			Log::Create(
				['message' => DB::connection()->getPdo()->quote($ex->getMessage()), 'added' => date('Y-m-d H:i:s')]
			);
		}

		return count($result);
	}

	private function toArray(array $array) : array
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