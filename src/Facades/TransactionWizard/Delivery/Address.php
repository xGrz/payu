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

    protected array $data = [
        'street' => null,
        'postalCode' => null,
        'city' => null,
        'countryCode' => null
    ];
    public function __construct(string $email = null, string $name = null, string|int $phone = null, string $city = null, string $street = null, string|int $postalCode = null, string $countryCode = 'PL')
    {
        parent::__construct();
        if (!empty($email)) $this->setRecipientEmail($email);
        if (!empty($phone)) $this->setRecipientPhone($phone);
        if (!empty($name)) $this->setRecipientName($name);
        if (!empty($postalBox)) $this->setPostalBox($postalBox);
        if (!empty($city)) $this->setCity($city);
        if (!empty($street)) $this->setStreet($street);
        if (!empty($postalCode)) $this->setPostalCode($postalCode);
        if (!empty($countryCode)) $this->setCountryCode($countryCode);
    }

    public static function make(string $email = null, string $name = null, string|int $phone = null, string $city = null, string $street = null, string|int $postalCode = null, string $countryCode = null): static
    {
        return new static($email, $name, $phone, $city, $street, $postalCode, $countryCode);
    }


}
