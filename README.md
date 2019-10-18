# Kraken ProductOptionsTooltips Extension

## Description

Add tooltips to configurable products and custom options.

Partially inspired by [Spaggel_Tooltip](https://github.com/Spaggel/Tooltip) (I only discovered this extension after I had mostly built this extension).

Currently supports configurable attributes (both dropdown and swatch). TODO: Add support for custom options (see XCH codebase).

## Usage Instructions

**User Stories & Acceptance Criteria**
<ol class="ak-ol">
    <li>
        <p>
            As an admin, I’d like to be able to add tooltip information to each product option so users can learn more about each option.
        </p>
        <ol class="ak-ol">
            <li>
                <p>
                    Tooltip data will show on click (not hover)
                </p>
            </li>
            <li>
                <p>
                    CMS Static Blocks will be used to power these tooltips. The Static Block IDs will be looked up using the following fallback logic:<br>
                    product_option_tooltip_&lt;product-sku&gt;_&lt;label&gt;<br>
                    product_option_tooltip_&lt;label&gt;
                </p>
            </li>
            <li>
                <p>
                    The <span class="code">product-sku</span> and <span class="code">label</span> values will be converted using the following logic:<br>
                    every character except a-z and 0-9 will be converted to a hyphen and lowercased.<br>
                    For example:<br>
                    Shirt Color<br>
                    shirt-color<br>
                    <br>
                    Someone’s cööl label description<br>
                    someone-s-c--l-label-description
                </p>
            </li>
        </ol>
    </li>
</ol>

## Installation Instructions

### Option 1 - Install extension using Composer (default approach)

```bash
composer config repositories.kraken/module-product-options-tooltips git https://github.com/krakencommerce/magento2-module-product-options-tooltips.git
composer require kraken/module-product-options-tooltips:dev-master
bin/magento module:enable --clear-static-content Kraken_ProductOptionsTooltips
bin/magento setup:upgrade
bin/magento cache:flush
```

### Option 2 - Install extension by copying files into project (only if the project requires it for some reason)

```bash
mkdir -p app/code/Kraken/ProductOptionsTooltips
git archive --format=tar --remote=git@github.com:krakencommerce/magento2-module-product-options-tooltips.git master | tar xf - -C app/code/Kraken/ProductOptionsTooltips/
bin/magento module:enable --clear-static-content Kraken_ProductOptionsTooltips
bin/magento setup:upgrade
bin/magento cache:flush
```

## Uninstallation Instructions

These instructions work regardless of how you installed the extension:

```bash
bin/magento module:disable --clear-static-content Kraken_ProductOptionsTooltips
rm -rf app/code/Kraken/ProductOptionsTooltips
composer remove kraken/module-product-options-tooltips
mr2 db:query 'DELETE FROM `setup_module` WHERE `module` = "Kraken_ProductOptionsTooltips"'
bin/magento cache:flush
```