<?php


namespace App\Services\Recaptcha;


class RecaptchaValidatorDev implements RecaptchaValidatorInterface
{

    public function verify(): bool
    {
        return true;
    }
}