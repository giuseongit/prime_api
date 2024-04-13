<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class PrimesTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    // =================
    // GET ROUTES
    // =================
    public function testGetRoutesWithSuccess()
    {
        $tests = [
            "/get/next" => [
                "args" => ["num" => 2],
                "result" => 3,
            ],
            "/get/prev" => [
                "args" => ["num" => 3],
                "result" => 2,
            ],
            "/get/between" => [
                "args" => ["num1" => 3, "num2" => 10],
                "result" => [3, 5, 7],
            ],
        ];

        foreach ($tests as $route => $test) {
            $response = $this->get($route, $test["args"])->response();
            $this->assertEquals(200, $response->getStatusCode());
            $this->assertEquals('application/json; charset=UTF-8', $response->getHeaderLine('Content-Type'));
            $json = json_decode($response->getJSON(), true);
            $expected = [
                "success" => true,
                "data" => $test["result"],
            ];
            $failMsg = "$route failed: should have got " . json_encode($expected) . " but got " . json_encode($json);
            $this->assertEquals($expected, $json, $failMsg);
        }
    }

    public function testGetRoutesWithValidationError()
    {
        $tests = [
            "/get/next" => [
                "args" => ["num" => "a"],
                "result" => [
                    "num" => "The num field must contain a number greater than 0.",
                ],
            ],
            "/get/prev" => [
                "args" => ["num" => -1],
                "result" => [
                    "num" => "The num field must contain a number greater than 0.",
                ],
            ],
            "/get/between" => [
                "args" => ["num1" => 3],
                "result" => [
                    "num2" => "The num2 field must contain a number greater than 0.",
                ],
            ],
        ];

        foreach ($tests as $route => $test) {
            $response = $this->get($route, $test["args"])->response();
            $this->assertEquals(400, $response->getStatusCode());
            $this->assertEquals('application/json; charset=UTF-8', $response->getHeaderLine('Content-Type'));
            $json = json_decode($response->getJSON(), true);
            $expected = [
                "success" => false,
                "data" => $test["result"],
            ];
            $failMsg = "$route failed: should have got " . json_encode($expected) . " but got " . json_encode($json);
            $this->assertEquals($expected, $json, $failMsg);
        }
    }

    // =================
    // POST ROUTES
    // =================
    public function testPostRoutesWithSuccess()
    {
        $tests = [
            "/post/next" => [
                "args" => ["num" => 2],
                "result" => 3,
            ],
            "/post/prev" => [
                "args" => ["num" => 3],
                "result" => 2,
            ],
            "/post/between" => [
                "args" => ["num1" => 3, "num2" => 10],
                "result" => [3, 5, 7],
            ],
        ];

        $headers = [
            'CONTENT_TYPE' => 'application/json',
        ];
        foreach ($tests as $route => $test) {
            $response = $this->withHeaders($headers)->withBodyFormat('json')->post($route, $test["args"])->response();
            $this->assertEquals(200, $response->getStatusCode());
            $this->assertEquals('application/json; charset=UTF-8', $response->getHeaderLine('Content-Type'));
            $json = json_decode($response->getJSON(), true);
            $expected = [
                "success" => true,
                "data" => $test["result"],
            ];
            $failMsg = "$route failed: should have got " . json_encode($expected) . " but got " . json_encode($json);
            $this->assertEquals($expected, $json, $failMsg);
        }
    }

    public function testPostRoutesWithValidationError()
    {
        $tests = [
            "/post/next" => [
                "args" => ["num" => "a"],
                "result" => [
                    "num" => "The num field must contain a number greater than 0.",
                ],
            ],
            "/post/prev" => [
                "args" => ["num" => -1],
                "result" => [
                    "num" => "The num field must contain a number greater than 0.",
                ],
            ],
            "/post/between" => [
                "args" => ["num1" => 3],
                "result" => [
                    "num2" => "The num2 field must contain a number greater than 0.",
                ],
            ],
        ];

        $headers = [
            'CONTENT_TYPE' => 'application/json',
        ];
        foreach ($tests as $route => $test) {
            $response = $this->withHeaders($headers)->withBodyFormat('json')->post($route, $test["args"])->response();
            $this->assertEquals(400, $response->getStatusCode());
            $this->assertEquals('application/json; charset=UTF-8', $response->getHeaderLine('Content-Type'));
            $json = json_decode($response->getJSON(), true);
            $expected = [
                "success" => false,
                "data" => $test["result"],
            ];
            $failMsg = "$route failed: should have got " . json_encode($expected) . " but got " . json_encode($json);
            $this->assertEquals($expected, $json, $failMsg);
        }
    }

    // =================
    // URL ROUTES
    // =================
    public function testUrlRoutesWithSuccess()
    {
        $tests = [
            "/url/2/next" => [
                "result" => 3,
            ],
            "/url/3/prev" => [
                "result" => 2,
            ],
            "/url/3/and/10" => [
                "result" => [3, 5, 7],
            ],
        ];

        foreach ($tests as $route => $test) {
            $response = $this->get($route)->response();
            $this->assertEquals(200, $response->getStatusCode());
            $this->assertEquals('application/json; charset=UTF-8', $response->getHeaderLine('Content-Type'));
            $json = json_decode($response->getJSON(), true);
            $expected = [
                "success" => true,
                "data" => $test["result"],
            ];
            $failMsg = "$route failed: should have got " . json_encode($expected) . " but got " . json_encode($json);
            $this->assertEquals($expected, $json, $failMsg);
        }
    }

    public function testUrlRoutesWithValidationError()
    {
        $response = $this->get('url/-1/next')->response();
        $this->assertEquals(404, $response->getStatusCode());

        $response = $this->get('url/a/prev')->response();
        $this->assertEquals(404, $response->getStatusCode());

        $response = $this->get('url/10/and/3')->response();
        $this->assertEquals(400, $response->getStatusCode());
        $json = json_decode($response->getJSON(), true);
        $expected = [
            "success" => false,
            "data" => "the second must be greater than the first",
        ];
        $failMsg = "url/10/and/3 failed: should have got " . json_encode($expected) . " but got " . json_encode($json);
        $this->assertEquals($expected, $json, $failMsg);
    }
}