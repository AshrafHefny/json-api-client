<?php

namespace Swis\JsonApi\Tests\Guzzle;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use Swis\JsonApi\Guzzle\FixtureResponseBuilderInterface;
use Swis\JsonApi\Guzzle\FixturesHandler;
use Swis\JsonApi\Tests\AbstractTest;

class FixturesHandlerTest extends AbstractTest
{
    /**
     * @test
     */
    public function it_calls_the_fixture_response_builder_on_invoke()
    {
        $request = new Request('GET', new Uri('http://example.com'));
        /** @var \PHPUnit_Framework_MockObject_MockObject|\Swis\JsonApi\Guzzle\FixtureResponseBuilderInterface $responseBuilder */
        $responseBuilder = $this->getMockBuilder(FixtureResponseBuilderInterface::class)->getMock();
        $responseBuilder->expects($this->once())
            ->method('build')
            ->with($request)
            ->willReturn(new Response());

        $handler = new FixturesHandler($responseBuilder);
        /* @noinspection ImplicitMagicMethodCallInspection */
        $handler->__invoke($request, []);
    }
}
