<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

use Omnipay\Amex\Helper\ExpirationMonthNormalizer;
use Omnipay\Amex\Helper\ExpirationYearNormalizer;

class AuthorizeRequest extends AbstractRequest
{
    use BillingDataTrait;
    use ShippingDataTrait;

    protected const API_OPERATION = 'AUTHORIZE';

    protected const PATH = '/merchant/%s/order/%s/transaction/%s';

    /**
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('orderId', 'currency', 'amount', 'card');

        $data = [
            'apiOperation' => self::API_OPERATION,
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
        ];

        if ($this->getCorrelationId()) {
            $data['correlationId'] = $this->getCorrelationId();
        }

        if ($this->getTransactionReference()) {
            $data['authentication']['transactionId'] = $this->getTransactionReference();
            $data['order']['reference'] = $this->getOrderId();
            $data['transaction']['reference'] = $this->getOrderId();
        }

        if (!$this->getTransactionReference() && $this->getCard()->getBillingAddress1()) {
            $data['billing']['address'] = $this->getBillingData();

            if (!$this->getCard()->getShippingAddress1()) {
                $data['shipping']['address']['sameAsBilling'] = 'SAME';
            } else {
                $data['shipping']['address'] = $this->getShippingData();
            }
        }

        if ($this->getCard()->getEmail()) {
            $data['shipping']['contact']['email'] = $this->getCard()->getEmail();
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

        return $host . \sprintf(self::PATH, $this->getMerchantId(), $this->getOrderId(), $this->getTransactionId());
    }

    public function getHttpMethod(): string
    {
        return 'PUT';
    }

    protected function getCardData(): array
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
