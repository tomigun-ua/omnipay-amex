<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

class InitiateAuthenticationRequest extends AbstractRequest
{
    private const API_OPERATION = 'INITIATE_AUTHENTICATION';

    private const PATH = '/merchant/%s/order/%s/transaction/%s';

    /**
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('currency', 'card');

        $data = [
            'apiOperation' => self::API_OPERATION,
            'authentication' => [
                'acceptVersions' => $this->getAcceptVersions() ?? '3DS1,3DS2',
                'channel' => $this->getChannel() ?? 'PAYER_BROWSER',
            ],
            'order' => [
                'currency' => $this->getCurrency(),
            ],
            'sourceOfFunds' => [
                'provided' => [
                    'card' => [
                        'number' => $this->getCard()->getNumber(),
                    ],
                ],
            ],
        ];

        if ($this->getPurpose()) {
            $data['authentication']['purpose'] = $this->getPurpose();
        }

        if ($this->getCorrelationId()) {
            $data['correlationId'] = $this->getCorrelationId();
        }

        return $data;
    }

    public function getChannel(): ?string
    {
        return $this->getParameter('channel');
    }

    /**
     * @return static
     */
    public function setChannel(string $value): self
    {
        return $this->setParameter('channel', $value);
    }

    public function getPurpose(): ?string
    {
        return $this->getParameter('purpose');
    }

    /**
     * @return static
     */
    public function setPurpose(string $value): self
    {
        return $this->setParameter('purpose', $value);
    }

    public function getAcceptVersions(): ?string
    {
        return $this->getParameter('acceptVersions');
    }

    /**
     * @return static
     */
    public function setAcceptVersions(string $value): self
    {
        return $this->setParameter('acceptVersions', $value);
    }

    /**
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getEndpoint(): string
    {
        $this->validate('orderId', 'transactionId');

        $host = \rtrim(parent::getEndpoint(), '/');

        return $host . \sprintf(self::PATH, $this->getMerchantId(), $this->getOrderId(), $this->getOrderId());
    }

    public function getHttpMethod(): string
    {
        return 'PUT';
    }
}
