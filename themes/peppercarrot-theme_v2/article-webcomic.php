<?php
if(!defined('PLX_ROOT')) exit;

include_once(dirname(__FILE__).'/bottom_links.php');
include_once(dirname(__FILE__).'/navigation.php');

include(dirname(__FILE__) . '/header.php');

?>

<div class="containercomic" <?php eval($plxShow->callHook('MyMultiLingueBackgroundColor')) ?>>
  <main class="main grid" role="main">
    <section>
      <article class="article" role="article" id="post-<?php echo $plxShow->artId(); ?>">

        <div class="col sml-12 sml-text-right">
          <nav class="nav" role="navigation">
            <div class="responsive-langmenu">
              <?php
                eval($plxShow->callHook('MyMultiLingueComicToggleButtons'));
                $episodeData = $plxShow->callHook('MyMultiLingueEpisodeData');
                eval($plxShow->callHook('MyMultiLingueLanguageMenu', array(
                    'pageurl' => '?lang={LANG}',
                    'testdir' => $episodeData['directory'].'/low-res',
                    'includewebsite' => false
                )));
               ?>
            </div>
          </nav>
        </div>

        <div style="clear:both;">
        </div>

        <?php eval($plxShow->callHook('MyMultiLingueComicHeader')) ?>

        <?php showWebcomicNavigator('', ''); ?>

        <section class="text-center">
          <?php eval($plxShow->callHook('MyMultiLingueComicDisplay')) ?>
        </section>
        
        <!-- Temporary banneer to help with the launch of books -->
        <div class="container">
          <section class="col sml-12 med-12 lrg-12">
            <a href="https://www.davidrevoy.com/shop">
              <img src="<?php $plxShow->template(); ?>/img/books-available.jpg" style="max-width:1200px;margin-bottom:20px;border-radius:5px">
            </a>
        </section>
        </div>

      </article>

      <?php
        showBottomArticleLinks(
          $plxShow->callHook('MyMultiLingueSourceLink'),
          $plxShow->callHook('MyMultiLingueCommentLink'),
          $plxShow->callHook('MyMultiLingueFramagitLink')
        );
      ?>

    </section>
  </main>
</div>

<?php include(dirname(__FILE__).'/footer.php'); ?>
