<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

trait ShippingDataTrait
{
    protected function getShippingData(): array
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
