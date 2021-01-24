<?php
$boards = array('','bb2c','bb2c','jeopardy','pyramid');
echo '<div id="'.$boards[$rnd].'"><div id="dynscore"></div></div>';
?>
<script>
$(document).ready(function(){
	sendRequest();
});

function sendRequest(){
	$.ajax({
		type: 'POST',
  	  	url: "<?=base_url()?>getscore_<?=$boards[$rnd]?>",
  	  	cache: false
		})
  .done(function( html ) {
    $( "#dynscore" ).html( html );
  });
  //			setTimeout(function(){
  //				sendRequest();
  //			}, 5000);
  
};
</script>