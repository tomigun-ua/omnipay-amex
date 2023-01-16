<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

use GuzzleHttp\Psr7\Stream;
use Omnipay\Common\Message\RequestInterface;

class Response extends \Omnipay\Common\Message\AbstractResponse
{
    private const SUCCESS_STATUS = 'SUCCESS';

    public function __construct(RequestInterface $request, $data)
    {
        if ($data instanceof Stream) {
            $data = $data->getContents();
        }

        parent::__construct($request, \json_decode($data, true));
    }

    public function isSuccessful(): bool
    {
        return isset($this->data->status) && $this->data->status === self::SUCCESS_STATUS;
    }
}
