<?php


namespace Kitar\Dynamodb\Debugbar;

use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\DataCollectorInterface;
use DebugBar\DataCollector\Renderable;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;


/**
 * Collector for Models.
 */
class DynamodbCollector extends DataCollector implements DataCollectorInterface, Renderable
{
    public $data = [];

    public function __construct(Request $request)
    {

    }

    public function collect()
    {

        $data = $this->data;
        return ['data' => $data, 'count' => count($this->data)];
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'dynamodb';
    }

    /**
     * {@inheritDoc}
     */
    public function getWidgets()
    {
        return [
            "dynamodb" => [
                "icon" => "bolt",
                "widget" => "PhpDebugBar.Widgets.VariableListWidget",
                "map" => "dynamodb.data",
                "default" => "{}"
            ],
            'dynamodb:badge' => [
                'map' => 'dynamodb.count',
                'default' => 0
            ]
        ];
    }
}
