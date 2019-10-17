<?php


namespace Kraken\ProductOptionsTooltips\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{
    /**
     * @var array Cache of tooltips
     */
    protected $_productTooltipOptionsCache = [];

    /**
     * @var \Magento\Cms\Api\BlockRepositoryInterface
     */
    protected $blockRepository;

    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $cmsFilterProvider;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Data constructor.
     * @param Context $context
     * @param \Magento\Cms\Model\Template\FilterProvider $cmsFilterProvider
     * @param \Magento\Cms\Api\BlockRepositoryInterface $blockRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        \Magento\Cms\Model\Template\FilterProvider $cmsFilterProvider,
        \Magento\Cms\Api\BlockRepositoryInterface $blockRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->cmsFilterProvider = $cmsFilterProvider;
        $this->blockRepository = $blockRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $storeManager;
    }

    /**
     * Get all CMS content for product options
     *
     * @param array $optionNames
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getOptionTooltips(array $optionNames, $sku) {
        $blockIds = [];
        foreach ($optionNames as $optionName) {
            $blockIds[] = $this->normalizeOptionName($optionName, $sku);
            $blockIds[] = $this->normalizeOptionName($optionName);
        }

        $blocks = $this->getCmsBlocks($blockIds);

        $blocksByIdentifier = [];
        /** @var \Magento\Cms\Api\Data\BlockInterface $block */
        foreach ($blocks->getItems() as $block) {
            $blockIdentifier = $block->getIdentifier();
            $blockContent = $this->getFilteredBlockContent($block->getContent());
            $blocksByIdentifier[$blockIdentifier] = $blockContent;
            $this->_productTooltipOptionsCache[$blockIdentifier] = $blockContent;
        }

        return $blocksByIdentifier;
    }

    /**
     * Get CMS content for this product option
     *
     * @param $optionName
     * @return mixed
     */
    public function getOptionTooltip($optionName, $sku) {
        $blockIdentifierWithSku = $this->normalizeOptionName($optionName, $sku);
        $blockIdentifier = $this->normalizeOptionName($optionName);

        if (isset($this->_productTooltipOptionsCache[$blockIdentifierWithSku])) {
            return $this->_productTooltipOptionsCache[$blockIdentifierWithSku];
        }
        if (isset($this->_productTooltipOptionsCache[$blockIdentifier])) {
            return $this->_productTooltipOptionsCache[$blockIdentifier];
        }

        return false;
    }

    /**
     * Get block identifier
     *
     * @param $optionName
     * @param null $sku
     * @return string
     */
    protected function normalizeOptionName($optionName, $sku = null) {
        $parsedName = $this->normalizeString($optionName);
        $normalizedName = 'product_option_tooltip_';
        if ($sku) {
            $normalizedName .= $this->normalizeString($sku) . '_';
        }
        $normalizedName .= $parsedName;
        return $normalizedName;
    }

    /**
     * Return normalized string
     * 
     * @param $string
     * @return string
     */
    public function normalizeString($string) {
        return strtolower(mb_ereg_replace('[^\da-zA-Z]', '-', $string));
    }

    /**
     * Get the list of CMS blocks
     *
     * @param $ids
     * @return \Magento\Cms\Api\Data\BlockSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getCmsBlocks($ids)
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('identifier', $ids, 'in')->create();

        $blocks = $this->blockRepository->getList($searchCriteria);

        return $blocks;
    }

    /**
     * Get the content of a CMS block, filtered for CMS directives
     *
     * @param string $blockContent
     * @return string
     */
    protected function getFilteredBlockContent($blockContent)
    {
        $storeId = $this->storeManager->getStore()->getId();
        return $this->cmsFilterProvider->getBlockFilter()->setStoreId($storeId)->filter($blockContent);
    }
}
