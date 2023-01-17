<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

use Omnipay\Common\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response extends \Omnipay\Common\Message\AbstractResponse
{
    protected ResponseInterface $response;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
    ) {
        $this->response = $response;

        $data = $response->getBody();
        $body = $data->getContents();
        $data->rewind();

        parent::__construct($request, \json_decode($body, true));
    }

    public function isSuccessful(): bool
    {
        return $this->response->getStatusCode() < 300;
    }
}
