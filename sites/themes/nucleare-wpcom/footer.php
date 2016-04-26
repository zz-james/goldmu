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
    jQuery('.charm-menu').click(function(){
      jQuery( 'body' ).toggleClass( "menu-open" );
    });
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


  </script>

</body>
</html>