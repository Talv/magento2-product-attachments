<?php

namespace Talv\ProductAttachments\Controller\Attachment;

class Download extends \Magento\Framework\App\Action\Action
{
    /**
     * Attachment Factory
     *
     * @var \Talv\ProductAttachments\Model\AttachmentFactory
     */
    public $attachmentFactory;

    /**
     * @inheritDoc
     */
    public function __construct(
        \Talv\ProductAttachments\Model\AttachmentFactory $attachmentFactory,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->attachmentFactory = $attachmentFactory;
    }

    public function execute()
    {
        $attachmentId = (int) $this->getRequest()->getParam('attachment_id');

        if (!$attachmentId) {
            die();
        }

        $attachment = $this->attachmentFactory->create();
        /** @var \Talv\ProductAttachments\Model\Attachment $attachment */
        $attachment->load($attachmentId);

        if (!$attachment->getId()) {
            die();
        }

        $attachment->setNumberOfDownloads($attachment->getNumberOfDownloads() + 1);
        $attachment->save();

        preg_match('/\/?([^\/]+)$/', $attachment->getFilename(), $matches);
        $name = $matches[1];

        $response = $this->getResponse();
        /** @var \Magento\Framework\App\Response\Http $response */
        // $response->setRedirect($attachment->getDirectUrl());
        $response->setHeader('Content-Description', 'File Transfer');
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $name);
        $response->setHeader('Content-Type', $attachment->getMimeType());
        $response->setHeader('Content-Length', filesize($attachment->getAbsoluteFilename()));
        // $response->setBody(readfile($attachment->getAbsoluteFilename()));
        $response->sendHeaders();
        flush();

        readfile($attachment->getAbsoluteFilename());

        die();
    }
}
