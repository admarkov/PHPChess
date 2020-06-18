<?php

// ---  Response Status Enumeration ---
const STATUS_OK                 = 'ok';
const STATUS_CLIENT_ERROR       = 'client_error';
const STATUS_SERVER_ERROR       = 'server_error';
const STATUS_LOGIC_ERROR        = 'logic_error';

// --- Error Code Enumeration ---
const ERRCODE_WRONG_METHOD      = 'wrong_method';
const ERRCODE_MISSING_FIELD     = 'missing_field';
const ERRCODE_PG_ERROR          = 'pg_request_failed';
const ERRCODE_GAME_NOT_FOUND    = 'game_not_found';

// --- HTTP Method Enumeration ---
const METHOD_GET                = 'GET';
const METHOD_POST               = 'POST';

abstract class APIHandler
{
    private $requiredFields = array();
    private $requestData = array();
    private $method;

    public function __construct()
    {

    }

    protected function setMethod($method)
    {
        $this->method = $method;
    }

    protected function addRequiredField($field)
    {
        $this->requiredFields[] = $field;
    }

    protected function setRequestData($data)
    {
        $this->requestData = $data;
    }

    private function checkMethod()
    {
        if (isset($this->method)) {
            if ($this->method == $_SERVER['REQUEST_METHOD']) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    private function checkField($field)
    {
        if (!array_key_exists($field, $this->requestData)) {
            return false;
        }
        return true;
    }

    private function checkRequiredFields()
    {
        foreach ($this->requiredFields as $field) {
            if (!$this->checkField($field)) {
                return false;
            }
        }
        return true;
    }

    private function errorCodeToStatus($code)
    {
        $type = (int)($code / 100);
        if ($type == 2)
            return STATUS_LOGIC_ERROR;
        if ($type == 4)
            return STATUS_CLIENT_ERROR;
        if ($type == 5)
            return STATUS_SERVER_ERROR;
        return null;
    }

    protected function fail($httpcode, $errorcode, $details = null)
    {
        http_response_code($httpcode);
        $response = array();
        $response['status'] = $this->errorCodeToStatus($httpcode);
        $response['error']['code'] = $errorcode;
        $response['error']['details'] = $details;
        echo json_encode($response);
        return null;
    }

    protected function sendResponse($payload)
    {
        http_response_code(200);
        $response = [
            'status' => STATUS_OK,
            'payload'=> $payload
        ];
        echo json_encode($response);
        return null;
    }

    public function handleRequest()
    {
        if (!$this->checkMethod()) {
            return $this->fail(405, ERRCODE_WRONG_METHOD, "{$this->method} is expected");
        }
        if (!$this->checkRequiredFields()) {
            return $this->fail(400, ERRCODE_MISSING_FIELD);
        }
        $this->handlePreparedRequest();
    }

    protected function requestMethod()
    {
        return $this->method;
    }

    protected function requestParam($param)
    {
        if (array_key_exists($param, $this->requestData)) {
            return $this->requestData[$param];
        }
        return null;
    }

    protected abstract function handlePreparedRequest();

}