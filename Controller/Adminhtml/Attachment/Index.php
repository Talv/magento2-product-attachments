<?php

namespace Talv\ProductAttachments\Controller\Adminhtml\Attachment;

class Index extends \Talv\ProductAttachments\Controller\Adminhtml\Attachment
{
    /**
     * execute the action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $this->setPageData();
        return $this->getResultPage();
    }

    /**
     * instantiate result page object
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
     */
	public function getResultPage()
    {
        if ($this->resultPage === null) {
            $this->resultPage = $this->resultPageFactory->create();
        }
        return $this->resultPage;
    }

    /**
     * set page data
     *
     * @return $this
     */
	public function setPageData()
    {
        $resultPage = $this->getResultPage();
        $resultPage->getConfig()->getTitle()->prepend((__('Załączniki')));
        return $this;
    }
}
