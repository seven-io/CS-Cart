{include file='common/subheader.tpl' title='Send SMS via sms77'}

<div class='control-group'>
    <label class='control-label' for='sms77_performance_tracking'>
        <a href='https://help.sms77.io/en/performance-tracking-1' target='_blank'>
            Performance Tracking?
        </a>
    </label>

    <div class='controls'>
        <input
                id='sms77_performance_tracking'
                name='sms77_performance_tracking'
                type='checkbox'
        />
    </div>
</div>

<div class='control-group'>
    <label for='sms77_text' class='control-label'>
        Message:
    </label>

    <div class='controls'>
         <textarea
                 class='input-textarea-long input-large'
                 data-sms77-sms
                 id='sms77_text'
                 maxlength='1520'
                 name='sms77_text'
                 rows='8'
         ></textarea>
    </div>
</div>

<script src='https://unpkg.com/@sms77.io/counter/dist/index.js'></script>
