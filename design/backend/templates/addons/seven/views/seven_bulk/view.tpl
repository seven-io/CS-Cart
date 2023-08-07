{capture name='mainbox'}
    <form action='{'sms77_bulk.view'|fn_url}' method='post'>

        <div style='display: flex; flex-direction: row'>
            <fieldset style='width: 40%'>
                <p class='muted description'>
                    {__('sms77.bulk_narrow_down')}
                </p>

                <div class='control-group'>
                    <div class='controls'>
                        <label class='radio'>
                            <input checked type='radio' name='time' value='all'/>
                            {__('sms77.bulk_filter_order_date_any')}
                        </label>
                    </div>
                </div>

                <div class='control-group'>
                    <div class='controls'>
                        <label class='radio'>
                            <input type='radio' name='time' value='period'/>
                            {__('sms77.bulk_filter_order_between')}
                        </label>
                    </div>
                </div>

                <div class='control-group hidden' id='from_wrapper'>
                    <label class='control-label' for='from'>
                        {__('sms77.bulk_filter_order_from')}:
                    </label>

                    <div class='controls'>
                        <input id='from' type='date' name='from'/>
                    </div>
                </div>

                <div class='control-group hidden' id='to_wrapper'>
                    <label class='control-label' for='to'>
                        {__('sms77.bulk_filter_order_to')}:
                    </label>

                    <div class='controls'>
                        <input id='to' type='date' name='to'/>
                    </div>
                </div>

                <hr/>

                <div class='control-group'>
                    <label class='control-label'>
                        {__('ordered_products')}
                        {*Limit to customers who bought certain products*}
                    </label>

                    <div class='controls'>
                        {include file='common/products_to_search.tpl'}
                    </div>
                </div>

                <hr/>

                <div class='control-group'>
                    <label class='control-label' for='min_price'>
                        {__('sms77.bulk_filter_order_min_price')}:
                    </label>

                    <div class='controls'>
                        <input type='number' step='0.01' min='0' name='min_price'
                               id='min_price'/>
                    </div>
                </div>

                <hr/>

                <div class='control-group'>
                    <label class='control-label' for='countries'>
                        {__('sms77.bulk_filter_order_countries')}:
                    </label>

                    <div class='controls'>
                        <select aria-label='Countries' id='countries' name='countries[]'
                                multiple>
                            {foreach $countries as $country}
                                <option value='{$country}'>{$country}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </fieldset>

            <fieldset class='center' style='width: 60%;'>
                <div class='control-group'>
                    <label class='control-label cm-required' for='text'>
                        {__('sms77.message')}:
                    </label>

                    <div class='controls'>
                    <textarea
                            class='input-textarea-long'
                            data-sms77-sms
                            id='text'
                            maxlength='1520'
                            name='text'
                            required
                            rows='15'
                    ></textarea>
                    </div>
                </div>
            </fieldset>
        </div>

        <div class='btn-group center input-full'>
            {include
            but_name='calculate'
            but_role='submit'
            but_text=__('sms77.calculate_sms_price_submit')
            file='buttons/button.tpl'
            }

            {include
            but_name='save'
            but_role='submit'
            but_text=__('sms77.send_sms')
            file='buttons/button.tpl'
            }
        </div>
    </form>
{/capture}

<div class='cm-notification-container'></div>

<link href='https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
      rel='stylesheet'/>
<script src='https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js'></script>
<script src='https://unpkg.com/@sms77.io/counter/dist/index.js'></script>
<script>
    $(() => {
        $('select[name=\'countries[]\']').select2({
            placeholder: '{__('sms77.bulk_filter_countries_all')}',
        })

        const time_inputs = [$('#from_wrapper'), $('#to_wrapper')]

        $('input[name=\'time\']')
            .change(() => time_inputs.forEach(el => el.toggleClass('hidden')))
    })
</script>

{include
content=$smarty.capture.mainbox
file='common/mainbox.tpl'
title=__('sms77.bulk_sms')
}
