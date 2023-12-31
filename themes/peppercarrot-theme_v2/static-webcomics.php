<?php include(dirname(__FILE__).'/header.php'); ?>
<div class="container">
  <main class="main grid" role="main">
		<section class="col sml-12">
      <div class="grid">
        <div class="col sml-12 sml-text-right">
          <nav class="nav" role="navigation">
            <div class="responsive-langmenu">
            <?php eval($plxShow->callHook('MyMultiLingueLanguageMenu', array(
                'statstemplate' => '0_sources/ep[0-9][0-9]*/lang/{LANG}/E[0-9][0-9]*P00.svg'))); ?>
            </div>
          </nav>
        </div>
        <section class="col sml-12 med-12 lrg-12" >
          <div class="cover">
            <div class="covertextoverlay">
              <h1><?php $plxShow->lang('HOMEPAGE_BIG_TEXT') ?>
              </h1>
                <div class="button mandarine big">
                  <a href="<?php $plxShow->urlRewrite('?static2/philosophy') ?>" title="<?php $plxShow->lang('HOMEPAGE_MOREINFO_BUTTON') ?>">
                    <?php $plxShow->lang('HOMEPAGE_MOREINFO_BUTTON') ?>
                    <img src="themes/peppercarrot-theme_v2/ico/go.svg"/>
                  </a>
                </div>
            </div>
          </div>
        </section>

        <!-- Temporary banneer to help with the launch of books
        <section class="col sml-12 med-12 lrg-12">
          <a href="https://www.davidrevoy.com/shop">
            <img src="<?php $plxShow->template(); ?>/img/books-available.jpg" style="width:100%;margin-top:10px;border-radius:5px">
          </a>
        </section>
         -->

        <section class="col sml-12 med-12 lrg-12">
          <div class="homebox">
            <h2 style="margin-top: 0.8rem;"><?php $plxShow->lang('WEBCOMIC_EPISODE') ?></h2>
            <div class="homecontent" style="margin-right: -1rem;">
            <?php eval($plxShow->callHook("vignetteArtList", array('
            <figure class="thumbnail #translationstatus col sml-12 med-6 lrg-4" style="padding:0 1rem 0 0;">
              <a href="#art_url" title="#art_title">
                <img class="#translationstatus" src="plugins/vignette/plxthumbnailer.php?src=#episode_vignette&amp;w=400&amp;s=1&amp;q=92" alt="#art_title" title="#art_title, click to read #translationmessage" >
              </a>
              <figcaption class="#translationstatus text-center"><a href="#art_url" title="#art_title"><span class="detail">#overlay</span></a></figcaption>
            </figure>
            ',99,'003', "...", "rsort"))); ?>

            <div style="clear:both;"></div>
            </div>
          </div>
        </section>

      </div>
    </section>
	</main>
</div>
<?php include(dirname(__FILE__).'/footer.php'); ?>
