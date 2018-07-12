(function($){
  $(function(){

    $('.button-collapse').sideNav();

    // Detect touch screen and enable scrollbar if necessary
    function is_touch_device() {
      try {
        document.createEvent("TouchEvent");
        return true;
      } catch (e) {
        return false;
      }
    }
    if (is_touch_device()) {
      $('#slide-out').css({ overflow: 'auto'});
    }

  }); // end of document ready
})(jQuery); // end of jQuery name space