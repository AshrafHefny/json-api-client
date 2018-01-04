<?php

namespace Swis\JsonApi\Guzzle;

use Psr\Http\Message\RequestInterface;

interface FixtureResponseBuilderInterface
{
    /**
     * @param \Psr\Http\Message\RequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function build(RequestInterface $request);
}
