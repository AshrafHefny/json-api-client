<?php

namespace Swis\JsonApi\Client;

use Art4\JsonApiClient\Utils\Manager as JsonApiClientManger;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Swis\JsonApi\Client\Client as ApiClient;
use Swis\JsonApi\Client\DocumentClient as ApiDocumentClient;
use Swis\JsonApi\Client\Interfaces\ClientInterface as ApiClientInterface;
use Swis\JsonApi\Client\Interfaces\DocumentClientInterface as ApiDocumentClientInterface;
use Swis\JsonApi\Client\Interfaces\ParserInterface;
use Swis\JsonApi\Client\Interfaces\TypeMapperInterface;
use Swis\JsonApi\Client\JsonApi\ErrorsParser;
use Swis\JsonApi\Client\JsonApi\Hydrator;
use Swis\JsonApi\Client\JsonApi\Parser;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__).'/config/' => config_path(),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/config/jsonapi.php',
            'jsonapi'
        );

        $this->registerSharedTypeMapper();
        $this->registerApiClients();
    }

    protected function registerSharedTypeMapper()
    {
        $this->app->singleton(
            TypeMapperInterface::class,
            function () {
                return new TypeMapper();
            }
        );
    }

    protected function registerApiClients()
    {
        $this->app->bind(
            ApiClientInterface::class,
            function () {
                return new ApiClient(
                    $this->getHttpClient(),
                    config('jsonapi.base_uri'),
                    $this->getMessageFactory()
                );
            }
        );

        $this->app->bind(
            ParserInterface::class,
            function (Application $app) {
                return new Parser(
                    new JsonApiClientManger(),
                    new Hydrator($app->make(TypeMapperInterface::class)),
                    new ErrorsParser()
                );
            }
        );

        $this->app->bind(
            ApiDocumentClientInterface::class,
            function (Application $app) {
                return new ApiDocumentClient(
                    $app->make(ApiClientInterface::class),
                    new ItemDocumentSerializer(),
                    $app->make(ParserInterface::class)
                );
            }
        );
    }

    protected function getHttpClient(): HttpClient
    {
        return HttpClientDiscovery::find();
    }

    /**
     * Should return a MessageFactory which has 2 method. get.
     *
     * @return \Http\Message\MessageFactory
     */
    protected function getMessageFactory(): MessageFactory
    {
        return MessageFactoryDiscovery::find();
    }
}
