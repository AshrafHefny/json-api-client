<?php

namespace Swis\JsonApi\Fixtures\Guzzle;

use GuzzleHttp\Handler\MockHandler;
use Psr\Http\Message\RequestInterface;
use Swis\JsonApi\Fixtures\FixtureResponseBuilderInterface;

class FixturesHandler extends MockHandler
{
    /**
     * @var string|\Swis\JsonApi\Fixtures\FixtureResponseBuilderInterface
     */
    protected $responseBuilder;

    /**
     * @param \Swis\JsonApi\Fixtures\FixtureResponseBuilderInterface $responseBuilder
     * @param array|null                                             $queue
     * @param callable|null                                          $onFulfilled
     * @param callable|null                                          $onRejected
     */
    public function __construct(
        FixtureResponseBuilderInterface $responseBuilder,
        array $queue = null,
        callable $onFulfilled = null,
        callable $onRejected = null
    ) {
        $this->responseBuilder = $responseBuilder;
        parent::__construct($queue, $onFulfilled, $onRejected);
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param array                              $options
     *
     * @throws \RuntimeException
     *
     * @return \GuzzleHttp\Promise\Promise|\GuzzleHttp\Promise\PromiseInterface
     */
    public function __invoke(RequestInterface $request, array $options)
    {
        $this->append($this->responseBuilder->build($request));

        return parent::__invoke($request, $options);
    }
}
