<?php

namespace Swis\JsonApi\Client\Interfaces;

interface DocumentClientInterface
{
    /**
     * @param string $endpoint
     *
     * @return \Swis\JsonApi\Client\Interfaces\DocumentInterface
     */
    public function get(string $endpoint): DocumentInterface;

    /**
     * @param string                                                $endpoint
     * @param \Swis\JsonApi\Client\Interfaces\ItemDocumentInterface $document
     *
     * @return \Swis\JsonApi\Client\Interfaces\DocumentInterface
     */
    public function patch(string $endpoint, ItemDocumentInterface $document): DocumentInterface;

    /**
     * @param string                                                $endpoint
     * @param \Swis\JsonApi\Client\Interfaces\ItemDocumentInterface $document
     *
     * @return \Swis\JsonApi\Client\Interfaces\DocumentInterface
     */
    public function post(string $endpoint, ItemDocumentInterface $document): DocumentInterface;

    /**
     * @param string $endpoint
     *
     * @return \Swis\JsonApi\Client\Interfaces\DocumentInterface
     */
    public function delete(string $endpoint): DocumentInterface;

    /**
     * @return string
     */
    public function getBaseUri(): string;

    /**
     * @param string $baseUri
     */
    public function setBaseUri(string $baseUri);
}
