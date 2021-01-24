<?php
$boards = array('','bb','pyramid','jeopardy');
echo '<div id="'.$boards[$rnd].'"><div id="dynscore">';
?>
	<table>
		<thead>
			<tr><th colspan="21" class="uk-text-center">A I R L I N E &nbsp; G E E K &nbsp; O F F</th></tr>
			<tr><th colspan="21" class="uk-text-center">-------------------------------</th></tr>
			<tr class='uk-text-nowrap'><th colspan="2" class="uk-text-left teamnm">TEAM NAME</th>
				<?php
				for($k=1;$k<=15;$k++) {
					echo "<th class='uk-text-right score'>" . $k . "</th>";
				}
				?>
			<th class='uk-text-right score'>T1</th><th class='uk-text-right score'>T2</th>
			<th class='uk-text-right bonus'>BONUS</th><th class='uk-text-right ttl'>TTL</th></tr>
		</thead>
		<tbody>
			<?php
			//array return
			foreach($scores->result() as $teamid) {
				echo "<tr><td class='uk-text-right scoreteam'>" . $teamid->team_no . "</td>";
				echo "<td class='teamname uk-text-nowrap'>" . substr(strtoupper($teamid->teamname),0,17) . "</td>";
				for($k=1;$k<=17;$k++) {
					$m = 'q' . $k;
					echo "<td class='uk-text-right score'>" . $teamid->$m . "</td>";
				}
				echo "<td class='uk-text-right bonus'>" . $teamid->bonus . "</td>";
				echo "<td class='uk-text-right ttl'>" . $teamid->ptswithbonus . "</td></tr>";
			}
			?>
		</tbody>
	</table>
<?php
	echo "</div>";
echo "</div></div>";
?>
    <script>
    function refresh() {
      jQuery.ajax({
        url: '',
        dataType: 'text',
        success: function(html) {
          $('#dynscore').replaceWith($.parseHTML(html));
          setTimeout(refresh,3000);
        }
      });
    }
    refresh();
    </script>
