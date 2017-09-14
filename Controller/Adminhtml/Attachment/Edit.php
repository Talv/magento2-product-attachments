<?php

namespace Talv\ProductAttachments\Controller\Adminhtml\Attachment;

class Edit extends \Talv\ProductAttachments\Controller\Adminhtml\Attachment
{
    /**
     * execute the action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
     */
     public function execute()
     {
         $id = $this->getRequest()->getParam('attachment_id');
         /** @var \Talv\ProductAttachments\Model\Attachment $attachment */
         $attachment = $this->initAttachment();
         /** @var \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page $resultPage */
         $resultPage = $this->resultPageFactory->create();
         if ($id) {
             $attachment->load($id);
             if (!$attachment->getId()) {
                 $this->messageManager->addError(__('This Attachment no longer exists.'));
                 $resultRedirect = $this->resultRedirectFactory->create();
                 $resultRedirect->setPath(
                     'talv_attachments/*/edit',
                     [
                         'attachment_id' => $attachment->getId(),
                         '_current' => true
                     ]
                 );
                 return $resultRedirect;
             }
         }
         $title = $attachment->getId() ? $attachment->getName() : __('New Attachment');
         $resultPage->getConfig()->getTitle()->prepend($title);
         $data = $this->backendSession->getData('talv_attachments_attachment_data', true);
         if (!empty($data)) {
             $attachment->setData($data);
         }
         return $resultPage;
     }
}
