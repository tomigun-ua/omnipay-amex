<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

class CaptureRequest extends AbstractRequest
{
    use AirlineDataTrait;

    protected const API_OPERATION = 'CAPTURE';

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

        $this->buildAirlineData($data);

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
}
