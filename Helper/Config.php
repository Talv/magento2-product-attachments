<?php

namespace Talv\ProductAttachments\Helper;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Media sub folder
     *
     * @var string
     */
    protected $subDir = 'attachments';

    /**
     * URL builder
     *
     * @var \Magento\Framework\UrlInterface
     */
	protected $urlBuilder;

    /**
     * File system model
     *
     * @var \Magento\Framework\Filesystem
     */
	protected $filesystem;

    /**
     * @inheritDoc
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->urlBuilder = $urlBuilder;
        $this->filesystem = $filesystem;
    }

    /**
     * get base url
     *
     * @return string
     */
    public function getAttachmentBaseUrl()
    {
        return $this->urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $this->subDir;
    }
    /**
     * get base dir
     *
     * @return string
     */
    public function getAttachmentBaseDir()
    {
        return $this->filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath($this->subDir);
    }

    public function getAttachmentAllowedExtensions()
    {
        return ['aac', 'abw', 'arc', 'avi', 'azw', 'bin', 'bz', 'bz2', 'csh', 'css', 'csv', 'doc', 'docx', 'eot', 'epub', 'gif', 'htm', 'html', 'ico', 'ics', 'jar', 'jpeg', 'jpg', 'js', 'json', 'mid', 'midi', 'mpeg', 'mpkg', 'odp', 'ods', 'odt', 'oga', 'ogv', 'ogx', 'otf', 'png', 'pdf', 'ppt', 'rar', 'rtf', 'sh', 'svg', 'swf', 'tar', 'tif', 'tiff', 'ts', 'ttf', 'vsd', 'wav', 'weba', 'webm', 'webp', 'woff', 'woff2', 'xhtml', 'xls', 'xlsx', 'xml', 'xul', 'zip', '3gp', '3g2', '7z'];
    }
}
