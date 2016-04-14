
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
        // do something
    });
  }

  /* ----------------- private functions ------------------ */

  function render(data) {
    htmlString = '';
    data.forEach(function(link){
      htmlString += "<li> <a href='"+link.url+"'>"+link.label+"</a> </li>";
    });
    $el.find('#loading').remove();
    $el.append(htmlString);
  }

  this.initialise();

  return this;
}

var mainMenu  = new ViewMenuBuilder(jQuery('#main-menu') , {url:'/api/mainmenuitems.json'});
var staffMenu = new ViewMenuBuilder(jQuery('#staff-menu'), {url:'/api/staffmenuitems.json'});

