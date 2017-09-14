<?php

namespace Talv\ProductAttachments\Controller\Adminhtml\Attachment;

class Save extends \Talv\ProductAttachments\Controller\Adminhtml\Attachment
{
    /**
     * Upload model factory
     *
     * @var \Magento\Framework\File\UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @var \Talv\ProductAttachments\Helper\Config
    */
    protected $config;

    /**
     * JS helper
     *
     * @var \Magento\Backend\Helper\Js
     */
	public $jsHelper;

    /**
     * @inheritDoc
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Talv\ProductAttachments\Model\AttachmentFactory $attachmentFactory,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\File\UploaderFactory $uploaderFactory,
        \Magento\Backend\Helper\Js $jsHelper,
        \Talv\ProductAttachments\Helper\Config $config
    ) {
        parent::__construct($coreRegistry, $resultPageFactory, $attachmentFactory, $context);
        $this->uploaderFactory = $uploaderFactory;
        $this->jsHelper = $jsHelper;
        $this->config = $config;
    }

    /**
     * execute the action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Framework\App\Request\Http $request */
        $request = $this->getRequest();
        $data = $request->getPost('attachment');
        $files = $request->getFiles('attachment');
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$data) {
            return $resultRedirect->setPath('talv_attachments/*/');
        }

        try {
            $attachment = $this->initAttachment();
            $attachment->setData($data);

            if (isset($files['file']) && $files['file']['name']) {
                $uploader = $this->uploaderFactory->create(['fileId' => $files['file']]);
                $uploader->setAllowedExtensions($this->config->getAttachmentAllowedExtensions());
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $uploader->setAllowCreateFolders(true);
                $filename = substr($uploader->save($this->config->getAttachmentBaseDir())['file'], 1);
                $attachment->setFilename($filename);
                $attachment->setFileType(strtolower($uploader->getFileExtension()));
            }

            $products = $request->getPost('products');
            if ($products !== null) {
                $attachment->setProductsRelation($this->jsHelper->decodeGridSerializedInput($products));
            }

            $attachment->save();

            $this->messageManager->addSuccess(__('Saved.'));

            if ($this->getRequest()->getParam('back')) {
                $resultRedirect->setPath(
                    'talv_attachments/*/edit',
                    [
                        'attachment_id'  => $attachment->getId(),
                        '_current' => true
                    ]
                );
            } else {
                $resultRedirect->setPath('talv_attachments/*/');
            }

            return $resultRedirect;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\RuntimeException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Sorry, something went wrong.'));
        }

        $resultRedirect->setPath(
            'talv_attachments/*/edit',
            [
                'attachment_id'  => $attachment->getId(),
                '_current' => true
            ]
        );

        return $resultRedirect;
    }
}
