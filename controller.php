<?php

namespace Concrete\Package\HwRecaptchaV3;

use Concrete\Core\Logging\Logger;
use Concrete\Core\Package\Package;
use Concrete\Core\Captcha\Library as CaptchaLibrary;


class Controller extends Package
{
    protected $pkgHandle = 'hw_recaptcha_v3';
    protected $appVersionRequired = '8.0';
    protected $pkgVersion = '0.9.3';

    protected $logger;

    protected $pkgAutoloaderMapCoreExtensions = true;

    public function getPackageName()
    {
        return t('HW Recaptcha V3');
    }

    public function getPackageDescription()
    {
        return t('Google reCAPTCHA Version 3.');
    }


    public function install()
    {
        $pkg = parent::install();
        CaptchaLibrary::add('recaptchaV3', t('reCAPTCHAv3'), $pkg);
        return $pkg;
    }

    public function uninstall()
    {
        parent::uninstall();
    }
}
