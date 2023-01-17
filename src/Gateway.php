<?php

declare(strict_types=1);

namespace Omnipay\Amex;

use Omnipay\Amex\Message\CheckStatusRequest;
use Omnipay\Amex\Message\InitiateAuthenticationRequest;

class Gateway extends \Omnipay\Common\AbstractGateway
{
    public function getName(): string
    {
        return 'Amex';
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

    public function initiateAuthentication(array $parameters = []): \Omnipay\Common\Message\RequestInterface
    {
        return $this->createRequest(InitiateAuthenticationRequest::class, $parameters);
    }

    /**
     * @return static
     */
    public function setHost(string $value): self
    {
        return $this->setParameter('host', $value);
    }

    public function getHost(): ?string
    {
        return $this->getParameter('host');
    }

    /**
     * @return static
     */
    public function setMerchantId(string $value): self
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getMerchantId(): string
    {
        return $this->getParameter('merchantId');
    }

    /**
     * @return static
     */
    public function setApiVersion(string $value): self
    {
        return $this->setParameter('apiVersion', $value);
    }

    /**
     * @return string
     */
    public function getApiVersion(): string
    {
        return $this->getParameter('apiVersion');
    }

    /**
     * @return static
     */
    public function setPassword(string $value): self
    {
        return $this->setParameter('password', $value);
    }

    public function getPassword(): string
    {
        return $this->getParameter('password');
    }
}
