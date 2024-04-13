<?php

namespace xGrz\PayU\Facades\TransactionWizard\Delivery;

use xGrz\PayU\Interfaces\DeliveryTypeInterface;

/**
 * @method setRecipientEmail(string $email)
 * @method setRecipientPhone(int|string $phone)
 * @method setRecipientName(string $name)
 * @method setPostalBox($postalBox)
 * @method setCity(string $city)
 * @method setStreet(string $street)
 * @method setPostalCode(int|string $postalCode)
 * @method setCountryCode(string $countryCode)
 */
class Address extends Delivery implements DeliveryTypeInterface
{

    public function __construct(
        string|int $postalCode = null,
        string     $city = null,
        string     $streetWithNumber = null,
        string     $countryCode = 'PL',
        string     $recipientEmail = null,
        string     $recipientFullName = null,
        string|int $recipientPhone = null
    )
    {
        parent::__construct();
        if (!empty($recipientEmail)) $this->setRecipientEmail($recipientEmail);
        if (!empty($recipientPhone)) $this->setRecipientPhone($recipientPhone);
        if (!empty($recipientFullName)) $this->setRecipientName($recipientFullName);
        if (!empty($postalBox)) $this->setPostalBox($postalBox);
        if (!empty($city)) $this->setCity($city);
        if (!empty($streetWithNumber)) $this->setStreet($streetWithNumber);
        if (!empty($postalCode)) $this->setPostalCode($postalCode);
        if (!empty($countryCode)) $this->setCountryCode($countryCode);
    }

    protected array $data = [
        'street' => null,
        'postalCode' => null,
        'city' => null,
        'countryCode' => null
    ];

    public static function make(
        string|int $postalCode = null,
        string     $city = null,
        string     $streetWithNumber = null,
        string     $countryCode = 'PL',
        string     $recipientEmail = null,
        string     $recipientFullName = null,
        string|int $recipientPhone = null
    ): static
    {
        return new static(
            $postalCode,
            $city,
            $streetWithNumber,
            $countryCode,
            $recipientEmail,
            $recipientFullName,
            $recipientPhone
        );
    }


}
