<?php

namespace Lenddo\tests\mocks;

class GuzzleClientMock {
    protected $_request_args;
    protected $_construct_args;

    function __construct()
    {
        $this->_construct_args = func_get_args();
    }


    function request()
    {
        $this->_request_args = func_get_args();
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestArgs()
    {
        return $this->_request_args;
    }

    /**
     * @return mixed
     */
    public function getConstructArgs()
    {
        return $this->_construct_args;
    }
}