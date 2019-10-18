<?php

namespace Kraken\ProductOptionsTooltips\Plugin;

class ProductSwatchPlugin
{
    /**
     * Override setTemplate because the path to the template is hardcoded by Magento
     * @param \Magento\Swatches\Block\Product\Renderer\Configurable $subject
     * @param $template
     * @return array
     */
    public function beforeSetTemplate(
        \Magento\Swatches\Block\Product\Renderer\Configurable $subject,
        $template
    ) {

        if ($template == \Magento\Swatches\Block\Product\Renderer\Configurable::CONFIGURABLE_RENDERER_TEMPLATE) {
            $template = 'Kraken_ProductOptionsTooltips::product/view/type/options/configurable.phtml';
        }

        if ($template == \Magento\Swatches\Block\Product\Renderer\Configurable::SWATCH_RENDERER_TEMPLATE) {
            $template = 'Kraken_ProductOptionsTooltips::product/view/renderer.phtml';
        }

        return [$template];
    }
}