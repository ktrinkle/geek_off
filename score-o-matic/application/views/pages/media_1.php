<div class="uk-container uk-container-large">
<?php 
echo form_open('media_1', array('id'=>'media1'), array('event' => $event));?>
<div class="uk-grid" uk-grid>
    <div class="uk-width-1-6@s"><div>
    	<?php
    	echo form_label('Select question: ', 'question_no', array('class'=>'uk-form-label'));
	echo form_dropdown('question_no', $options, $question_nbr, array('class'=>'uk-select'));
    	?>
    </div></div>
    <div class="uk-width-1-2@s"><div>
	<table class="uk-table uk-table-striped uk-table-small">
		<thead>
			<tr><th>Team Name</th><th>Score</th></tr>
		</thead>
		<tbody>
			<?php
			foreach($teams->result() as $teamid) {
				echo "<tr><td>" . form_label($teamid->teamname, 't'. $teamid->team_no) . "</td>";
				echo "<td>" . form_input(array('name'=>'t' . $teamid->team_no ,'id'=>'team_no', 'value'=>'','maxlength'=>'5','size'=>'5','class'=>'uk-input')) . "</td></tr>";	
			}
			?>
		</tbody>
	</table>
    </div></div>
    <div class="uk-width-1-3@s"><div>
	<table class="uk-table uk-table-striped uk-table-small">
		<thead>
			<tr><th>Team Number</th><th>Score</th><th>Rank</th></tr>
		</thead>
		<tbody>
			<?php
			foreach($scores->result() as $teamid) {
				echo "<tr><td>" . $teamid->team_no . "</td>";
				echo "<td>" . $teamid->ptswithbonus . "</td>";
				echo "<td>" . $teamid->rnk . "</td></tr>";	
			}
			?>
		</tbody>
	</table>
    </div></div>
</div>
<div class="uk-width-1-1@s uk-grid uk-text-center" uk-grid>
<?php 

echo form_submit('update','Add result', array('class' => 'uk-button uk-button-primary')) . form_close();
echo form_open('finalize_round',array('id'=>'finalize'),array('round' => '1', 'event' => $event, 'next_page' => 'media_2')) . form_submit('finalize', 'Finalize round 1', array('class' => 'uk-button uk-button-secondary')) . form_close();
?>
</div>
<?php
