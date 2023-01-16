<?php

declare(strict_types=1);

namespace Omnipay\Amex;

use Omnipay\Amex\Message\CheckStatusRequest;

class Gateway extends \Omnipay\Common\AbstractGateway
{
    public function getName(): string
    {
        return 'OmnipayAmex';
    }

    public function getDefaultParameters(): array
    {
        return [
            'host' => '',
            'merchantId' => '',
            'password' => '',
            'apiVersion' => '69',
            'testMode' => false,
        ];
    }

    public function checkStatus(array $parameters = []): \Omnipay\Common\Message\RequestInterface
    {
        return $this->createRequest(CheckStatusRequest::class, $parameters);
    }
}
