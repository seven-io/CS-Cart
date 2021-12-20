{capture name='mainbox'}
    <form action='{'sms77_history.view'|fn_url}' method='post'>
        <div class='control-group'>
            <label class='control-label' for='sms77_to'>
                {__('sms77.filter_by_phone')}:
            </label>

            <div class='controls'>
                <input id='sms77_to' name='to'/>
            </div>
        </div>

        <div class='control-group'>
            <label class='control-label' for='sms77_date'>
                {__('sms77.filter_by_date')}:
            </label>

            <div class='controls'>
                <input type='date' id='sms77_date' name='date'/>
            </div>
        </div>

        {include
        but_name='save'
        but_role='submit'
        but_text=__('sms77.filter_messages')
        file='buttons/button.tpl'
        }
    </form>
    {if $messages}
        {include file='common/pagination.tpl'}
        <div class='table-responsive-wrapper'>
            <table class='table table-condensed table--relative table-responsive'>
                <thead>
                <tr>
                    <th>{__('id')}</th>
                    <th>{__('status')}</th>
                    <th>{__('type')}</th>
                    <th>{__('recipient')}</th>
                    <th>{__('date')}</th>
                    <th>{__('sms77.info')}</th>
                </tr>
                </thead>
                <tbody>
                {strip}
                    {foreach $messages as $message}
                        <tr>
                            <td data-th='{__('id')}'>
                                {$message[0]}
                            </td>

                            <td data-th='{__('status')}'>
                                {fn_sms77_label(('success' == $message[1]), $message[1])}
                            </td>

                            <td data-th='{__('type')}'>
                                {__('sms77.'|cat:$message[2])}
                            </td>

                            <td data-th='{__('recipient')}'>
                                {$message[3]}
                            </td>

                            <td data-th='{__('date')}'>
                                {$message[4]}
                            </td>

                            <td data-th='{__('sms77.info')}'>
                                {if is_object($message[5])}
                                    <button class='btn btn-primary btn-small'
                                            data-toggle='modal'
                                            data-target='#sms77_info_{$message@index}'>
                                        {__('view')}
                                    </button>
                                    <div class='modal hide fade'
                                         id='sms77_info_{$message@index}'
                                         tabindex='-1' role='dialog' aria-hidden='true'>
                                        <div class='modal-header'>
                                            {__('sms77.info')}
                                        </div>

                                        <div class='modal-body'>
                                            <div class='table-responsive-wrapper'>
                                                <table class='table table-condensed table--relative table-responsive'>
                                                    <thead>
                                                    <tr>
                                                        <th>{__('sms77.success')}</th>
                                                        <th>{__('total_price')}</th>
                                                        <th>{__('sms77.debug')}</th>
                                                        <th>{__('sender')}</th>
                                                        <th>{__('text')}</th>
                                                        <th>{__('sms77.encoding')}</th>
                                                        <th>{__('sms77.label')}</th>
                                                        <th>{__('sms77.parts')}</th>
                                                        <th>{__('messages')}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            {fn_sms77_label(('100' == $message[5]->success), $message[5]->success|intval)}
                                                        </td>
                                                        <td>{$message[5]->total_price}</td>
                                                        <td>
                                                            {fn_sms77_label($message[5]->debug, $message[5]->debug)}
                                                        </td>
                                                        <td>{$message[5]->messages[0]->sender}</td>
                                                        <td>{$message[5]->messages[0]->text}</td>
                                                        <td>{$message[5]->messages[0]->encoding}</td>
                                                        <td>{$message[5]->messages[0]->label}</td>
                                                        <td>{$message[5]->messages[0]->parts}</td>
                                                        <td>
                                                            <div class='table-responsive-wrapper'>
                                                                <table class='table table-condensed table--relative table-responsive'>
                                                                    <thead>
                                                                    <tr>
                                                                        <th>{__('id')}</th>
                                                                        <th>{__('recipient')}</th>
                                                                        <th>{__('price')}</th>
                                                                        <th>{__('sms77.success')}</th>
                                                                        <th>{__('error')}</th>
                                                                        <th>{__('sms77.error_text')}</th>
                                                                    </tr>
                                                                    </thead>

                                                                    <tbody>
                                                                    {strip}
                                                                        {foreach $message[5]->messages as $msg}
                                                                            <tr>
                                                                                <td>{$msg->id}</td>
                                                                                <td>{$msg->recipient}</td>
                                                                                <td>{$msg->price}</td>
                                                                                <td>{fn_sms77_label($msg->success, $msg->success)}</td>
                                                                                <td>{$msg->error}</td>
                                                                                <td>{$msg->error_text}</td>
                                                                            </tr>
                                                                        {/foreach}
                                                                    {/strip}
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class='modal-footer center'>
                                            <button class='btn btn-secondary'
                                                    data-dismiss='modal'>
                                                {__('close')}
                                            </button>
                                        </div>
                                    </div>
                                {else}
                                    {$message[5]}
                                {/if}
                            </td>
                        </tr>
                    {/foreach}
                {/strip}
                </tbody>
            </table>
        </div>
        <div class='clearfix'>
            {include file='common/pagination.tpl'}
        </div>
    {else}
        <p class='no-items'>{__('no_data')}</p>
    {/if}
{/capture}

{include
content=$smarty.capture.mainbox
file='common/mainbox.tpl'
title=__('sms77.sms_history')
}
