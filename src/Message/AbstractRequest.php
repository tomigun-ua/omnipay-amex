<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    private const DEFAULT_ENDPOINT = 'https://gateway-na.americanexpress.com/api/rest/version';

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
    public function setVersion(string $value): self
    {
        return $this->setParameter('version', $value);
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->getParameter('version');
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

        $body = $data ? \http_build_query($data, '', '&') : null;
        $httpResponse = $this->httpClient->request(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            $headers,
            $body
        );

        return new Response(
            $this,
            $httpResponse->getBody()
        );
    }

    public function getEndpoint(): string
    {
        $host = $this->getHost();

        return !empty($host) ? $host : self::DEFAULT_ENDPOINT;
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
