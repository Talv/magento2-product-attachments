<?php

namespace Talv\ProductAttachments\Block\Adminhtml\Attachment\Edit\Tab;

class Product extends \Magento\Backend\Block\Widget\Grid\Extended implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
	public $productCollectionFactory;

    /**
     * Registry
     *
     * @var \Magento\Framework\Registry
     */
	public $coreRegistry;

    /**
     * Product factory
     *
     * @var \Magento\Catalog\Model\ProductFactory
     */
	public $productFactory;

    /**
     * constructor
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        array $data = []
    ) {

        $this->productCollectionFactory = $productCollectionFactory;
        $this->coreRegistry           = $coreRegistry;
        $this->productFactory           = $productFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Set grid params
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('product_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getAttachment()->getId()) {
            $this->setDefaultFilter(['in_products' => 1]);
        }
    }

    /**
     * prepare the collection

     * @return $this
     */
    protected function _prepareCollection()
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('name');
        if ($this->getAttachment()->getId()) {
            $constraint = 'related.attachment_id=' . $this->getAttachment()->getId();
        } else {
            $constraint = 'related.attachment_id=0';
        }
        $collection->getSelect()->joinLeft(
            ['related' => $collection->getTable('talv_attachments_attachment_product')],
            'related.product_id=e.entity_id AND ' . $constraint,
            ['position']
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_products',
            [
                'header_css_class'  => 'a-center',
                'type'   => 'checkbox',
                'name'   => 'in_product',
                'values' => $this->_getSelectedProducts(),
                'align'  => 'center',
                'index'  => 'entity_id'
            ]
        );
        $this->addColumn(
            'product_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'entity_id',
                'type' => 'number',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'title',
            [
                'header' => __('Name'),
                'index' => 'name',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name'
            ]
        );

        $this->addColumn(
            'position',
            [
                'header' => __('Position'),
                'name'   => 'position',
                'width'  => 60,
                'type'   => 'number',
                'validate_class' => 'validate-number',
                'index' => 'position',
                'editable'  => true,
            ]
        );
        return $this;
    }

    /**
     * Retrieve selected Products

     * @return array
     */
    protected function _getSelectedProducts()
    {
        $products = $this->getAttachmentProducts();
        if (!is_array($products)) {
            $products = $this->getAttachment()->getProductsPosition();
            return array_keys($products);
        }
        return $products;
    }

    /**
     * Retrieve selected Products

     * @return array
     */
    public function getSelectedProducts()
    {
        $selected = $this->getAttachment()->getProductsPosition();
        if (!is_array($selected)) {
            $selected = [];
        } else {
            foreach ($selected as $key => $value) {
                $selected[$key] = ['position' => $value];
            }
        }
        return $selected;
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\DataObject $item
     * @return string
     */
    public function getRowUrl($item)
    {
        return '#';
    }

    /**
     * get grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/productsGrid',
            [
                'attachment_id' => $this->getAttachment()->getId()
            ]
        );
    }

    /**
     * @return \Talv\ProductAttachments\Model\Attachment
     */
    public function getAttachment()
    {
        return $this->coreRegistry->registry('talv_attachments_attachment');
    }

    /**
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_products') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }

            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return __('Products');
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getTabUrl()
    {
        return $this->getUrl('talv_attachments/attachment/products', ['_current' => true]);
    }

    /**
     * @return string
     */
    public function getTabClass()
    {
        return 'ajax only';
    }
}
