<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

final class PurchaseRequest extends AuthorizeRequest
{
    protected const API_OPERATION = 'PAY';

    public function getData(): array
    {
        $data = parent::getData();

        if ($this->getBookingReference()) {
            $data['airline']['bookingReference'] = $this->getBookingReference();
        }

        if ($this->getTravelAgentCode()) {
            $data['airline']['ticket']['issue']['travelAgentCode'] = $this->getTravelAgentCode();
        }

        if ($this->getTravelAgentName()) {
            $data['airline']['ticket']['issue']['travelAgentName'] = $this->getTravelAgentName();
        }

        return $data;
    }

    public function getBookingReference(): ?string
    {
        return $this->getParameter('bookingReference');
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
}
