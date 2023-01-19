<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

use Omnipay\Amex\Helper\ExpirationMonthNormalizer;
use Omnipay\Amex\Helper\ExpirationYearNormalizer;

final class AuthorizeRequest extends AbstractRequest
{
    private const API_OPERATION = 'AUTHORIZE';

    private const PATH = '/merchant/%s/order/%s/transaction/%s';

    /**
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('orderId', 'currency', 'amount', 'card', 'transactionReference');

        $data = [
            'apiOperation' => self::API_OPERATION,
            'authentication' => [
                'transactionId' => $this->getTransactionReference(),
            ],
            'order' => [
                'currency' => $this->getCurrency(),
                'amount' => $this->getAmount(),
                'reference' => $this->getOrderId(),
            ],
            'sourceOfFunds' => [
                'provided' => [
                    'card' => $this->getCardData(),
                ],
            ],
            'transaction' => [
                'reference' => $this->getOrderId(),
            ],
        ];

        if ($this->getCorrelationId()) {
            $data['correlationId'] = $this->getCorrelationId();
        }

        return $data;
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

    private function getCardData(): array
    {
        return [
            'number' => $this->getCard()->getNumber(),
            'securityCode' => $this->getCard()->getCvv(),
            'expiry' => [
                'month' => ExpirationMonthNormalizer::normalizer($this->getCard()->getExpiryMonth()),
                'year' => ExpirationYearNormalizer::normalizer($this->getCard()->getExpiryYear()),
            ],
        ];
    }
}
