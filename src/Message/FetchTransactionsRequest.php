<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

class FetchTransactionsRequest extends AbstractRequest
{
    protected const PATH = '/merchant/%s/order/%s';

    /**
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('orderId');

        $data = [];

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
        $this->validate('orderId');

        $host = \rtrim(parent::getEndpoint(), '/');

        return $host . \sprintf(self::PATH, $this->getMerchantId(), $this->getOrderId());
    }

    public function getHttpMethod(): string
    {
        return 'GET';
    }
}
