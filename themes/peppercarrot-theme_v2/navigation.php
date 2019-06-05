<?php if(!defined('PLX_ROOT')) exit; 
// Calling this template require to declare:
// $buttonthemeA : the primary css class theme, for first/last button
// $buttonthemeB : the secondary css class theme, for first/last button
?>

<!-- Navigation-->
<div class="readernav col sml-12 med-12 lrg-12 sml-centered">
  <div class="grid">  

  <div class="col sml-hide sml-12 med-3 lrg-3 med-show">
    <div class="<?php echo ''.$buttonthemeB.''; ?>">
      <a class="readernavbutton" style="text-align:left;" href="<?php $plxShow->lastArtList('#art_url',1,3,'','sort'); ?>">
        &nbsp; « &nbsp; <?php $plxShow->lang('FIRST') ?>
      </a>
    </div>
  </div>

  <?php 
  $nextep=$plxShow->getLang('UTIL_NEXT_EPISODE');
  $prevep=$plxShow->getLang('UTIL_PREVIOUS_EPISODE');
  eval($plxShow->callHook("artPrevNext", array(''.$prevep.'',''.$nextep.'',''.$buttonthemeA.'',''.$buttonthemeB.'')));
  ?>

  <div class="col sml-hide sml-12 med-3 lrg-3 med-show">
    <div class="<?php echo ''.$buttonthemeB.''; ?>">
      <a class="readernavbutton" style="text-align:right;" href="<?php $plxShow->lastArtList('#art_url',1,3); ?>">
        <?php $plxShow->lang('LAST') ?>&nbsp; » &nbsp;
      </a>
    </div>
  </div>

  </div>
</div>
