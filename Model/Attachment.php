<?php

namespace Talv\ProductAttachments\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * @method Attachment setName($name)
 * @method string getName()
 * @method Attachment setFilename($name)
 * @method string getFilename()
 * @method Attachment setFileType($name)
 * @method string getFileType()
 * @method Attachment setNumberOfDownloads($name)
 * @method int getNumberOfDownloads()
 * @method Attachment setCreatedAt($createdAt)
 * @method string getCreatedAt()
 * @method Attachment setUpdatedAt($updatedAt)
 * @method string getUpdatedAt()
 * @method Attachment setEnabled($enabled)
 * @method bool getEnabled()
 * @method Attachment setProductsRelation($products)
 * @method array getProductsRelation()
 **/
class Attachment extends AbstractModel implements IdentityInterface
{
    /**
     * cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'talv_attachments_attachment';

    /**
     * Cache tag
     *
     * @var string
     */
    protected $_cacheTag = 'talv_attachments_attachment';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'talv_attachments_attachment';

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $dateTime;

    /**
     * @var \Talv\ProductAttachments\Helper\Config
     */
    protected $config;

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Talv\ProductAttachments\Model\ResourceModel\Attachment');
    }

    /**
     * @inheritDoc
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Talv\ProductAttachments\Helper\Config $config,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $value = parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->objectManager = $objectManager;
        $this->dateTime = $dateTime;
        $this->config = $config;

        return $value;
    }

    public function getDefaultValues()
    {
        $values = [];
        $values['enabled'] = '1';
        return $values;
    }

    /**
     * @return array|mixed
     */
    public function getProductsPosition()
    {
        if (!$this->getId()) {
            return [];
        }
        $array = $this->getData('products');
        if ($array === null) {
            $array = $this->getResource()->getProductsPosition($this);
            $this->setData('products', $array);
        }

        return $array;
    }

    public function getAbsoluteFilename()
    {
        return $this->config->getAttachmentBaseDir() . '/' . $this->getFilename();
    }

    public function getFileSize()
    {
        $size = filesize($this->config->getAttachmentBaseDir() . '/' . $this->getFilename());
        $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        if ($size == 0) {
            return('n/a');
        } else {
            return (round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $sizes[$i]);
        }
    }

    public function getUrl()
    {
        return '/attachments/attachment/download/attachment_id/' . $this->getId();
    }

    public function getDirectUrl()
    {
        return $this->config->getAttachmentBaseUrl() . '/' . $this->getFilename();
    }

    public function getMimeType()
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        return finfo_file($finfo, $this->config->getAttachmentBaseDir() . '/' . $this->getFilename());
    }
}
