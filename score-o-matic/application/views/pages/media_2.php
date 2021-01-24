<div class="uk-container uk-container-large">
<?php echo form_open('media_2', array('id'=>'media2'), array('event' => $event));?>
<div class="uk-grid" uk-grid>
    <div class="uk-width-1-6@s">
    	<?php
    	echo form_label('Select question: ', 'question_no', array('class'=>'uk-form-label'));
	echo form_dropdown('question_no', $options, $question_nbr, array('class'=>'uk-select')) . '<br/>';
    	?>
    </div>
    <div class="uk-width-1-2@s">
	    <div class="uk-child-width-1-3 uk-grid bigbtngrn" uk-grid>
	<?php
	foreach($teams->result() as $teamid) {
		echo '<div class="uk-width-1-3 uk-card-small uk-card-body">';
		echo form_checkbox(array('name'=>'t'. $teamid->team_no, 'id' => 't'. $teamid->team_no, 'value' => '1', 'checked' => FALSE, 'style' => 'class:bigbtngrnlabel')) . form_label($teamid->teamname, 't' . $teamid->team_no, array('class' => 'bigbtngrnlabel'));
		echo '</div>';
		}
	?>
    </div></div>
    <div class="uk-width-1-3@s"><div>
	<table class="uk-table uk-table-striped uk-table-small">
		<thead>
			<tr><th>Team Number</th><th>Score</th></tr>
		</thead>
		<tbody>
			<?php
			foreach($scores->result() as $teamid) {
				echo "<tr><td>" . $teamid->team_no . "</td>";
				echo "<td>" . $teamid->ptswithbonus . "</td></tr>";	
			}
			?>
		</tbody>
	</table>
    </div></div>
</div>
<div class="uk-width-1-1@s uk-grid" uk-grid>
<?php 

echo form_submit('update','Add result',array('class' => 'uk-button uk-button-primary')) . form_close();
echo form_open('finalize_round',array('id'=>'finalize'),array('round' => '2', 'event' => $event, 'next_page' => 'media_3')) . form_submit('finalize', 'Finalize round 2',array('class' => 'uk-button uk-button-secondary')) . form_close();
?>
</div>
<?php
