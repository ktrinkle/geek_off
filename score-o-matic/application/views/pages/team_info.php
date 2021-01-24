<div id = 'dynscore'>
<div class="uk-container uk-container-large">
<div class='uk-text-center'><span class='geekofftextblue'>GEEK OFF </span><span class='geekofftextred'><?=substr($event,1,2)?></span></div>
	<table class="uk-table uk-table-striped uk-table-small">
		<thead>
			<tr><th>Team #</th><th>Team Name</th><th>Dollar Raised</th><th>Member 1</th><th>Member 2</th><th>Workgroup 1</th><th>Workgroup 2</th></tr>
		</thead>
		<tbody class="input_fields_wrap">
			<?php
			foreach($teams as $teamid) {
				echo "<tr><td>" . $teamid['team_no'] . "</td>";
				echo "<td>" . $teamid['teamname'] . "</td>";
				echo "<td>" . $teamid['dollarraised'] . "</td>";
				echo "<td>" . $teamid['member1'] . "</td>";
				echo "<td>" . $teamid['member2'] . "</td>";
				echo "<td>" . $teamid['workgroup1'] . "</td>";
				echo "<td>" . $teamid['workgroup2'] . "</td></tr>";
			}
			?>
		</tbody>
	</table>
</div>
</div>
    <script>
    function refresh() {
      jQuery.ajax({
        url: '',
        dataType: 'text',
        success: function(html) {
          $('#dynscore').replaceWith($.parseHTML(html));
          setTimeout(refresh,6000);
        }
      });
    }
    refresh();
    </script>
