<?php

use Tygh\Registry;
use Tygh\Settings;
use Tygh\Tools\DateTimeHelper;

abstract class Utility {
    public static function getApiKey() {
        return self::getSetting('api-key');
    }

    public static function getSetting($key) {
        return Registry::get('addons.sms77.' . $key);
    }

    public static function getFrom() {
        return self::getSetting('message-from');
    }

    public static function insertMessage(array $data) {
        if (!is_string($data['info'])) $data['info'] = json_encode($data['info']);
        db_query('INSERT INTO ?:sms77_messages ?e', $data);
    }

    public static function notifyAdmin($type, $message) {
        $prefix = 'sms77.';
        if (false === strstr($message, $prefix)) $message = $prefix . $message;
        $message = __($message);
        if (self::isAdmin()) fn_set_notification($type, 'sms77', $message, 'K');
    }

    private static function isAdmin() {
        return defined('ACCOUNT_TYPE') && ACCOUNT_TYPE === 'admin';
    }

    public static function replacePlaceholder($text, $orderInfo, $orderStatuses) {
        $search = [
            '{order_id}',
            '{total}',
            '{date}',
            '{firstname}',
            '{lastname}',
        ];
        $replace = [
            $orderInfo['order_id'],
            self::getTotalFormatted($orderStatuses['total']),
            self::getDateForTimezoneAndTimestamp(
                self::getTimezone(), $orderStatuses['timestamp']),
            $orderInfo['firstname'],
            $orderInfo['lastname'],
        ];
        return str_replace($search, $replace, $text);
    }

    private static function getTotalFormatted($total, $currency = '$') {
        $decimals = (int)db_get_array(
            'SELECT decimals FROM ?:currencies WHERE currency_id=1')[0]['decimals'];
        return number_format($total, $decimals, ',', '') . ' ' . $currency;
    }

    private static function getDateForTimezoneAndTimestamp($timezone, $timestamp) {
        $date = date('Y-m-d H:i:s', $timestamp);

        if (0 === $timezone) $date .= ' (UTC)';
        else {
            if ($timezone > 0) $timezone = '+' . $timezone;

            $date .= ' (' . $timezone . ' UTC)';
        }

        return $date;
    }

    static function getSystemTimezone() {
        return Settings::instance()->getValue('timezone', 'Appearance');
    }

    public static function getTimezone() {
        $timeZone = self::getSystemTimezone();
        $offset = DateTimeHelper::getTimeZoneOffset($timeZone);
        return floor($offset / 3600);
    }

    public static function isChecked($key) {
        return 'Y' === self::getSetting($key);
    }

    public static function toBool($key) {
        return (bool)filter_var(isset($key) ? $key : 0, FILTER_VALIDATE_BOOLEAN);
    }
}
