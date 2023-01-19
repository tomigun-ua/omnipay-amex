<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    private const DEFAULT_HOST = 'https://gateway-na.americanexpress.com';
    private const DEFAULT_ENDPOINT = '/api/rest/version';

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
    public function setOrderId(string $value): self
    {
        return $this->setParameter('orderId', $value);
    }

    public function getOrderId(): string
    {
        return (string)$this->getParameter('orderId');
    }

    /**
     * @return static
     */
    public function setCorrelationId(string $value): self
    {
        return $this->setParameter('correlationId', $value);
    }

    public function getCorrelationId(): ?string
    {
        return $this->getParameter('correlationId');
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

    public function sendData($data): \Omnipay\Common\Message\ResponseInterface
    {
        $headers = $this->getHeaders();

        $body = ($this->getHttpMethod() !== 'GET' && $data)
            ? \json_encode($data)
            : null;

        $httpResponse = $this->httpClient->request(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            $headers,
            $body
        );

        return new Response(
            $this,
            $httpResponse
        );
    }

    public function getEndpoint(): string
    {
        $host = !empty($this->getHost()) ? \rtrim($this->getHost(), '/') : self::DEFAULT_HOST;

        return $host . self::DEFAULT_ENDPOINT . '/' . $this->getApiVersion();
    }

    public function getHeaders(): array
    {
        return [
            'Authorization' => 'Basic ' . \base64_encode(
                \sprintf(
                    '%s:%s',
                    'merchant.' . $this->getMerchantId(),
                    $this->getPassword()
                )
            ),
        ];
    }

    public function getHttpMethod(): string
    {
        return 'GET';
    }
}
