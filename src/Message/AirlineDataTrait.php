<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

trait AirlineDataTrait
{
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
