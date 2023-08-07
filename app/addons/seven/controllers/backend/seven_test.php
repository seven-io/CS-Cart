<?php defined('BOOTSTRAP') || die('Access denied');

use Tygh\Registry;

if ('POST' !== $_SERVER['REQUEST_METHOD'] || 'view' !== $mode) return;

$addonDir = Registry::get('config.dir.addons') . 'sms77/';
require_once $addonDir . 'sms77.php';
require_once $addonDir . 'Utility.php';

$text = $_POST['text'];
if (!$text) return Utility::notifyAdmin('W', __('missing_text'));

$to = fn_sms77_format_phone($_POST['to']);
if (!$to) return Utility::notifyAdmin('W', __('missing_recipient'));

$type = 'test_sms';
try {
    $api = new sms77;
} catch (Exception $e) {
    return Utility::insertMessage(array_merge(compact('text', 'to', 'type'), [
        'info' => 'missing_api_key',
        'status' => 'fail',
    ]));
}
$api->sendSMS(compact('text', 'to'), $type);
