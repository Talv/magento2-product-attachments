<?php

namespace Talv\ProductAttachments\Controller\Adminhtml;

abstract class Attachment extends \Magento\Backend\App\Action
{
    /**
     * Page result factory
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
	public $resultPageFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    public $coreRegistry;

    /**
     * Result redirect factory
     *
     * @var \Magento\Backend\Model\View\Result\RedirectFactory
     */
    public $resultRedirectFactory;

    /**
     * Attachment Factory
     *
     * @var \Talv\ProductAttachments\Model\AttachmentFactory
     */
	public $attachmentFactory;

    /**
     * Backend session
     *
     * @var \Magento\Backend\Model\Session
     */
	public $backendSession;

    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Talv\ProductAttachments\Model\AttachmentFactory $attachmentFactory,
        \Magento\Backend\App\Action\Context $context
    ) {

        $this->coreRegistry = $coreRegistry;
        $this->resultRedirectFactory = $context->getRedirect();
        $this->resultPageFactory = $resultPageFactory;
        $this->attachmentFactory = $attachmentFactory;
        $this->backendSession = $context->getSession();
        parent::__construct($context);
    }

    /**
     * Init Attachment
     *
     * @return \Talv\ProductAttachments\Model\Attachment
     */
	public function initAttachment()
    {
        $attachmentId = (int) $this->getRequest()->getParam('attachment_id');
        /** @var \Talv\ProductAttachments\Model\Attachment $attachment */
        $attachment = $this->attachmentFactory->create();
        if ($attachmentId) {
            $attachment->load($attachmentId);
        }
        $this->coreRegistry->register('talv_attachments_attachment', $attachment);
        return $attachment;
    }
}
