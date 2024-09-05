<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

trait AirlineDataTrait
{
    public function getDocumentType(): ?string
    {
        return $this->getParameter('documentType');
    }

    public function setDocumentType(?string $value): self
    {
        return $this->setParameter('documentType', $value);
    }

    public function getBookingReference(): ?string
    {
        return $this->getParameter('bookingReference');
    }

    public function setBookingReference(?string $value): self
    {
        return $this->setParameter('bookingReference', $value);
    }

    public function getPassenger(): ?array
    {
        return $this->getParameter('passenger');
    }

    public function setPassenger(?array $value): self
    {
        return $this->setParameter('passenger', $value);
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
