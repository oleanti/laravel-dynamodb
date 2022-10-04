<?php

namespace Kitar\Dynamodb;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Event;

class DynamodbServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        if (config('app.debug')) {
            try {
                $dynamodbCollector = $this->app->make('Kitar\Dynamodb\Debugbar\DynamodbCollector');
                $debugbar = \App::make('debugbar');
                $debugbar->addCollector($dynamodbCollector);
            } catch(BindingResolutionException $e){
                // did not find debugbar
            } catch (\Exception $e) {
                throw new \Exception('Cannot add Dynamodb Collector: ' . $e->getMessage(), $e->getCode(), $e);

            }
            Event::listen(function (Events\QueryExecuted $event) {
                $key = 'Query-'.microtime();
                $data = [
                    'params' => $event->params,
                    'result' => $event->result
                ];
                $debugbar = \App::make('debugbar');
                $debugbar['dynamodb']->data[$key] = $debugbar['dynamodb']->formatVar($data);
            });
            Event::listen(function (Events\GetItemExecuted $event) {
                $key = 'GetItem-'.microtime();
                $data = [
                    'params' => $event->params,
                    'result' => $event->result
                ];
                $debugbar = \App::make('debugbar');
                $debugbar['dynamodb']->data[$key] = $debugbar['dynamodb']->formatVar($data);
            });
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        // Add database driver.
        $this->app->resolving('db', function ($db) {
            $db->extend('dynamodb', function ($config, $name) {
                $config['name'] = $name;
                return new Connection($config);
            });
        });
    }
}
