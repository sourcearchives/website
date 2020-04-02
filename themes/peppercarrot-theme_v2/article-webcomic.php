<?php include(dirname(__FILE__) . '/header.php');

/**
 * An Option Button to show below the main page navigator
 */
class NavigationToggleButton {
  private $css;
  private $link;
  private $title;
  private $status;

# TODO plugins/plxMyMultiLingue/plxMyMultiLingue.php:
# plugins/cache.php:

# TODO make the 2 buttons show each other's options

  /**
   * Construct a button.
   *
   * @param string  $title           Button title to display to user
   *
   * @param string  $varname         Variable name to add to URL, format '&varname=on'
   *
   * @param string  $onname          Value to use for switching the option on,
   *                                 format '&varname=onname'
   *
   * @param string  $offname         Value to use for switching the option off,
   *                                 format '&varname=offname'
   *
   * @param string  $sessionvar      Name of the $_SESSION entry to remember the option
   *
   * @param string  $sessiononname   Value for sessionvar when option is on
   *
   * @param string  $sessionoffname  Value for sessionvar when option is off
   *
   * @return void
   */
  function __construct($title, $varname, $onname, $offname, $sessionvar, $sessiononname, $sessionoffname) {
    global $_GET, $_SESSION;

    $this->title = $title;

    # Have we got a new variable 'varname' in URL ? grab and security fix it.
    $this->status = htmlspecialchars($_GET[$varname]);
    $this->status = preg_replace('/[^A-Za-z0-9\._-]/', '', $this->status);

    if ($this->status == $onname) {
      $this->css = '';
      $this->link = '&' . $varname . '=' . $offname;
    } elseif ($this->status == $offname) {
      $this->css = 'moka';
      $this->link = '&' . $varname . '=' . $onname;
      $_SESSION[$sessionvar] = $sessionoffname;

    } else {
      $this->css = 'moka';
      $this->link = '&' . $varname . '=' . $onname;
    }

    # Have we got a preference in memory from previous page?
    if ($_SESSION[$sessionvar] == $sessiononname) {
      $this->css = '';
      $this->link = '&' . $varname . '=' . $offname;
    }
  }

  /**
   * Print the Button's HTML code
   */
  function printHtml() {
    global $plxShow;
    ?>
    <div class="button top <?php echo ''.$this->css.''; ?>">
      <a href="<?php $plxShow->artUrl(); echo ''.$this->link.''; ?>" class="lang option"><?php echo ''.$this->title.''; ?></a>
    </div>
    <?php
  }

  /**
   * @return  the button's status as parsed in the constructor from $_GET[$varname]
   */
  function status() {
    return $this->status;
  }
}
?>

<div class="containercomic" <?php eval($plxShow->callHook('MyMultiLingueBackgroundColor')) ?>>
  <main class="main grid" role="main">
    <section>
      <article class="article" role="article" id="post-<?php echo $plxShow->artId(); ?>">

        <div class="col sml-12 sml-text-right">
          <nav class="nav" role="navigation">
            <div class="responsive-langmenu">
              <?php
              $hdButton = new NavigationToggleButton('HD 2400px', 'option', 'hd', 'low', 'SessionMemory', 'KeepHD', 'RemoveHD');
              $hdButton->printHtml();
              (new NavigationToggleButton('Transcript', 'transcript', 'on', 'off', 'TranscriptMemory', 'on', 'off'))->printHtml();
              ?>
              <label for="langmenu" style="display: inline-block;"><span class="translabutton"><img src="themes/peppercarrot-theme_v2/ico/language.svg" alt=""/> <?php echo $langlabel;?><img src="themes/peppercarrot-theme_v2/ico/dropdown.svg" alt=""/></span></label>
              <input type="checkbox" id="langmenu">
              <ul class="langmenu expanded">
              <?php eval($plxShow->callHook('MyMultiLingueComicLang')) ?>
              <li class="button"><a class="lang option" href="<?php $plxShow->urlRewrite('?static14/documentation&page=010_Translate_the_comic') ?>"><img src="themes/peppercarrot-theme_v2/ico/add.svg" alt="+"/> <?php $plxShow->lang('ADD_TRANSLATION') ?></a></li>
              </ul>
            </div>
          </nav>
        </div>

        <div style="clear:both;">
        </div>

        <?php eval($plxShow->callHook('MyMultiLingueComicHeader')) ?>

        <?php
        $buttonthemeA = '';
        $buttonthemeB = '';
        include(dirname(__FILE__).'/navigation.php');
        ?>

        <section class="text-center">
          <?php eval($plxShow->callHook("MyMultiLingueComicDisplay", array(''.$hdButton->status().''))) ?>
        </section>

      </article>

      <div class="content">
        <div style="clear:both;">
        </div>

        <div class="grid">

          <section class="col sml-12 med-12 lrg-11 text-center sml-centered">

            <div class="cardsocket mini col sml-4 med-4 lrg-4">
              <div class="cardblock mini">
                <figure class="thumbnail">
                  <a href="<?php eval($plxShow->callHook('MyMultiLingueSourceLinkDisplay')) ?>">
                    <img src="<?php $plxShow->racine() ?>/plugins/vignette/plxthumbnailer.php?src=0_sources/0ther/misc/low-res/2019-06_sources-webcomic_by-David-Revoy.jpg&amp;w=275&amp;h=275&amp;s=1&amp;q=92" alt="Pepper doing shopping,">
                  </a>
                </figure>
                <div class="button milk">
                  <a href="<?php eval($plxShow->callHook('MyMultiLingueSourceLinkDisplay')) ?>">
                    <b>Artworks files</b><br/> Krita files, svg and jpg.
                  </a>
                </div>
              </div>
            </div>


            <div class="cardsocket mini col sml-4 med-4 lrg-4">
              <div class="cardblock mini">
                <figure class="thumbnail">
                  <a href="<?php eval($plxShow->callHook('MyMultiLingueCommentLinkDisplay', array('url'))) ?>#comments">
                    <img src="<?php $plxShow->racine() ?>/plugins/vignette/plxthumbnailer.php?src=0_sources/0ther/misc/low-res/2019-06_comment-webcomic_by-David-Revoy.jpg&amp;w=275&amp;h=275&amp;s=1&amp;q=92" alt="Pepper doing shopping,">
                  </a>
                </figure>
                <div class="button milk">
                  <a href="<?php eval($plxShow->callHook('MyMultiLingueCommentLinkDisplay', array('url'))) ?>#comments">
                    <b><?php eval($plxShow->callHook('MyMultiLingueCommentLinkDisplay', array('nb_com'))) ?> Comment(s)</b> <br/>on the blog.
                  </a>
                </div>
              </div>
            </div>

            <div class="cardsocket mini col sml-4 med-4 lrg-4">
              <div class="cardblock mini">
                <figure class="thumbnail">
                  <a href="<?php eval($plxShow->callHook('MyMultiLingueFramagitLinkDisplay')) ?>">
                    <img src="<?php $plxShow->racine() ?>/plugins/vignette/plxthumbnailer.php?src=0_sources/0ther/misc/low-res/2019-06_framagit-webcomic_by-David-Revoy.jpg&amp;w=275&amp;h=275&amp;s=1&amp;q=92" alt="Pepper doing shopping,">
                  </a>
                </figure>
                <div class="button milk">
                  <a href="<?php eval($plxShow->callHook('MyMultiLingueFramagitLinkDisplay')) ?>">
                    <b>Git repository</b><br/>Sources code (translation)
                  </a>
                </div>
              </div>
            </div>

          <?php
          $buttonthemeA = 'button';
          $buttonthemeB = 'button moka';
          include(dirname(__FILE__).'/navigation.php');
          ?>

            <br/>

            <time style="color: rgba(0,0,0,0.6);" datetime="<?php $plxShow->artDate('#num_year(4)-#num_month-#num_day'); ?>">
              <?php $plxShow->artDate('#num_year(4)-#num_month-#num_day'); ?>
            </time>

          </section>

      </div>

      <div style="clear:both;">
      <br/>


    </section>
  </main>
</div>

<?php include(dirname(__FILE__).'/footer.php'); ?>
