<?php

declare(strict_types=1);

namespace Omnipay\Amex;

use Omnipay\Amex\Message\AuthenticatePayerRequest;
use Omnipay\Amex\Message\AuthorizeRequest;
use Omnipay\Amex\Message\CaptureRequest;
use Omnipay\Amex\Message\PurchaseRequest;
use Omnipay\Amex\Message\CheckStatusRequest;
use Omnipay\Amex\Message\InitiateAuthenticationRequest;
use Omnipay\Amex\Message\RefundRequest;
use Omnipay\Amex\Message\VoidRequest;

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
            'merchantId' => null,
            'password' => null,
            'apiVersion' => '69',
            'testMode' => false,
        ];
    }

    public function checkStatus(array $parameters = []): \Omnipay\Common\Message\RequestInterface
    {
        return $this->createRequest(CheckStatusRequest::class, $parameters);
    }

    public function initiateAuthentication(array $options = []): \Omnipay\Common\Message\RequestInterface
    {
        return $this->createRequest(InitiateAuthenticationRequest::class, $options);
    }

    public function authorize(array $options = []): \Omnipay\Common\Message\RequestInterface
    {
        return $this->createRequest(AuthenticatePayerRequest::class, $options);
    }

    public function completeAuthorize(array $options = []): \Omnipay\Common\Message\RequestInterface
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }

    public function capture(array $options = []): \Omnipay\Common\Message\RequestInterface
    {
        return $this->createRequest(CaptureRequest::class, $options);
    }

    public function purchase(array $options = []): \Omnipay\Common\Message\RequestInterface
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function void(array $options = []): \Omnipay\Common\Message\RequestInterface
    {
        return $this->createRequest(VoidRequest::class, $options);
    }

    public function refund(array $options = []): \Omnipay\Common\Message\RequestInterface
    {
        return $this->createRequest(RefundRequest::class, $options);
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

    /**
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function initialize(array $parameters = array()): self
    {
        $this->validate('merchantId', 'password');

        parent::initialize($parameters);

        return $this;
    }
}
