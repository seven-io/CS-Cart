<?php defined('AREA') || die('Access denied');

use Tygh\Enum\OrderStatuses;
use Tygh\Registry;

$addonDir = Registry::get('config.dir.addons');
require_once $addonDir . 'sms77/sms77.php';
require_once $addonDir . 'sms77/Utility.php';

function fn_sms77_format_phone($phoneNumber) {
    if (empty($phoneNumber)) return '';

    $phoneNumber = filter_var($phoneNumber, FILTER_SANITIZE_NUMBER_INT);
    $phoneNumber = str_replace(['+', '-'], '', $phoneNumber);
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

    return ltrim($phoneNumber, '0');
}

function fn_sms77_change_order_status_post(
    $order_id,
    $status_to,
    $status_from,
    $order_info,
    $force_notification,
    $order_statuses,
    $place_order
) {
    $hasChanged = $status_to !== $status_from;
    $wasProcessing = OrderStatuses::INCOMPLETED !== $status_from;

    if (!(($hasChanged && $wasProcessing) || (!$wasProcessing && empty($order_info))))
        return;

    $to = fn_sms77_format_phone($order_statuses['phone']);
    if (!$to) return Utility::notifyAdmin('W', __('sms77.user_missing_phone'));

    $statuses = [
        OrderStatuses::BACKORDERED => 'backordered',
        OrderStatuses::COMPLETE => 'complete',
        OrderStatuses::DECLINED => 'declined',
        OrderStatuses::FAILED => 'failed',
        OrderStatuses::CANCELED => 'canceled',
        OrderStatuses::INCOMPLETED => 'incomplete',
        OrderStatuses::OPEN => 'open',
        OrderStatuses::PAID => 'paid',
    ];

    $toStatus = $statuses[$status_to];
    $settingPrefix = $toStatus . '-';

    if (!Utility::isChecked($settingPrefix . 'activated')) return;

    $text = Utility::getSetting($settingPrefix . 'text');
    $type = 'order_status';
    $messageData = compact('text', 'to', 'type');

    if (!$text) {
        Utility::notifyAdmin('W', __('sms77.missing_text'));
        return Utility::insertMessage(array_merge($messageData, [
            'info' => 'missing_text',
            'status' => 'fail',
        ]));
    }
    $text = Utility::replacePlaceholder($text, $order_info, $order_statuses);

    try {
        $api = new sms77;
    } catch (Exception $e) {
        return Utility::insertMessage(array_merge($messageData, [
            'info' => 'missing_api_key',
            'status' => 'fail',
        ]));
    }

    $api->sendSMS(array_merge(compact('text', 'to'), [
        'performance_tracking' =>
            Utility::isChecked($settingPrefix . 'performance-tracking'),
    ]), $type);
}

function fn_sms77_update_order_details_post(
    $params, $order_info, $edp_data, $force_notification
) {
    $text = $_POST['sms77_text'];
    if (empty($text)) return;

    $to = fn_sms77_format_phone($order_info['phone']);
    if (empty($to)) return Utility::notifyAdmin('W', __('sms77.user_missing_phone'));

    $type = 'order_update';

    try {
        $api = new sms77;
    } catch (Exception $e) {
        return Utility::insertMessage(array_merge(compact('text', 'to', 'type'), [
            'info' => 'missing_api_key',
            'status' => 'fail',
        ]));
    }

    $api->sendSMS(compact('text', 'to'), $type);
}

function fn_sms77_label($condition, $content) {
    $type = $condition ? 'success' : 'danger';
    if (is_bool($content)) $content = $content ? 'true' : 'false';
    if (is_string($content)) $content = __('sms77.' . $content);

    echo <<<HTML
    <span class='label label-$type'>$content</span>
HTML;
}