{capture name='mainbox'}
    <form class='cm-ajax submitForm' action='{'sms77_test.view'|fn_url}' method='post'>
        <div class='control-group'>
            <label class='control-label cm-required' for='sms77_phone'>
                {__('sms77.phone_number')}
            </label>

            <div class='controls'>
                <input id='sms77_phone' name='to' required/>
            </div>
        </div>

        <label class='checkbox inline'>
            <input class='checkbox' type='checkbox' name='sms77_performance_tracking'/>

            {__('sms77.use_performance_tracking')}
        </label>

        <div class='control-group'>
            <label for='sms77_message' class='control-label cm-required'>
                {__('sms77.message')}
            </label>

            <div class='controls'>
                <textarea class='input-textarea-long' id='sms77_message' name='text'
                          rows='8' maxlength='1520' required></textarea>
            </div>
        </div>

        {include
        but_name='save'
        but_role='submit'
        but_text=__('sms77.send_sms')
        file='buttons/button.tpl'
        }
    </form>
{/capture}

{include
content=$smarty.capture.mainbox
file='common/mainbox.tpl'
title=__('sms77.test_sms')
}
