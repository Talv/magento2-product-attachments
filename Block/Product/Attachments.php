<?php

namespace Talv\ProductAttachments\Block\Product;

class Attachments extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'attachments.phtml';

    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * @var \Talv\ProductAttachments\Model\ResourceModel\Attachment\CollectionFactory
    */
    protected $attachmentCollectionFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Talv\ProductAttachments\Model\ResourceModel\Attachment\CollectionFactory $attachmentCollectionFactory
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->attachmentCollectionFactory = $attachmentCollectionFactory;

        parent::__construct($context);

        $this->setTabTitle();
    }

    /**
     * Set tab title
     *
     * @return void
     */
    public function setTabTitle()
    {
        $this->setTitle(__('Attachments'));
    }

    public function getAttachments()
    {
        $collection = $this->attachmentCollectionFactory->create();
        /** @var \Talv\ProductAttachments\Model\ResourceModel\Attachment\Collection $collection */
        $collection->getSelect()->joinLeft(
            ['related' => $collection->getTable('talv_attachments_attachment_product')],
            'related.attachment_id=main_table.attachment_id',
            ['position']
        )->where('related.product_id=' . $this->getProduct()->getId());
        $collection
            // ->addFieldToFilter('product_id', $this->getProduct()->getId())
            ->addOrder('position', 'ASC')
            ->addOrder('attachment_id', 'ASC')
        ;
        return $collection;
    }

    /**
     * Return current product instance
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->coreRegistry->registry('product');
    }
}
