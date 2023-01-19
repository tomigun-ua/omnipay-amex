<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

class RefundRequest extends AbstractRequest
{
    protected const API_OPERATION = 'REFUND';

    protected const PATH = '/merchant/%s/order/%s/transaction/%s';

    /**
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('orderId', 'transactionId', 'currency', 'amount', 'transactionReference');

        $data = [
            'apiOperation' => self::API_OPERATION,
            'transaction' => [
                'amount' => $this->getAmount(),
                'currency' => $this->getCurrency(),
                'reference' => $this->getTransactionReference(),
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

        return $host . \sprintf(self::PATH, $this->getMerchantId(), $this->getOrderId(), $this->getTransactionId());
    }

    public function getHttpMethod(): string
    {
        return 'PUT';
    }
}
