<div class="uk-container uk-container-large">
<?php 
echo form_open('team_update', array('id'=>'team_update'), array('event' => $event));
?>
	<table class="uk-table uk-table-striped uk-table-small">
		<thead>
			<tr><th>Team #</th><th>Team Name</th><th>Dollar Raised</th><th>Member 1</th><th>Member 2</th><th>Workgroup 1</th><th>Workgroup 2</th><th></th></tr>
		</thead>
		<tbody class="input_fields_wrap">
			<?php
			$j = 0;
			foreach($teams as $teamid) {
				echo "<tr><td>" . form_input(array('name'=>'team_no[]' ,'id'=>'team_no', 'value'=>$teamid['team_no'],'maxlength'=>'5','size'=>'5','class'=>'uk-input')) . "</td>";
				echo "<td>" . form_input(array('name'=>'teamname[]' ,'id'=>'teamname', 'value'=>$teamid['teamname'],'maxlength'=>'100','size'=>'25','class'=>'uk-input')) . "</td>";
				echo "<td>" . form_input(array('name'=>'dollarraised[]' ,'id'=>'dollarraised', 'value'=>$teamid['dollarraised'],'maxlength'=>'6','size'=>'6','class'=>'uk-input')) . "</td>";
				echo "<td>" . form_input(array('name'=>'member1[]' ,'id'=>'member1', 'value'=>$teamid['member1'],'maxlength'=>'100','size'=>'25','class'=>'uk-input')) . "</td>";
				echo "<td>" . form_input(array('name'=>'member2[]' ,'id'=>'member2', 'value'=>$teamid['member2'],'maxlength'=>'100','size'=>'25','class'=>'uk-input')) . "</td>";
				echo "<td>" . form_input(array('name'=>'workgroup1[]' ,'id'=>'workgroup1', 'value'=>$teamid['workgroup1'],'maxlength'=>'100','size'=>'25','class'=>'uk-input')) . "</td>";
				echo "<td>" . form_input(array('name'=>'workgroup2[]' ,'id'=>'workgroup2', 'value'=>$teamid['workgroup2'],'maxlength'=>'100','size'=>'25','class'=>'uk-input')) . "</td><td><a href='#' class='remove_field'>Remove row</a></td></tr>";
				$j++;
			}
			?>
		</tbody>
	</table>
<?php 
echo form_button('add_row', 'Add row', array('class' => 'uk-button uk-button-secondary add_field_button')) . " " . form_submit('update','Update teams', array('class' => 'uk-button uk-button-primary')) . anchor('pages/emp_1', 'Round 1', array('class' => 'uk-button uk-button-secondary'));
	
	form_close();
?>
</div>
<script>
	$(document).ready(function() {
	    var max_fields      = 25; //maximum input boxes allowed
	    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
	    var add_button      = $(".add_field_button"); //Add button ID
    
	    var x = <?=$j?>; //initlal text box count
	    $(add_button).click(function(e){ //on add input button click
	        e.preventDefault();
	        if(x < max_fields){ //max input box allowed
	            x++; //text box increment
	            $(wrapper).append('<tr><td><input type="text" name="team_no[]" value="" id="team_no" maxlength="5" size="5" class="uk-input"  /></td><td><input type="text" name="teamname[]" value="" id="teamname" maxlength="100" size="25" class="uk-input"  /></td><td><input type="text" name="dollarraised[]" value="" id="dollarraised" maxlength="6" size="6" class="uk-input"  /></td><td><input type="text" name="member1[]" value="" id="member1" maxlength="100" size="25" class="uk-input"  /></td><td><input type="text" name="member2[]" value="" id="member2" maxlength="100" size="25" class="uk-input"  /></td><td><input type="text" name="workgroup1[]" value="" id="workgroup1" maxlength="100" size="25" class="uk-input"  /></td><td><input type="text" name="workgroup2[]" value="" id="workgroup2" maxlength="100" size="25" class="uk-input"  /></td><td><a href="#" class="remove_field">Remove row</a></td></tr>'); //add input box
	        }
	    });
    
	    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
	        e.preventDefault(); $(this).closest('tr').remove(); x--;
	    })
	});
</script>