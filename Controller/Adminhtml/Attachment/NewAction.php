<?php

namespace Talv\ProductAttachments\Controller\Adminhtml\Attachment;

class NewAction extends \Talv\ProductAttachments\Controller\Adminhtml\Attachment
{
    /**
     * Redirect result factory
     *
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
	public $resultForwardFactory;

    /**
     * @inheritDoc
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Talv\ProductAttachments\Model\AttachmentFactory $attachmentFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($coreRegistry, $resultPageFactory, $attachmentFactory, $context);
        $this->resultForwardFactory = $resultForwardFactory;
    }

    /**
     * forward to edit
     *
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        $resultForward = $this->resultForwardFactory->create();
        $resultForward->forward('edit');
        return $resultForward;
    }
}
