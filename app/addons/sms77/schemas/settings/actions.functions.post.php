<?php defined('BOOTSTRAP') || die('Access denied');

use Tygh\Registry;

require_once Registry::get('config.dir.addons') . 'sms77/sms77.php';

function fn_sms77_add_from_maxlength() {
    return <<<HTML
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('[id^="addon_option_sms77_message-from_"]')
                .setAttribute('maxlength', 16)
        })
    </script>
HTML;
}

function fn_sms77_alter_message_templates() {
    return <<<HTML
    Click a term for inserting placeholders resolving to its corresponding value:
    <dl>
    <dt class='sms77-cscart-placeholder'>{date}</dt>
    <dd>The order date</dd>
    
    <dt class='sms77-cscart-placeholder'>{firstname}</dt>
    <dd>The order buyer's first name</dd>
    
    <dt class='sms77-cscart-placeholder'>{lastname}</dt>
    <dd>The order buyer's last name</dd>
    
    <dt class='sms77-cscart-placeholder'>{order_id}</dt>
    <dd>The order ID</dd>
    
    <dt class='sms77-cscart-placeholder'>{total}</dt>
    <dd>The total order price</dd>
    </dl>

    <script>
        let focused

        document.addEventListener('DOMContentLoaded', () => {
            for (const textarea of document.querySelectorAll('textarea')) {
                textarea.dataset.sms77Sms = '';
                
                textarea.addEventListener('focus', e => focused = e.target)
            }
            
            for (const ele of document.querySelectorAll('.sms77-cscart-placeholder')) {
                ele.addEventListener('click', () => {
                    if (!focused) return
                    focused.value += ele.textContent
                    focused.dispatchEvent(new Event('input', { bubbles: true }))
                })
            }
        })
    </script>
    <script src='https://unpkg.com/@sms77.io/counter/dist/index.js'></script>
HTML;
}

function fn_sms77_get_balance() {
    try {
        $balance = (new sms77)->getBalance();
    } catch (Exception $e) {
        return $e->getMessage();
    }

    return null === $balance
        ? 'Unable to connect to sms77 API.'
        : 'Your current account balance: ' . $balance;
}
