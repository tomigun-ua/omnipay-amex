<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

use Omnipay\Amex\CustomerBrowser;
use Omnipay\Amex\Exception\InvalidCustomerBrowserException;

final class AuthenticatePayerRequest extends AbstractRequest
{
    use BillingDataTrait;
    use ShippingDataTrait;
    use CardDataTrait;

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
}
