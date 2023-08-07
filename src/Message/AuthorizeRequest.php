<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

use Omnipay\Amex\CustomerBrowser;
use Omnipay\Amex\Exception\InvalidCustomerBrowserException;

class AuthorizeRequest extends AbstractRequest
{
    use BillingDataTrait;
    use ShippingDataTrait;
    use CardDataTrait;

    protected const API_OPERATION = 'AUTHORIZE';

    protected const PATH = '/merchant/%s/order/%s/transaction/%s';

    /**
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws InvalidCustomerBrowserException
     */
    public function getData(): array
    {
        $this->validate('orderId', 'currency', 'amount', 'card', 'transactionId');

        $data = [
            'apiOperation' => static::API_OPERATION,
            'order' => [
                'currency' => $this->getCurrency(),
                'amount' => $this->getAmount(),
                'reference' => $this->getOrderId(),
            ],
            'sourceOfFunds' => [
                'provided' => [
                    'card' => $this->getCardData(),
                ],
                'type' => 'CARD',
            ],
        ];

        if ($this->getCustomerBrowser()) {
            $data['device'] = $this->getDeviceData();
        }

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

    public function getCustomerBrowser(): ?CustomerBrowser
    {
        return $this->getParameter('customerBrowser');
    }

    /**
     * @return static
     */
    public function setCustomerBrowser(array $value): self
    {
        return $this->setParameter('customerBrowser', $value ? new CustomerBrowser($value) : null);
    }

    /**
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getEndpoint(): string
    {
        $host = \rtrim(parent::getEndpoint(), '/');

        return $host . \sprintf(self::PATH, $this->getMerchantId(), $this->getOrderId(), $this->getTransactionId());
    }

    public function getHttpMethod(): string
    {
        return 'PUT';
    }

    /**
     * @throws InvalidCustomerBrowserException
     */
    private function getDeviceData(): array
    {
        $customerBrowser = $this->getCustomerBrowser();

        $customerBrowser->validate();

        return [
            'browser' => $customerBrowser->getUserAgent(),
            'ipAddress' => $customerBrowser->getIpAddress(),
        ];
    }
}
