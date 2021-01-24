<div class="uk-container uk-container-large">
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
<?php 
echo anchor('pages/' . $next_page, 'Proceed to next round', 'title="Next round"');
?>
</div>
<?php
