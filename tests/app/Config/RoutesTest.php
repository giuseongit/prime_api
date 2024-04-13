<?php

namespace App\Libraries;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class RoutesTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function test404()
    {
        $response = $this->get('/dummy')->response();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $json = json_decode($response->getJSON(), true);
        $this->assertEquals(["success" => false, "data" => "Page not found"], $json);
    }
}
