<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

use Omnipay\Common\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

class Response extends \Omnipay\Common\Message\AbstractResponse
{
    private const SUCCESS_STATUSES = ['SUCCESS', 'OPERATING'];

    public function __construct(RequestInterface $request, $data)
    {
        if ($data instanceof StreamInterface) {
            $body = $data->getContents();
            $data->rewind();
        } else {
            $body = $data;
        }

        parent::__construct($request, \json_decode($body, true));
    }

    public function isSuccessful(): bool
    {
        return isset($this->data['status']) && \in_array($this->data['status'], self::SUCCESS_STATUSES);
    }
}
