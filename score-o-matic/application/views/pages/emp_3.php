<div class="uk-container uk-container-large">
<div class="uk-grid" uk-grid>
    <div class="uk-width-1-6@s">
    	<?php
		echo anchor('pages/emp_4', 'Final Jeopardy', array('class' => 'uk-button uk-button-secondary'));
	?>		
    </div>
    <div class="uk-width-1-2@s">
		<?php echo form_open('emp_3', array('id'=>'emp3'), array('event' => $event));?>
	<div class="uk-child-width-1-5@s uk-grid" uk-grid>
	<?php
		//get count of rows in result
		$elementcount = $questions->num_rows();
		$elem_per_col = ceil($elementcount/5);
		$j = 0;
		
		for($i=0; $i <= 4; $i++) {
			echo '<div class="bigbtnblue" uk-grid>';
			for($k=0; $k<$elem_per_col; $k++ ){
				if ($j < $elementcount) {
					$questionrtn = $questions->row($j);
					echo "<div class='uk-card-small uk-card-body'>";
					echo form_radio(array('name'=>'question_no', 'id'=>'q' . $questionrtn->question_no, 'value' => $questionrtn->question_no, 'checked' => FALSE, 'style' =>'class:bigbtnbluebtn'));
					echo form_label($questionrtn->ptsposs, 'q' . $questionrtn->question_no, array('class' => 'bigbtnbluelabel'));
					echo "</div>";
					$j++;
				}
			}
			echo "</div>";

		}
	?>
</div>
<div class="uk-child-width-1-5 uk-grid" uk-grid>
	<?php
	$color = array('blue','green','red');
	for($j=0;$j<3;$j++) {
		$teaminfo = $teams->row($j);
		echo "<div class='uk-card uk-card-small uk-text-center ".$color[$j]."border' uk-grid>";
		echo "<div>" . $teaminfo->team_no . " - " . $teaminfo->teamname . "</div>";
		echo "<div>". form_radio(array('name'=>'t' . $j, 'id'=>'t' . $j . '-', 'value' => '2', 'checked' => FALSE));
		echo form_label("-", 't' . $j . '-') . "</div>";
		echo "<div>" . form_radio(array('name'=>'t' . $j, 'id'=>'t' . $j, 'value' => '', 'checked' => TRUE));
		echo form_label("0", 't' . $j). "</div>";
		echo "<div>" . form_radio(array('name'=>'t' . $j, 'id'=>'t' . $j . '+', 'value' => '1', 'checked' => FALSE));
		echo form_label("+", 't' . $j . '+'). "</div>";
		echo "<div class='" . $color[$j] . "'>". ucfirst($color[$j]);
		echo form_hidden('tr' . $j, $teaminfo->team_no) . "</div>";
		echo "</div>";
	}
	?>
<div uk-grid>
	<div class='uk-form-label'>Daily double</div>
	<?php
	$teamdrop = array('' => 'Select');
	foreach($teams->result_array() as $row) 
		{
			$teamdrop[$row['team_no']] = $row['teamname'];
		}	
	echo "<div>" . form_dropdown('dailydblteam', $teamdrop, '', array('class'=>'uk-select')) . '</div>';
	echo "<div>" . form_input(array('name'=>'dailydblwager','id'=>'dailydblwager',
	'value'=>'','maxlength'=>'5','size'=>'5','class'=>'uk-input')) . "</div>";	
	?>
</div>
<div uk-grid>
	<?php echo form_submit('update','Add result',array('class' => 'uk-button uk-button-primary'));?>
<input type="hidden" id="round" name="round" class="round_no" value="3" />&nbsp;
<button type="button" id="btn_scorebd" name="btn_scorebd" class="uk-button uk-button-secondary btngrnbg add_field_button">Refresh scoreboard</button></form>
</div>
</div>
	<?php 
	echo form_close();
	?>
    </div>
    <div class="uk-width-1-6@s"><div>
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
<script type="text/javascript">
    $(document).ready(function(){
        var add_button      = $(".add_field_button"); //Add button ID
    
        $(add_button).on('click', function(){ //on add input button click
		var round_no = $('.round_no').val();
		console.log(round_no);
            $.ajax({
                type : "POST",
                url  : "<?php echo site_url('pages/refresh_scoreboard')?>",
                dataType : "JSON",
                data : {round:round_no},
		success: function(data){
			console.log(data);
			}
            });
            return false;
	});
});
</script>
<?php
