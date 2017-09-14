<?php

namespace Talv\ProductAttachments\Controller\Adminhtml\Attachment;

class Delete Extends \Talv\ProductAttachments\Controller\Adminhtml\Attachment
{
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('talv_attachments/*/');

        $attachment = $this->initAttachment();
        if ($attachment) {
            $name = $attachment->getName();
            $attachment->delete();
            $this->messageManager->addSuccess(__('Attachment \'%1\' has been deleted.', $name));
        }

        return $resultRedirect;
    }
}
