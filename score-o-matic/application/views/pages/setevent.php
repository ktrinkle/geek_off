<?php 
if (isset($result)) {
	echo "<p class='uk-alert-success'>Current event is updated.</p><hr />";
}

echo form_open('setevent', array('id'=>'event'),array('class'=>'uk-form-label'));
echo form_label('Select event: ','yevent');

echo form_dropdown('yevent', $options, $selected,array('class'=>'uk-select'));
echo '<br />';
echo form_submit('update','Update event',array('class' => 'uk-button uk-button-primary'));
form_close();
