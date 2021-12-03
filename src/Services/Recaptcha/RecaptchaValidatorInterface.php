<?php

namespace App\Services\Recaptcha;

interface RecaptchaValidatorInterface
{
    /**
     * @return bool
     */
    public function verify(): bool;
}