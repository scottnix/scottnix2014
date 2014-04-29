// google analytics
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-21469201-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();


// mobile menu toggle classes
jQuery(document).ready(function($) {
    $('.menu-toggle').click(function(e) {
        $('.menu-toggle').toggleClass('toggled');
        $('.menu').toggleClass('active');
    });
});