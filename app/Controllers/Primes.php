<?php

namespace App\Controllers;

use App\Libraries\PrimesMachine;

class Primes extends BaseController
{
    private const MAX_VALUE = PHP_INT_MAX;
    private const NUMERIC_RULE = 'required|integer|greater_than[0]|less_than['.self::MAX_VALUE.']';

    // =================
    // GET METHODS
    // =================
    public function nextGet()
    {
        $num = intval($this->request->getGet('num')) ?? -1;
        return $this->next($num);
    }

    public function prevGet()
    {
        $num = intval($this->request->getGet('num')) ?? -1;
        return $this->prev($num);
    }

    public function betweenGet()
    {
        $num1 = intval($this->request->getGet('num1')) ?? -1;
        $num2 = intval($this->request->getGet('num2')) ?? -1;
        return $this->between($num1, $num2);
    }

    // =================
    // POST METHODS
    // =================
    public function nextPost()
    {
        try {
            $data = $this->request->getJSON(true);
        } catch (\Exception $e) {
            return $this->prepareResponse($this->errorPayload('Invalid JSON'), 400);
        }
        $num = $data["num"] ?? -1;
        $num = intval($num) ?? -1;
        return $this->next($num);
    }
    
    public function prevPost()
    {
        try {
            $data = $this->request->getJSON(true);
        } catch (\Exception $e) {
            return $this->prepareResponse($this->errorPayload('Invalid JSON'), 400);
        }
        $num = $data["num"] ?? -1;
        $num = intval($num) ?? -1;
        return $this->prev($num);
    }
    
    public function betweenPost()
    {
        try {
            $data = $this->request->getJSON(true);
        } catch (\Exception $e) {
            return $this->prepareResponse($this->errorPayload('Invalid JSON'), 400);
        }
        $num1 = $data['num1'] ?? -1;
        $num1 = intval($num1) ?? -1;
        $num2 = $data['num2'] ?? -1;
        $num2 = intval($num2) ?? -1;
        return $this->between($num1, $num2);
    }

    // =================
    // URL METHODS
    // =================

    public function next(int $a)
    {

        if ($err = $this->validateNumber($a)){
            return $this->prepareResponse($err, 400);
        }
        
        return $this->prepareResponse($this->successPayload(PrimesMachine::next($a)));
    }
    
    public function prev(int $a)
    {

        if ($err = $this->validateNumber($a)) {
            return $this->prepareResponse($err, 400);
        }

        return $this->prepareResponse($this->successPayload(PrimesMachine::prev($a)));

    }

    public function between(int $a, int $b)
    {
        $rules = [
            'num1' => self::NUMERIC_RULE,
            'num2' => self::NUMERIC_RULE,
        ];

        
        if (!$this->validateData(["num1" => $a, "num2" => $b], $rules)) {
            return $this->prepareResponse($this->errorPayload($this->validator->getErrors()), 400);
        }
        if ($b < $a) {
            return $this->prepareResponse($this->errorPayload('the second must be greater than the first'), 400);
        }


        return $this->prepareResponse($this->successPayload(PrimesMachine::between($a, $b)));
    }

    private function validateNumber(int $num)
    {
        $rules = [
            'num' => self::NUMERIC_RULE,
        ];

        if (!$this->validateData(["num" => $num], $rules)) {
            return $this->errorPayload($this->validator->getErrors());
        }

        return null;
    }

    private function prepareResponse($data, $statusCode = 200)
    {
        return $this->response->setStatusCode($statusCode)->setHeader('Content-Type', 'application/json; charset=UTF-8')->setJSON($data);
    }

    private function errorPayload($message)
    {
        return [
            'success' => false,
            'data' => $message,
        ];
    }

    private function successPayload($data)
    {
        return [
            'success' => true,
            'data' => $data,
        ];
    }
}
