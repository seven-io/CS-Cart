<?php defined('BOOTSTRAP') || die('Access denied');

use Tygh\Registry;

if ('view' !== $mode) return;

$elements = ['date', 'to'];

if ('POST' === $_SERVER['REQUEST_METHOD']) {
    $url = 'sms77_history.view';

    foreach ($elements as $element)
        if (!empty($_POST[$element])) $url .= '&' . $element . '=' . $_POST[$element];

    return [CONTROLLER_STATUS_OK, $url];
}

$where = fn_sms77_get_messages_where_clauses($elements);
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
$items_per_page = isset($_REQUEST['items_per_page'])
    ? $_REQUEST['items_per_page']
    : Registry::get('settings.Appearance.admin_elements_per_page');
$total_items = db_query(
    'SELECT COUNT(*) from ?:sms77_messages' . $where)->fetch_all()[0][0];

$messages = db_query(
    'SELECT * FROM ?:sms77_messages ' . $where . ' ORDER BY id ASC LIMIT ?i, ?i',
    ($page - 1) * $items_per_page,
    $items_per_page
)->fetch_all();

foreach ($messages as $k => $v) {
    if (!strstr($v[5], '{')) continue;
    $v[5] = json_decode($v[5]);
    $messages[$k] = $v;
}

Tygh::$app['view']->assign('messages', $messages);

Tygh::$app['view']->assign('search', compact('page', 'items_per_page', 'total_items'));

function fn_sms77_get_messages_where_clauses($elements) {
    $where = '';
    $operator = ' WHERE ';

    foreach ($elements as $col) {
        if (!isset($_GET[$col])) continue;
        $where .= $operator . '`' . $col . '` LIKE \'%' . $_GET[$col] . '%\'';
        $operator = ' AND ';
    }

    return $where;
}
