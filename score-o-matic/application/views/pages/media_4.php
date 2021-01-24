<div class="uk-container uk-container-large">
<?php echo form_open('media_4', array('id'=>'media4'), array('event' => $event)); ?>
<div class="uk-grid" uk-grid>
    <div class="uk-width-2-3@s uk-grid" uk-grid>
    <div class="uk-width-1-4@s">
	<?php
	$teamdrop = array('' => 'Select');
	foreach($teams->result_array() as $row) 
		{
			$teamdrop[$row['team_no']] = $row['teamname'];
		}	
	echo form_label('Select team:', 'teamdrop', array('class'=>'uk-form-label')) . form_dropdown('teamdrop', $teamdrop, '', array('class'=>'uk-select')) . "<br/><div></div>";	
	?>
</div>
    <div class="uk-width-3-4@s">
	<?php
	//preprocess numrows
	$elementcount = $questions->num_rows(); 
	$j = 5; 
	echo "<div class='uk-text-center bigbtnred uk-card-small uk-card-body'>";
	if ($j <= $elementcount) {
		$pyr  = $questions->row($j);
		echo form_checkbox(array('name'=>'q' . $pyr->question_no, 'id'=>'q' . $pyr->question_no, 'value' => $pyr->ptsposs, 'checked' => FALSE, 'style' =>'class:bigbtnred'));
		echo form_label($pyr->ptsposs, 'q' . $pyr->question_no, array('class' => 'bigbtnredlabel'));
	}
	echo "</div>";		
	?>
	<div class="uk-child-width-1-2@s uk-text-center uk-grid" uk-grid>
	<div class='bigbtnred uk-card-small uk-card-body'>
		<?php
		$j = 3;
		if ($j <= $elementcount) {
			$pyr  = $questions->row($j);
			echo form_checkbox(array('name'=>'q' . $pyr->question_no, 'id'=>'q' . $pyr->question_no, 'value' => $pyr->ptsposs, 'checked' => FALSE, 'style' =>'class:bigbtnred'));
			echo form_label($pyr->ptsposs, 'q' . $pyr->question_no, array('class' => 'bigbtnredlabel'));
		}
		?>
	</div>
	<div class='bigbtnred uk-card-small uk-card-body'>
		<?php
		$j = 4;
		if ($j <= $elementcount) {
			$pyr  = $questions->row($j);
			echo form_checkbox(array('name'=>'q' . $pyr->question_no, 'id'=>'q' . $pyr->question_no, 'value' => $pyr->ptsposs, 'checked' => FALSE, 'style' =>'class:bigbtnred'));
			echo form_label($pyr->ptsposs, 'q' . $pyr->question_no, array('class' => 'bigbtnredlabel'));
			$j++;
		}
		?>
	</div>
</div>
	<div class="uk-grid uk-child-width-1-3@s uk-text-center" uk-grid>
		<?php
		$j = 0;
		if ($elementcount <= 2) {
			$end = $elementcount;
		} else {
			$end = 2;
		}
		for($k=$j; $k<=$end; $k++){
		echo "<div class='bigbtnred uk-card-small uk-card-body'>";
			$pyr  = $questions->row($k);
			echo form_checkbox(array('name'=>'q' . $pyr->question_no, 'id'=>'q' . $pyr->question_no, 'value' => $pyr->ptsposs, 'checked' => FALSE, 'style' =>'class:bigbtnred'));
			echo form_label($pyr->ptsposs, 'q' . $pyr->question_no, array('class' => 'bigbtnredlabel'));
			echo "</div>";
		}
		?>
	
</div>
<div class="uk-text-center">
<div>
	<?php echo form_submit('update','Add result',array('class' => 'uk-button uk-button-primary'));?>
</div>

</div>

</div>

</div>
    <div class="uk-width-1-3@s uk-grid" uk-grid><div>
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
<?php echo form_close();?>
    	<?php
		echo form_open('finalize_round',array('id'=>'finalize'),array('round' => '4',
		 'event' => $event, 'next_page' => 'home')) . form_submit('finalize', 'Finalize round 4',array('class' => 'uk-button uk-button-secondary')) . form_close();
		 ?>
</div>
<?php
