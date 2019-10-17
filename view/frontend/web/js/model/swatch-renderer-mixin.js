define([
  'jquery',
  'underscore',
  'activateTooltipster'
], function ($, _, activateTooltipster) {
  'use strict';

  return function () {

    jQuery.widget(
      'mage.SwatchRenderer',
      jQuery['mage']['SwatchRenderer'],
      {
        _RenderControls: function(){

          var productOptionsTooltips = this.options.productOptionsTooltips;
          var parentReturnValue = this._super();
          var normalizeString = function(string){
            // return string;
            return string.replace(/[^a-z0-9]|\s/gmi,"-").toLowerCase();
          };

          try {
            $.each(this.options.jsonConfig.attributes, function () {
              var item = this,
                controlLabelId = 'option-label-' + item.code + '-' + item.id,
                tooltipId = item.code + '_option_tooltip';

              var labelNormalized = normalizeString(item.label);

              if (typeof productOptionsTooltips[labelNormalized] !== 'undefined') {
                $('#' + controlLabelId)
                  .siblings('.swatch-attribute-selected-option')
                  .after('<div class="tooltip-info" aria-describedby="#' + tooltipId + '" data-tooltip-content="#' + tooltipId + '">More Info</div><div class="hidden tooltip-content"><div id="' + tooltipId + '">' + productOptionsTooltips[labelNormalized] + '</div></div>');
              }
            });
          } catch (e) {
            console.error("Error with tooltip logic:", e);
            // Do nothing
          }

          activateTooltipster();

          return parentReturnValue;
        }
      }
    );

    return jQuery['mage']['SwatchRenderer'];
  };
});
