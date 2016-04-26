<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the content divs and all content after
 *
 * @package Nucleare Gold
 */
?>

        </div><!-- main-container -->
      </div><!-- pusher-wrapper -->
    </main><!-- #content -->
  </div><!-- close site-wrapper -->

  <footer id="colophon" class="site-footer" role="contentinfo">
    <div class="site-info small-part"></div>
  </footer>

  <?php wp_footer(); ?>

  <script>



  jQuery(function(){


    siteUIClosing = function() {
      jQuery("body").addClass("site-ui-closing"),
      setTimeout(function() {
        jQuery("body").removeClass("site-ui-closing")
      }, 1e3)
    }

    jQuery('.charm-menu').on("click", function() {
      jQuery("body").toggleClass("menu-open");
      jQuery("body").toggleClass("menu-closed");
      jQuery("body").removeClass("staff-students-open");
      jQuery("body").addClass("staff-students-closed");
      jQuery("#student-nav").attr("aria-hidden", "true");

      jQuery("body").hasClass("menu-closed") ?
          ( jQuery("#primary-nav").attr("aria-hidden", "true"), siteUIClosing() ) : 
          ( jQuery("body").hasClass("tabbing") && setTimeout(function() { jQuery("#primary-nav").find("li > a").eq(0).focus() }, 500), jQuery("#primary-nav").attr("aria-hidden", "false") )
    });

    jQuery(".dropdown-nav, .dropdown-nav touchButton").click(function(e)
    {
      jQuery(".breadcrumb-wrapper").toggleClass("active");

      setTimeout(
        function() {
          jQuery(".dropdown-nav .touchButton").toggleClass("active");
          jQuery(".breadcrumb .secondary-nav").toggleClass("open");
          jQuery(".dropdown-nav").toggleClass("open");
        },50);
    });

    jQuery(".header__charm--text").on("click", function() {

      jQuery("body").toggleClass("staff-students-open"),
      jQuery("body").toggleClass("staff-students-closed"),

      jQuery("body").removeClass("menu-open"),  // if the regular menu is open, close it.
      jQuery("body").addClass("menu-closed"),

      jQuery("#primary-nav").attr("aria-hidden", "true"),

      jQuery("body").hasClass("staff-students-closed") ?
          ( jQuery("#student-nav").attr("aria-hidden", "true"), siteUIClosing() ) :
          ( jQuery("body").hasClass("tabbing") && setTimeout( function() { jQuery("#student-nav").find("li > a").eq(0).focus() }, 500 ),jQuery("#student-nav").attr("aria-hidden", "false") );

    });

  });
  </script>

</body>
</html>