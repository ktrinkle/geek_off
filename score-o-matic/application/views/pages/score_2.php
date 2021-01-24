<?php
$boards = array('','bb','pyramid','jeopardy');
echo '<div id="'.$boards[$rnd].'"><div id="dynscore">';
	echo "<div class='uk-grid uk-child-width-1-2@s' uk-grid>";
	echo "<div id='pyramidlogo'></div><div>";
	foreach ($scores->result() as $scoredisp) {
		echo "<div class='uk-grid-large uk-grid' uk-grid'>";
		echo "<div class='uk-width-1-6 uk-text-left'>" . $scoredisp->team_no . "</div>";
		echo "<div class='uk-width-1-2 uk-text-left uk-text-truncate'>" . $scoredisp->teamname . "</div>";
		echo "<div class='uk-width-1-6 uk-text-right'>" . round($scoredisp->ptswithbonus,0) . "</div></div>";
	}
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
