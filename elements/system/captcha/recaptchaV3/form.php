<?php
defined('C5_EXECUTE') or die('Access denied.');

use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Config;

$app = Application::getFacadeApplication();
$form = $app->make('helper/form');

?>

<p><?=  t('A site key and secret key must be provided. They can be obtained from the <a href="%s" target="_blank">reCAPTCHA website</a>.', 'https://www.google.com/recaptcha/admin') ?></p>

<div class="form-group">
    <?php  echo  $form->label('site', t('Site Key')) ?>
    <?php  echo  $form->text('site', Config::get('hw_recaptcha.site_key', '')) ?>
</div>

<div class="form-group">
    <?php  echo  $form->label('secret', t('Secret Key')) ?>
    <?php  echo  $form->text('secret', Config::get('hw_recaptcha.secret_key', '')) ?>
</div>

<div class="form-group">
    <p><?= t('Set the score threshold between 0.0 and 1.0. (0.0 being a bot)')?></p>
    <?php  echo  $form->label('score', t('Score')) ?>
    <?php  echo  $form->text('score', Config::get('hw_recaptcha.score', '0.5')) ?>
</div>

<div class="form-group">
    <p><?= t('Set the position of the reCAPTCHA badge.')?></p>
    <?php  echo  $form->label('position', t('Position')) ?>
    <?php  echo  $form->select(
            'position',
             array(
            'bottomright' => t('Bottom Right'),
            'bottomleft' => t('Bottom Left'),
            'inline' => t('Inline'),
            ),
            Config::get('hw_recaptcha.position', 'bottomright')) ?>
</div>
