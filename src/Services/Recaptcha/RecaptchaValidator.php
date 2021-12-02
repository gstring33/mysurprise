<?php

namespace App\Services\Recaptcha;

use ReCaptcha\ReCaptcha;
use Symfony\Component\HttpFoundation\RequestStack;

class RecaptchaValidator implements RecaptchaValidatorInterface
{
    /** @var Recaptcha */
    private Recaptcha $recaptcha;
    /** @var RequestStack */
    private RequestStack $request;
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    /**
     * Recaptcha constructor.
     * @param RequestStack $requestStack
     * @param Recaptcha $recaptcha
     */
    public function __construct(RequestStack $requestStack, Recaptcha $recaptcha)
    {
        $this->recaptcha = $recaptcha;
        $this->requestStack = $requestStack;
    }

    /**
     * @return bool
     */
    public function verify(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        $resp = $this->recaptcha->verify(
            $request->request->get('g-recaptcha-response'),
            $request->getClientIp()
        );

        return $resp->isSuccess();
    }
}