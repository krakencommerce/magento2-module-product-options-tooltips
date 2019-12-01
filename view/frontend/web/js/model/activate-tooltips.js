define([
  'jquery',
  'tooltipster',
], function ($) {
  'use strict';

  return function () {
    var viewportWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

    $('.tooltip-info').tooltipster({
      theme: 'tooltipster-shadow',
      maxWidth: '500',
      side: (viewportWidth >= 768) ? "left" : "bottom",
      repositionOnScroll:true,
      // Custom trigger so this will work on iOS
      trigger: 'custom',
      triggerOpen: { mouseenter: true, touchstart: true },
      triggerClose: { mouseleave: true, tap: true },
      debug: false,
    });
  };
});
