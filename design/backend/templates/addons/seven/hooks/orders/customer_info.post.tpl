{include file='common/subheader.tpl' title='Send SMS via seven'}

<div class='control-group'>
    <label class='control-label' for='seven_performance_tracking'>
        <a href='https://help.seven.io/en/performance-tracking' target='_blank'>
            Performance Tracking?
        </a>
    </label>

    <div class='controls'>
        <input
                id='seven_performance_tracking'
                name='seven_performance_tracking'
                type='checkbox'
        />
    </div>
</div>

<div class='control-group'>
    <label for='seven_text' class='control-label'>
        Message:
    </label>

    <div class='controls'>
         <textarea
                 class='input-textarea-long input-large'
                 data-seven-sms
                 id='seven_text'
                 maxlength='1520'
                 name='seven_text'
                 rows='8'
         ></textarea>
    </div>
</div>

<script src='https://unpkg.com/@sms77.io/counter/dist/index.js'></script>
