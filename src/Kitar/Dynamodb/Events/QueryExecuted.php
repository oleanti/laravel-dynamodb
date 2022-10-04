<?php

namespace Kitar\Dynamodb\Events;

use Illuminate\Foundation\Events\Dispatchable;

class QueryExecuted
{
    use Dispatchable;

    public $params;
    public $result;

    public function __construct($params, $result)
    {
        $this->params = $params;
        $this->result = $result;
    }
}
