<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

trait BillingDataTrait
{
    protected function getBillingData(): array
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
}
