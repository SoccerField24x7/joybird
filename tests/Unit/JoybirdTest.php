<?php
/**
 * Created by PhpStorm.
 * User: jquijano
 * Date: 2018-12-27
 * Time: 20:41
 */

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;


class JoybirdTest extends TestCase
{
	/**
	 * ensure when length is "wide open" we have the correct total number of records using POST route
	 */
	public function testTotalRecordsEquals184()
	{
		$response = $this->call('post', '/report?draw=1&start=0&length=500');

		$obj = json_decode($response->getContent());

		$this->assertEquals(184, count($obj->data));
	}

	/**
	 * ensure default gives us 25 records using GET route
	 */
	public function testTotalRecordsEquals25()
	{
		$response = $this->call('get', '/report');

		$obj = json_decode($response->getContent());

		$this->assertEquals(25, count($obj->data));
	}

	/**
	 * ensure count endpoint provides correct value
	 */
	public function testRecordCountEquals184()
	{
		$response = $this->call('get', '/count');

		$count = $response->getContent();

		$this->assertEquals(184, $count);
	}
}
