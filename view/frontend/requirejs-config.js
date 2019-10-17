var config = {
    config: {
        mixins: {
            'Magento_Swatches/js/swatch-renderer': {
                'Kraken_ProductOptionsTooltips/js/model/swatch-renderer-mixin': true
            }
        }
    },
    map: {
        '*': {
            tooltipster: 'Kraken_ProductOptionsTooltips/js/tooltipster/tooltipster.bundle',
            activateTooltipster: 'Kraken_ProductOptionsTooltips/js/model/activate-tooltips',
        }
    }
};
