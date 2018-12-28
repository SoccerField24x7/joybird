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
	private $headers = [
		'CONTENT_TYPE' => 'application/json',
		'HTTP_ACCEPT' => 'application/json',
		'Cache-control' => 'no-cache',
	];

	public function testTotalRecordsEquals184()
	{
		$this->withoutMiddleware();

		$response = $this->call('get', '/1/500');

		$obj = json_decode($response->getContent());

		$this->assertEquals(184, count($obj));
	}

	public function testTotalRecordsEquals25()
	{
		$this->withoutMiddleware();

		$response = $this->call('get', '/');

		$obj = json_decode($response->getContent());

		$this->assertEquals(25, count($obj));
	}
}
