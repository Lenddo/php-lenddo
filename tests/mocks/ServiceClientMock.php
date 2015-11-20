<?php

namespace Lenddo\tests\mocks;

class ServiceClientMock extends \Lenddo\ServiceClient {
    /**
     * Override the get date timestamp method so that we provide a constant time for expected testing.
     * @return string
     */
    protected function _getDateTimestamp()
    {
        return 'Sun Oct 4 21:45:10 CEST 2015';
    }

}