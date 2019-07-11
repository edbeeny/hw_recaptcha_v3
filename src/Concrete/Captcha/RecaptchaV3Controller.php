<?php

namespace Concrete\Package\HwRecaptchaV3\Captcha;

use Concrete\Core\Captcha\CaptchaInterface as CaptchaInterface;
use Concrete\Core\Http\ResponseAssetGroup;
use Concrete\Core\Package\Package;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Support\Facade\Config;
use Concrete\Core\View\View;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Support\Facade\Log;

class RecaptchaV3Controller implements CaptchaInterface

{

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Captcha\CaptchaInterface::display()
     */
    public function display()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Captcha\CaptchaInterface::showInput()
     */
    public function showInput()
    {

        $assetList = AssetList::getInstance();

        $assetUrl = 'https://www.google.com/recaptcha/api.js?onload=hwRecaptcha&render=explicit';

        $assetList->register('javascript', 'hw_recaptcha_api', $assetUrl, array('local' => false));
        $assetList->register('javascript', 'hw_recaptcha_render', 'assets/js/render.js', array(), 'hw_recaptcha_v3');


        $assetList->registerGroup(
            'hw_recaptcha_v3',
            array(
                array('javascript', 'hw_recaptcha_render'),
                array('javascript', 'hw_recaptcha_api'),
            )
        );

        $responseAssets = ResponseAssetGroup::get();
        $responseAssets->requireAsset('hw_recaptcha_v3');

        echo '<input type="hidden" name="recaptcha_key" id="recaptchaKey" value="' . Config::get('hw_recaptcha.site_key') . '">';
        echo '<div id="grecaptcha-box"></div>';


    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Captcha\CaptchaInterface::label()
     */
    public function label()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Captcha\CaptchaInterface::check()
     */
    public function check()
    {

        $qsa = http_build_query(
            array(
                'secret' => Config::get('hw_recaptcha.secret_key'),
                'response' => $_REQUEST['g-recaptcha-response'],
            )
        );

        $ch = curl_init('https://www.google.com/recaptcha/api/siteverify?' . $qsa);

        if (Config::get('concrete.proxy.host') != null) {
            curl_setopt($ch, CURLOPT_PROXY, Config::get('concrete.proxy.host'));
            curl_setopt($ch, CURLOPT_PROXYPORT, Config::get('concrete.proxy.port'));

            // Check if there is a username/password to access the proxy
            if (Config::get('concrete.proxy.user') != null) {
                curl_setopt(
                    $ch,
                    CURLOPT_PROXYUSERPWD,
                    Config::get('concrete.proxy.user') . ':' . Config::get('concrete.proxy.password')
                );
            }
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, Config::get('app.curl.verifyPeer'));

        $response = curl_exec($ch);

        if ($response !== false) {
            $data = json_decode($response, true);


            if (isset($data['error-codes']) && (in_array('missing-input-secret', $data['error-codes']) || in_array('invalid-input-secret', $data['error-codes']))) {
                Log::addError(t('The reCAPTCHA secret parameter is invalid or malformed.'));
            }

            if ($data['success'] == true && $data['score'] > Config::get('hw_recaptcha.score')) {
                return true;
            } else {
                return false;

            }
        } else {
            Log::addError(t('Error loading reCAPTCHA: %s', curl_error($ch)));
            return false;
        }
    }


    public
    function saveOptions($data)
    {

        Config::save('hw_recaptcha . site_key', $data['site']);
        Config::save('hw_recaptcha . secret_key', $data['secret']);
        Config::save('hw_recaptcha.score', $data['score']);
    }
}
