<div class="uk-container uk-container-large">
<?php echo form_open('emp_1', array('id'=>'emp1'), array('event' => $event));?>
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
		//get count of rows in result
		$elementcount = $teams->num_rows();
		$elem_per_col = ceil($elementcount/3);
		$j = 0;
		
		for($i=0; $i <= 2; $i++) {
			echo '<div class="bigbtngrn uk-width-1-3" uk-grid>';
			for($k=0; $k<$elem_per_col; $k++ ){
				if ($j < $elementcount) {
					$teamid = $teams->row($j);
					echo "<div class='uk-card-small uk-card-body'>";
					echo form_checkbox(array('name'=>'t'. $teamid->team_no, 'id' => 't'. $teamid->team_no, 'value' => '1', 'checked' => FALSE, 'style' => 'class:bigbtngrnlabel'));
					echo form_label('Team&nbsp;' . $teamid->team_no, 't' . $teamid->team_no, array('class' => 'bigbtngrnlabel'));
					echo "</div>";
					$j++;
				}
			}
			echo "</div>";

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

echo form_submit('update','Add result', array('class' => 'uk-button uk-button-primary')) . form_close();
echo form_open('finalize_round',array('id'=>'finalize'),array('round' => '1', 'event' => $event, 'next_page' => 'emp_2')) . form_submit('finalize', 'Finalize round 1', array('class' => 'uk-button uk-button-secondary')) . form_close();
?>
<input type="hidden" id="round" name="round" class="round_no" value="1" />&nbsp;
<button type="button" id="btn_scorebd" name="btn_scorebd" class="uk-button uk-button-secondary btngrnbg add_field_button">Refresh scoreboard</button></form>
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
