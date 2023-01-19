<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

use Omnipay\Amex\CustomerBrowser;
use Omnipay\Amex\Exception\InvalidCustomerBrowserException;
use Omnipay\Amex\Helper\ExpirationMonthNormalizer;
use Omnipay\Amex\Helper\ExpirationYearNormalizer;

final class AuthenticatePayerRequest extends AbstractRequest
{
    private const API_OPERATION = 'AUTHENTICATE_PAYER';

    private const PATH = '/merchant/%s/order/%s/transaction/%s';

    /**
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws InvalidCustomerBrowserException
     */
    public function getData(): array
    {
        $this->validate('currency', 'amount', 'card', 'customerBrowser');

        $data = [
            'apiOperation' => self::API_OPERATION,
            'order' => [
                'currency' => $this->getCurrency(),
                'amount' => $this->getAmount(),
            ],
            'sourceOfFunds' => [
                'provided' => [
                    'card' => $this->getCardData(),
                ],
            ],
        ];

        $data['device'] = $this->getDeviceData();

        if ($this->getReturnUrl() !== null) {
            $data['authentication']['redirectResponseUrl'] = $this->getReturnUrl();
        }

        if ($this->getCorrelationId()) {
            $data['correlationId'] = $this->getCorrelationId();
        }

        if ($this->getCard()->getBillingAddress1()) {
            $data['billing']['address'] = $this->getBillingData();
        }

        if ($this->getCard()->getShippingAddress1()) {
            $data['shipping']['address'] = $this->getShippingData();
        } elseif ($this->getCard()->getBillingAddress1()) {
            $data['shipping']['address']['sameAsBilling'] = 'SAME';
        }

        return $data;
    }

    public function getCustomerBrowser(): CustomerBrowser
    {
        return $this->getParameter('customerBrowser');
    }

    /**
     * @return static
     */
    public function setCustomerBrowser(array $value): self
    {
        return $this->setParameter('customerBrowser', new CustomerBrowser($value));
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
        $card = [
            'number' => $this->getCard()->getNumber(),
            'securityCode' => $this->getCard()->getCvv(),
            'expiry' => [
                'month' => ExpirationMonthNormalizer::normalizer($this->getCard()->getExpiryMonth()),
                'year' => ExpirationYearNormalizer::normalizer($this->getCard()->getExpiryYear()),
            ],
        ];

        if ($this->getCard()->getFirstName()) {
            $card['nameOnCard'] = $this->getCard()->getFirstName() . ' ' . $this->getCard()->getLastName();
        }

        return $card;
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
            'browserDetails' => [
                '3DSecureChallengeWindowSize' => $customerBrowser->get3DSecureChallengeWindowSize() ?? 'FULL_SCREEN',
                'acceptHeaders' => $customerBrowser->getAcceptHeaders() ?? 'text/html',
                'colorDepth' => $customerBrowser->getColorDepth() ?? 24,
                'javaEnabled' => $customerBrowser->getJavaEnabled() !== null
                    ? $customerBrowser->getJavaEnabled()
                    : true,
                'language' => $customerBrowser->getLanguage() ?? 'en-US',
                'screenHeight' => $customerBrowser->getScreenHeight() ?? 1080,
                'screenWidth' => $customerBrowser->getScreenWidth() ?? 1920,
                'timeZone' => $customerBrowser->getTimeZone() ?? 60,

            ],
        ];
    }

    private function getBillingData(): array
    {
        $card = $this->getCard();

        $billing = [];

        if ($card->getBillingAddress1()) {
            $billing['street'] = $card->getBillingAddress1();
        }

        if ($card->getBillingAddress2()) {
            $billing['street2'] = $card->getBillingAddress2();
        }

        if ($card->getCity()) {
            $billing['city'] = $card->getCity();
        }

        if ($card->getCountry()) {
            $billing['country'] = $card->getCountry();
        }

        if ($card->getBillingPostcode()) {
            $billing['postcodeZip'] = $card->getBillingPostcode();
        }

        if ($card->getCompany()) {
            $billing['company'] = $card->getCompany();
        }

        if ($card->getBillingState()) {
            $billing['stateProvince'] = $card->getBillingState();
        }

        return $billing;
    }

    private function getShippingData(): array
    {
        $card = $this->getCard();

        $shipping = [];

        if ($card->getShippingAddress1()) {
            $shipping['street'] = $card->getShippingAddress1();
        }

        if ($card->getShippingAddress1()) {
            $shipping['street'] = $card->getShippingAddress1();
        }

        if ($card->getShippingAddress2()) {
            $shipping['street2'] = $card->getShippingAddress2();
        }

        if ($card->getCity()) {
            $shipping['city'] = $card->getCity();
        }

        if ($card->getCountry()) {
            $shipping['country'] = $card->getCountry();
        }

        if ($card->getShippingPostcode()) {
            $shipping['postcodeZip'] = $card->getShippingPostcode();
        }

        if ($card->getCompany()) {
            $shipping['company'] = $card->getCompany();
        }

        if ($card->getShippingState()) {
            $shipping['stateProvince'] = $card->getShippingState();
        }

        return $shipping;
    }
}
