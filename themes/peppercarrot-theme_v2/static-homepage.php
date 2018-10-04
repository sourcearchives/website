<?php include(dirname(__FILE__).'/header.php'); 
$lang = $plxShow->defaultLang($echo);
?>
<div class="container">
<main class="main grid" role="main">

		<section class="col sml-12">

      <div class="grid">

      <div class="translabar col sml-12 med-12 lrg-12 sml-centered sml-text-center">
        <ul class="menu" role="toolbar">
          <?php eval($plxShow->callHook('MyMultiLingueStaticAllLang')) ?>
          <li><a class="lang" href="<?php $plxShow->urlRewrite('?static14/documentation&page=010_Translate_the_comic') ?>"><img src="themes/peppercarrot-theme_v2/ico/add.svg" alt="+"/> <?php $plxShow->lang('ADD_TRANSLATION') ?></a></li>
        </ul>
      </div>
      
      <section class="col sml-12 med-12 lrg-12" >
        <div class="cover">
          <div class="covertextoverlay">
            <h1><?php $plxShow->lang('HOMEPAGE_BIG_TEXT') ?></h1>
            <div id="supportmebox">
            <?php include(dirname(__FILE__).'/supportme.php'); ?>
            </div>
            <div id="moreinfobox">
              <a class="moreinfobutton" href="<?php $plxShow->urlRewrite('?static2/philosophy') ?>" title="<?php $plxShow->lang('HOMEPAGE_MOREINFO_BUTTON') ?>"><?php $plxShow->lang('HOMEPAGE_MOREINFO_BUTTON') ?></a>
            </div>
          </div>
        </div>
      </section>  
      
      <?php # ===========  Latest episode box ================ ?>
      <section class="col sml-12 med-4 lrg-4">
        <div class="homebox">
          <h2><?php $plxShow->lang('HOMEPAGE_LAST_EPISODE') ?></h2>
          <div class="homecontent" style="margin-right: -1rem;">
          <?php eval($plxShow->callHook("vignetteArtList", array('
          <div class="col sml-12 med-12 lrg-12" style="padding:0 1rem 0 0;">
            <div class="lastep">
              <figure>
                <a href="#art_url" title="#art_title">
                  <img src="plugins/vignette/plxthumbnailer.php?src=#episode_vignette&amp;w=630&amp;h=545&amp;s=1&amp;q=65" alt="#art_title" title="#art_title, click to read" >
                </a>
                <figcaption><a href="#art_url" title="#art_title"><span class="detail">#art_date#art_nbcoms</span></a></figcaption>
              </figure>
            </div>
          </div>
          ',1,'003', "...", "rsort"))); ?>
          </div>
            <div style="clear:both;"></div>
          <div class="moreposts" style="margin-bottom: 1rem;">
            <a class="button blue" href="<?php $plxShow->urlRewrite('?static3/webcomics') ?>" title="<?php $plxShow->lang('WEBCOMIC_EPISODE') ?>"><?php $plxShow->lang('WEBCOMIC_EPISODE') ?>  &nbsp;<img class="svg" src="themes/peppercarrot-theme_v2/ico/go.svg" alt="→"/></a>
          </div>
        </div>
      </section>
      
      <?php # ===========  News and updates box ================ ?>
      <section class="col sml-12 med-8 lrg-8">
        <div class="homebox news">
          <h2><?php $plxShow->lang('HOMEPAGE_NEWS_UPDATE') ?></h2>
          <div class="homecontent" style="margin-right: -1rem;">
            <?php 
            eval($plxShow->callHook("vignetteArtList", array('
              <div class="col sml-6 med-4 lrg-4" style="padding:0 1rem 0 0; margin: 0 0 1rem 0;">
                <div class="homethumbnail">
                <figure>
                  <a href="#art_url" title="#art_title">
                    <img src="plugins/vignette/plxthumbnailer.php?src=#art_vignette&amp;w=270&amp;h=160&amp;s=1&amp;q=60&amp;a=t" alt="#art_title" title="#art_title, click to read" >
                  </a>
                  <figcaption><a href="#art_url" title="#art_title">#art_supertitle #art_date#art_nbcoms</span></a></figcaption>
                </figure>
                </div>
              </div>
              ',6,'001|004|005|006|007|008|009|010|011|012|013', "...", "rsort"))); 
            ?>
          </div>
            <div style="clear:both;"></div>
          <div class="moreposts" style="margin-top: 0.3rem;">
            <a class="button blue" href="<?php $plxShow->pageBlog('#page_url') ?>" title="<?php $plxShow->lang('HOMEPAGE_MOREPOSTS_BUTTON') ?> (<?php $plxShow->lang('BLOG') ?>) "><?php $plxShow->lang('HOMEPAGE_MOREPOSTS_BUTTON') ?> (<?php $plxShow->lang('BLOG') ?>) &nbsp;<img class="svg" src="themes/peppercarrot-theme_v2/ico/go.svg" alt="→"/></a>
          </div>
        </div>
      </section>

      <div style="clear:both; padding-top: 1rem;"></div>

      <?php # ===========  Fan Art box ================ ?>
      <section class="col sml-12 med-4 lrg-4">
        <div class="homebox news">
          <h2><?php $plxShow->lang('HOMEPAGE_FANART') ?></h2>
          <div class="homecontent" style="margin-right: -1rem;">
            <?php
            #variables:
            $fanartcounter = 0;
            $pathartworks = '0_sources/0ther/fan-art';
            $hide = array('.', '..');
            $mainfolders = array_diff(scandir($pathartworks), $hide);
            $search = glob($pathartworks."/*.jpg");
            rsort($search);
            # we loop on found episodes
            if (!empty($search)){ 
              foreach ($search as $filepath) {
                if ( $fanartcounter < 4 ){
                  # filename extraction
                  $fileweight = (filesize($filepath) / 1024) / 1024;
                  $fanartcounter = $fanartcounter + 1;
                  $filename = basename($filepath);
                  $fullpath = dirname($filepath);
                  $dateextracted = substr($filename,0,10).'';
                  $dateextracted = str_replace('-', '&#47;', $dateextracted);
                  $filenameclean = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
                  $filenameclean = substr($filenameclean, 11); // rm iso date
                  $filenameclean = str_replace('_', ' ', $filenameclean);
                  $filenameclean = str_replace('-', ' ', $filenameclean);
                  $details = strstr($filenameclean, 'by');
                  $title = stristr($filenameclean, 'by', true);
                  $filenameclean = str_replace('featured', '', $filenameclean);
                  $filenamezip = str_replace('jpg', 'zip', $filename);      
                  echo '<div class="col sml-3 med-6 lrg-6" style="padding:0 1rem 0 0; margin: 0 0 1rem 0;">
                          <div class="homethumbnail">
                            <figure>
                            <a href="0_sources/0ther/fan-art/'.$filename.'" title="'.$filenameclean.', '.$dateextracted.'">
                              <img src="plugins/vignette/plxthumbnailer.php?src=0_sources/0ther/fan-art/'.$filename.'&amp;w=130&amp;h=110&amp;s=1&amp;q=65&amp;a=t" alt="'.$filenameclean.'" title="'.$filenameclean.', '.$dateextracted.'" >
                            </a>
                            <figcaption><a href="0_sources/0ther/fan-art/'.$filename.'" title="'.$filenameclean.', '.$dateextracted.'">'.$title.' <br/><span class="detail">'.$details.' '.$dateextracted.'</span></a></figcaption>
                          </figure>
                          </div>
                        </div>
                  ';
                }
              }
            }
            $fanartcounter = 0;
            $search = glob($pathartworks."/*.jpg");
            if (!empty($search)){ 
              foreach ($search as $filepath) {
                $fanartcounter = $fanartcounter + 1;
              }
            }?>  
            <div style="clear:both;"></div>
            <div class="moreposts" style="margin-top: 0.3rem;">
              <a  class="button blue" href="<?php $plxShow->urlRewrite('?static10/fanart-gallery') ?>" title="Go to the blog page">
                <?php $plxShow->lang('HOMEPAGE_MOREPOSTS_BUTTON') ?> (<?php $plxShow->lang('HOMEPAGE_FANART') ?>) &nbsp;&nbsp;<img class="svg" src="themes/peppercarrot-theme_v2/ico/go.svg" alt="→"/>
              </a>
            </div>
          </div>
        </div>
      </section>

      <?php # ===========  Framagit feed box ================ ?>
      <section class="col sml-12 med-4 lrg-4">
        <div class="homebox news">
          <h2>Git <?php $plxShow->lang('SOURCES') ?></h2>
          <div>
          <?php 
          $feed_url = "https://framagit.org/peppercarrot.atom"; 
          $content = file_get_contents($feed_url);
          $x = new SimpleXmlElement($content);
           
          echo "<ul>";
           
          foreach($x->entry as $item) {
          $published_on = $item->updated;
          $published_on = strftime("%Y-%m-%d", strtotime($published_on));
          $link = $item->link;
          $title = $item->title;
          $content = $item->summary;
              echo "<li>" . $published_on . " : <a href=" . $link . " title=" . $title . "><b>" . $title . "</b></a><br/>" . $content . "</li>";
          }
          echo "</ul>";
          ?>
          <div style="clear:both;"></div>
            <div class="moreposts" style="margin-top: 0.3rem;">
              <a  class="button blue" href="<?php $plxShow->urlRewrite('?static10/fanart-gallery') ?>" title="Go to the blog page">
                <?php $plxShow->lang('HOMEPAGE_MOREPOSTS_BUTTON') ?> (<?php $plxShow->lang('HOMEPAGE_FANART') ?>) &nbsp;&nbsp;<img class="svg" src="themes/peppercarrot-theme_v2/ico/go.svg" alt="→"/>
              </a>
          </div>
        </div>
      </section>

	</main>
          <?php include(dirname(__FILE__).'/share-static.php'); ?>  <br/>
</div>
<?php include(dirname(__FILE__).'/footer.php'); ?>
