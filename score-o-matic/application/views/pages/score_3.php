<?php
$boards = array('','bb','pyramid','jeopardy');
echo '<div id="'.$boards[$rnd].'"><div id="dynscore">';
$color = array('blue','green','red');
	echo "<div style='height:50vh'></div>";
	echo "<div class='uk-grid uk-child-width-1-3@s' uk-grid>";
	for ($j=0;$j<3;$j++) {
		$scoredisp = $scores->row($j);
		echo "<div><div class='uk-text-right score_j score_" . $color[$j] ."'>" . round($scoredisp->ptswithbonus,0) . "</div>";
		echo "<div class='uk-text-center name_j name_j_".$color[$j]."'>" . $scoredisp->teamname . "</div></div>";
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
