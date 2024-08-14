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
            $data['transaction']['reference'] = $this->getTransactionReference();
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

        $this->buildAirlineData($data);

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

    public function getBookingReference(): ?string
    {
        return $this->getParameter('bookingReference');
    }

    public function setDocumentType(?string $value): self
    {
        return $this->setParameter('documentType', $value);
    }

    public function getDocumentType(): ?string
    {
        return $this->getParameter('documentType');
    }

    public function setPassenger(?array $value): self
    {
        return $this->setParameter('passenger', $value);
    }

    public function getPassenger(): ?array
    {
        return $this->getParameter('passenger');
    }

    public function setBookingReference(?string $value): self
    {
        return $this->setParameter('bookingReference', $value);
    }

    public function getTravelAgentCode(): ?string
    {
        return $this->getParameter('travelAgentCode');
    }

    public function setTravelAgentCode(?string $value): self
    {
        return $this->setParameter('travelAgentCode', $value);
    }

    public function getTravelAgentName(): ?string
    {
        return $this->getParameter('travelAgentName');
    }

    public function setTravelAgentName(?string $value): self
    {
        return $this->setParameter('travelAgentName', $value);
    }

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

    private function buildAirlineData(array &$data): void
    {
        if ($this->getDocumentType()) {
            $data['airline']['documentType'] = $this->getDocumentType();
        }

        if ($this->getBookingReference()) {
            $data['airline']['bookingReference'] = $this->getBookingReference();
        }

        if ($this->getTravelAgentCode()) {
            $data['airline']['ticket']['issue']['travelAgentCode'] = $this->getTravelAgentCode();
        }

        if ($this->getTravelAgentName()) {
            $data['airline']['ticket']['issue']['travelAgentName'] = $this->getTravelAgentName();
        }

        if ($this->getPassenger() !== null) {
            $data['airline']['passenger'] = \array_map(
                static fn(array $passenger) => [
                    'firstName' => $passenger['firstName'] ?? '',
                    'lastName' => $passenger['lastName'] ?? '',
                ],
                $this->getPassenger()
            );
        }
    }
}
