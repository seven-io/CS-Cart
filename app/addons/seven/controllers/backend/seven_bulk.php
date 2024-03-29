<?php defined('BOOTSTRAP') || die('Access denied');

use Tygh\Registry;
use Tygh\Tools\DateTimeHelper;

$addonDir = Registry::get('config.dir.addons') . 'seven/';
require_once $addonDir . 'seven.php';
require_once $addonDir . 'Utility.php';

if ('view' !== $mode) return;

if ('POST' === $_SERVER['REQUEST_METHOD']) {
    $text = $_POST['text'];
    $time = $_POST['time'];
    $offset = DateTimeHelper::getTimeZoneOffset(Utility::getSystemTimezone());
    $end = 0;

    if ('all' === $time) $start = 0;
    else if ('period' === $time) {
        $start = strtotime($_POST['from']);
        $end = strtotime($_POST['to'] . ' +1 day');

        if ($start && $end) {
            $start += $offset;
            $end += $offset;

            if ($start > $end)
                Utility::notifyAdmin('W', __('seven.start_date_after_end_date'));
        } else Utility::notifyAdmin('W', __('seven.start_date_or_end_date_invalid'));

        return [CONTROLLER_STATUS_OK, 'seven_bulk.view'];
    } else $start = time() + $offset;

    $smsParams = [
        'text' => $_POST['text'],
        'to' => sevenGetPhones($time, $start, $end),
    ];
    $status = 'fail';
    $errorData = array_merge($smsParams, ['type' => 'Bulk']);

    $calculate = isset($_POST['calculate']);
    if ($calculate) $smsParams['debug'] = 1;

    try {
        $api = new seven;
        $res = $api->sendSMS($smsParams);
        $error = $api->getError();

        if ($error) {
            $notifyMsg = 'bulk_fail';
            $info = $error;
        } else {
            $notifyMsg = $calculate ? sevenCalculate($res->messages) : 'bulk_success';
            $info = $res;
            $status = 'success';
        }
    } catch (Exception $e) {
        $notifyMsg = $e->getMessage();
        $info = $notifyMsg;
    }

    Utility::notifyAdmin('N', $notifyMsg);
    Utility::insertMessage(array_merge($errorData, compact('info', 'status')));

    return [CONTROLLER_STATUS_OK, 'seven_bulk.view'];
}

sevenAssignCountries();

function sevenAssignCountries() {
    $entries = [];
    $rows = db_get_array('SELECT country FROM ?:country_descriptions ORDER BY country');

    foreach ($rows as $row) $entries[] = $row['country'];

    Tygh::$app['view']->assign('countries', $entries);
}

function sevenOrderHasProductId(array $products, array $productIds) {
    if (empty($productIds)) return true;

    foreach ($products as $p)
        if (in_array($p['product_id'], $productIds)) return true;

    return false;
}

function sevenCalculate(array $messages) {
    $count = 0;
    $perUnit = 0.075;
    $phoneCount = count($messages);

    foreach ($messages as $message) $count += $message->parts;

    $total = $count * $perUnit;

    return __('seven.calculate_sms_price', compact('count', 'phoneCount', 'total'));
}

function sevenGetPhones($time, $startDate, $endDate) {
    $phones = [];
    $productIds = preg_split('@,@', $_POST['p_ids'], null, PREG_SPLIT_NO_EMPTY);
    $countries = $_POST['countries'];
    $minPrice = $_POST['min_price'];

    foreach (fn_get_orders([])[0] as $order) {
        $orderInfo = fn_get_order_info($order['order_id']);

        if (!($orderInfo['subtotal'] >= $minPrice || empty($minPrice))) continue;
        if (!empty($countries) && !in_array($orderInfo['b_country_descr'], $countries))
            continue;

        $timestamp = (int)$orderInfo['timestamp'];
        $inRange = $timestamp > $startDate;

        if (
            ($inRange && $time !== 'period') ||
            ($inRange && $timestamp < $endDate && $time === 'period')
        ) {
            if (!sevenOrderHasProductId($orderInfo['products'], $productIds)) continue;

            $phone = fn_seven_format_phone($order['phone']);

            if (!empty($phone) && !in_array($phone, $phones)) $phones[] = $phone;
        }
    }

    return implode(',', $phones);
}
