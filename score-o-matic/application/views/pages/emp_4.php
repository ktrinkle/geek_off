<div class="uk-container uk-container-large">
<?php 
echo form_open('emp_4', array('id'=>'emp4'), array('event' => $event, 'question_no' => '350'));?>
<div class="uk-grid" uk-grid>
    <div class="uk-width-1-6@s">
    </div>
    <div class="uk-width-1-2@s"><div>
	<table class="uk-table uk-table-striped uk-table-small">
		<thead>
			<tr><th>Team Name</th><th>Score</th></tr>
		</thead>
		<tbody>
			<?php
			foreach($teams->result() as $teamid) {
				echo "<tr><td>" . form_label($teamid->teamname, 't'. $teamid->team_no) . "</td>";
				echo "<td>" . form_input(array('name'=>'t' . $teamid->team_no ,'id'=>'t' . $teamid->team_no, 'value'=>'','maxlength'=>'5','size'=>'5','class'=>'uk-input')) . "</td></tr>";	
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
echo form_open('finalize_round',array('id'=>'finalize'),array('round' => '3', 'event' => $event, 'next_page' => 'home')) . form_submit('finalize', 'Finalize Jeopardy', array('class' => 'uk-button uk-button-secondary')) . form_close();
?>
<input type="hidden" id="round" name="round" class="round_no" value="3" />&nbsp;
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
