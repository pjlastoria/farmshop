<?php

$show;

if(!empty($_GET) && $_GET['error'] == 'notAdmin') {

  $show = 'appear';

}
?>

<div class="modal-bg <?= $show ?>">
  <div class="modal">
    <p>Must be an admin to access admin area.</p>
    <button>OK</button>
  </div>
</div>
