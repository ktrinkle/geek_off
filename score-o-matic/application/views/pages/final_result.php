<div class="uk-container uk-container-large">
	<table class="uk-table uk-table-striped uk-table-small">
		<thead>
			<tr><th>Rank</th><th>Team #</th><th>Team Name</th><th>Member 1</th><th>Member 2</th></tr>
		</thead>
		<tbody class="input_fields_wrap">
			<?php
			$j = 0;
			foreach($teams->result() as $teamid) {
				echo "<tr><td>" . $teamid->rnk ."</td>";
				echo "<td>" . $teamid->team_no . "</td>";
				echo "<td>" . $teamid->teamname . "</td>";
				echo "<td>" . $teamid->member1 . "</td>";
				echo "<td>" . $teamid->member2 . "</td></tr>";
			}
			?>
		</tbody>
	</table>
</div>