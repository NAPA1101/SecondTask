<?php
namespace Perspective\SecondTask\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Perspective\SecondTask\Helper\Data;
use Magento\Catalog\Api\Data\TierPriceInterface;
use Magento\Catalog\Api\TierPriceStorageInterface;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var TierPriceStorageInterface
     */
    private $tierPrice;

    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param Data $helperData
     * @param TierPriceStorageInterface $tierPrice
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Data $helperData,
        TierPriceStorageInterface $tierPrice,
        array $data = [])

    {

        parent::__construct($context, $data);
        $this->_registry = $registry;
        $this->_helperData = $helperData;
        $this->tierPrice = $tierPrice;
    }

    /**
     * @return mixed|null
     */
    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    /**
     * @return string|void|null
     */
    public function getBasePrice()
    {
        if ($this->_helperData->getGeneralConfig('enable')) {
            if ($this->_helperData->getGeneralConfig('base_price')) {
                if ($this->getCurrentProduct()->getTypeId() == 'configurable') {
                    $basePrice = $this->getCurrentProduct()->getPriceInfo()->getPrice('regular_price')->getMinRegularAmount()->getValue();
                    return 'Base price: ' . $basePrice . '<br>';
                } elseif ($this->getCurrentProduct()->getTypeId() == 'simple') {
                    $basePrice = $this->getCurrentProduct()->getPriceInfo()->getPrice('regular_price')->getValue();
                    return 'Base price: ' . $basePrice . '<br>';
                } else {
                    return null;
                }
            }
        }
    }

    /**
     * @return string|void|null
     */
    public function getFinalPrice()
    {
        if ($this->_helperData->getGeneralConfig('enable')) {
            if ($this->_helperData->getGeneralConfig('final_price')) {
                if (($this->getCurrentProduct()->getTypeId() == 'configurable' || $this->getCurrentProduct()->getTypeId() == 'simple')) {
                    $finalPrice = $this->getCurrentProduct()->getPriceInfo()->getPrice('final_price')->getValue();
                    return 'Final price: ' . $finalPrice . '<br>';
                } else {
                    return null;
                }
            }
        }
    }

    /**
     * @return string|void|null
     */
    public function getSpecialPrice()
    {
        if ($this->_helperData->getGeneralConfig('enable')) {
            if ($this->_helperData->getGeneralConfig('special_price')) {
                if (($this->getCurrentProduct()->getTypeId() == 'configurable' || $this->getCurrentProduct()->getTypeId() == 'simple')) {
                    $specialPrice = $this->getCurrentProduct()->getPriceInfo()->getPrice('special_price')->getValue();
                    return 'Special Price: ' . $specialPrice . '<br>';
                } else {
                    return null;
                }
            }
        }
    }

    /**
     * @param array $sku
     * @return TierPriceInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getTierPriceArray(array $sku)
    {
        $result = [];
        $result = $this->tierPrice->get($sku);
        return $result;
    }

    /**
     * @return string|void|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getTierPrice()
    {
        if ($this->_helperData->getGeneralConfig('enable')) {
            if ($this->_helperData->getGeneralConfig('tier_price')) {
                if ($this->getCurrentProduct()->getTypeId() == 'configurable' || $this->getCurrentProduct()->getTypeId() == 'simple') {
                    $result = $this->getTierPriceArray([$this->getCurrentProduct()->getSku()]);
                    if (count($result)) {
                        foreach ($result as $item) {
                            $tierPrice = round($item['price'], 0);
                        }
                    }
                    return 'Tier Price: ' . $tierPrice . '<br>';
                } else {
                    return null;
                }
            }
        }
    }
    /**
     * @return string|void|null
     */
    public function getCatalogPrice()
    {
        if ($this->_helperData->getGeneralConfig('enable')) {
            if ($this->_helperData->getGeneralConfig('catalog_rule_price')) {
                if ($this->getCurrentProduct()->getTypeId() == 'configurable' || $this->getCurrentProduct()->getTypeId() == 'simple') {
                    $catalogRule = $this->getCurrentProduct()->getPriceInfo()->getPrice('catalog_rule_price')->getValue();
                    return 'Catalog Rule Price: ' . $catalogRule . '<br>';
                } else {
                    return null;
                }
            }
        }
    }
}

