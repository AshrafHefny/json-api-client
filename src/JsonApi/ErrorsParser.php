<?php

namespace Swis\JsonApi\Client\JsonApi;

use Art4\JsonApiClient\Error as JsonApiError;
use Art4\JsonApiClient\ErrorCollection as JsonApiErrorCollection;
use Art4\JsonApiClient\ErrorSource as JsonApiErrorSource;
use Art4\JsonApiClient\Meta as JsonApiMeta;
use Swis\JsonApi\Client\Errors\Error;
use Swis\JsonApi\Client\Errors\ErrorCollection;
use Swis\JsonApi\Client\Errors\ErrorMeta;
use Swis\JsonApi\Client\Errors\ErrorSource;

class ErrorsParser
{
    /**
     * @param \Art4\JsonApiClient\ErrorCollection $errorCollection
     *
     * @return \Swis\JsonApi\Client\Errors\ErrorCollection
     */
    public function parse(JsonApiErrorCollection $errorCollection)
    {
        $errors = new ErrorCollection();
        $errorCollectionArray = $errorCollection->asArray(false);

        // Empty errors
        if (empty($errorCollectionArray)) {
            throw new \InvalidArgumentException('Error collection does not contain any errors.');
        }

        foreach ($errorCollectionArray as $error) {
            $errors->push($this->buildError($error));
        }

        return $errors;
    }

    /**
     * @param \Art4\JsonApiClient\Error $error
     *
     * @return \Swis\JsonApi\Client\Errors\Error
     */
    private function buildError(JsonApiError $error): Error
    {
        return new Error(
            $error->has('id') ? $error->get('id') : null,
            $error->has('status') ? $error->get('status') : null,
            $error->has('code') ? $error->get('code') : null,
            $error->has('title') ? $error->get('title') : null,
            $error->has('detail') ? $error->get('detail') : null,
            $error->has('source') ? $this->buildErrorSource($error->get('source')) : null,
            $error->has('meta') ? $this->buildErrorMeta($error->get('meta')) : null
        );
    }

    /**
     * @param \Art4\JsonApiClient\ErrorSource $errorSource
     *
     * @return \Swis\JsonApi\Client\Errors\ErrorSource
     */
    private function buildErrorSource(JsonApiErrorSource $errorSource): ErrorSource
    {
        return new ErrorSource(
            $errorSource->has('pointer') ? $errorSource->get('pointer') : null,
            $errorSource->has('parameter') ? $errorSource->get('parameter') : null
        );
    }

    /**
     * @param \Art4\JsonApiClient\Meta $meta
     *
     * @return \Swis\JsonApi\Client\Errors\ErrorMeta
     */
    private function buildErrorMeta(JsonApiMeta $meta): ErrorMeta
    {
        return new ErrorMeta($meta->asArray(false));
    }
}
