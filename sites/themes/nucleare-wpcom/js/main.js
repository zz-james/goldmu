
/**
 * ViewMenuBuilder handles creating the menus from data supplied by www.gold.ac.uk
 * @param {jquery} $el   [jquery object wrapping parent element of DOM fragment we're managing]
 * @param {object} props [object containing config props]
 */
function ViewMenuBuilder($el, props) {

  this.$el  = $el;
  var scope = this,
      url  = props.url; // (.. etc)

  /* ------------------- public methods ------------------- */
  this.initialise = function() {
    var json = jQuery.getJSON(url);

    json.done(render);

    json.fail(function( jqxhr, textStatus, error ) {
        var err = textStatus + ", " + error;
        console.log( "Request Failed: " + err );
    });
  }

  /* ----------------- private functions ------------------ */

  function render(data) {
    htmlString = '';
    data.forEach(function(link){
      htmlString += "<li> <a href='"+link.url+"'>"+link.label+"</a> </li>";
    });
    $el.find('.js-replace-values').remove();
    $el.append(htmlString);
  }

  this.initialise();

  return this;
}

var mainMenu  = new ViewMenuBuilder(jQuery('#main-menu') , {url:'http://www.gold.ac.uk/api/mainmenuitems/index.json'});
var staffMenu = new ViewMenuBuilder(jQuery('#staff-menu'), {url:'http://www.gold.ac.uk/api/staffmenuitems/index.json'});



/* ------------------------------------------------ */


/**
 * the usual whole bunch of jQuery on load and set up event listners shizzle is here
 */
jQuery(function(){

  jQuery.cookieBar();

  siteUIClosing = function() { // this pretty much exactly from gold.ac.uk
    jQuery("body").addClass("site-ui-closing"),
    setTimeout(function() {
      jQuery("body").removeClass("site-ui-closing")
    }, 1e3)
  }

  jQuery('.charm-menu').on("click", function() { // this pretty much exactly from gold.ac.uk
    jQuery("body").toggleClass("menu-open");
    jQuery("body").toggleClass("menu-closed");
    jQuery("body").removeClass("staff-students-open");
    jQuery("body").addClass("staff-students-closed");
    jQuery("#student-nav").attr("aria-hidden", "true");

    jQuery("body").hasClass("menu-closed") ?
        ( jQuery("#primary-nav").attr("aria-hidden", "true"), siteUIClosing() ) :
        ( jQuery("body").hasClass("tabbing") && setTimeout(function() { jQuery("#primary-nav").find("li > a").eq(0).focus() }, 500), jQuery("#primary-nav").attr("aria-hidden", "false") )
  });

  jQuery(".dropdown-nav, .dropdown-nav touchButton").click(function(e) { // this pretty much exactly from gold.ac.uk
    jQuery(".breadcrumb-wrapper").toggleClass("active");

    setTimeout(
      function() {
        jQuery(".dropdown-nav .touchButton").toggleClass("active");
        jQuery(".breadcrumb .secondary-nav").toggleClass("open");
        jQuery(".dropdown-nav").toggleClass("open");
      },50);
  });

  jQuery(".header__charm--text").on("click", function() { // this pretty much exactly from gold.ac.uk

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