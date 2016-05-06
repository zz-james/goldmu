
<?php $linktext = trim(get_theme_mod( "breadcrumb_link" )); ?>
<?php $link = trim(get_theme_mod( "breadcrumb_href" )); ?>




          <div class="breadcrumb">
            <div class="wide-wrapper clearfix">
              <div class="breadcrumb-wrapper">
                <div class="dropdown-nav">In this section
                  <div class="touchButton touchButton--disabled"><span class="touchButton--plus"></span><span class="touchButton--minus"></span></div>
                </div>

                <nav class="secondary-nav" role="navigation">
                  <h2 class="visuallyhidden">Breadcrumb navigation</h2>
                  <ul>
                    <li class="expanded">
                      <a href="http://www.gold.ac.uk/departments/">Departments</a>
                      <div class="touchButton touchButton--disabled"><span class="touchButton--plus"></span></div>
                    </li>

                    <?php if ($linktext): ?>
                    <li class="expanded">
                      <a href="<?php echo $link; ?>"><?php echo $linktext; ?></a>
                      <div class="touchButton touchButton--disabled"><span class="touchButton--plus"></span></div>
                    </li>
                    <?php endif; ?>

                    <li class="expanded">
                      <a href="#"><?php bloginfo( 'name' ); ?></a>
                      <div class="touchButton touchButton--disabled"><span class="touchButton--plus"></span></div>
                    </li>
                  </ul>
                </nav>

                <a class="social-expand">Open social sharing</a><!-- new and some part removed -->
                <div class="social-hubs-container">
                  <ul class="social-hubs social-hubs--breadcrumb">
                    <li><a href="https://twitter.com/intent/tweet?text=Computing+research&amp;url=http%3A%2F%2Fwww.gold.ac.uk%2Fcomputing%2Fresearch%2F&amp;via=goldsmithsuol">Twitter</a></li>
                    <!--<li><a href="https://linkedin.com/">Linkedin</a></li>-->
                    <li><a href="https://www.facebook.com/sharer/sharer.php?u=http://www.gold.ac.uk/computing/research/" onclick="return fbs_click(450, 350)" target="_blank" title="Share on Facebook">Facebook</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div><!-- close breadcrumb -->