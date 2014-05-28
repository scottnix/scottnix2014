// google universal analytics code
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-21469201-1', 'scottnix.com');
ga('send', 'pageview');


// mobile menu toggle classes
jQuery(document).ready(function($) {
    $('.menu-toggle').click(function(e) {
        $('.menu-toggle').toggleClass('toggled');
        $('.menu').toggleClass('active');
    });
});

// testing some event tracking
// developers.google.com/analytics/devguides/collection/analyticsjs/events
$('.demo').on('click', function() {
  ga('send', 'event', 'button', 'click', 'demo-link');
});

$('.download').on('click', function() {
  ga('send', 'event', 'button', 'click', 'download-link');
});