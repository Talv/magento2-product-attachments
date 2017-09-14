<?php

namespace Talv\ProductAttachments\Controller\Adminhtml\Attachment;

class Products extends \Talv\ProductAttachments\Controller\Adminhtml\Attachment
{
    /**
     * Result layout factory
     *
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
	public $resultLayoutFactory;

    /**
     * @inheritDoc
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Talv\ProductAttachments\Model\AttachmentFactory $attachmentFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($coreRegistry, $resultPageFactory, $attachmentFactory, $context);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * execute the action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $this->initAttachment();
        $resultLayout = $this->resultLayoutFactory->create();
        /** @var \Talv\ProductAttachments\Block\Adminhtml\Attachment\Edit\Tab\Product $productBlock */
        $productBlock = $resultLayout->getLayout()->getBlock('attachment.edit.tab.product');
        if ($productBlock) {
            $productBlock->setAttachmentProducts($this->getRequest()->getPost('attachment_products', null));
        }
        return $resultLayout;
    }
}
