<?php

namespace Talv\ProductAttachments\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Attachment extends AbstractDb
{
    /**
     * Topic relation model
     *
     * @var string
     */
    public $attachmentProductTable;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('talv_attachments_attachment', 'attachment_id');
        $this->attachmentProductTable = $this->getTable('talv_attachments_attachment_product');
    }

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->dateTime = $dateTime;
    }

    /**
     * before save callback
     *
     * @param \Magento\Framework\Model\AbstractModel|\Mageplaza\Blog\Model\Post $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        /** @var \Talv\ProductAttachments\Model\Attachment $object */
        $object->setUpdatedAt($this->dateTime->date());
        if ($object->isObjectNew()) {
            $object->setCreatedAt($this->dateTime->date());
        }
    }

    /**
     * after save callback
     *
     * @param \Magento\Framework\Model\AbstractModel|\Talv\ProductAttachments\Model\Attachment $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->saveProductRelation($object);

        return parent::_afterSave($object);
    }

    public function saveProductRelation(\Talv\ProductAttachments\Model\Attachment $attachment)
    {
        $productsNew = $attachment->getProductsRelation();
        if ($productsNew === null) {
            return;
        }

        $productsCurrent = [];
        foreach ($this->getProducts($attachment) as $prod) {
            $productsCurrent[$prod['product_id']] = $prod;
        }

        // delete
        $ay = array_diff_key($productsCurrent, $productsNew);
        $this->getConnection()->delete($this->attachmentProductTable, [
            'attachment_id = ?' => $attachment->getId(),
            'product_id IN (?)' => array_keys(array_diff_key($productsCurrent, $productsNew)),
        ]);

        // update
        foreach (array_keys(array_intersect_key($productsNew, $productsCurrent)) as $prodId) {
            $this->getConnection()->update($this->attachmentProductTable, [
                'attachment_id' => $attachment->getId(),
                'product_id' => $prodId,
                'position' => $productsNew[$prodId]['position'],
            ], [
                'attachment_product_id = ?' => $productsCurrent[$prodId]['attachment_product_id'],
            ]);
        }

        // insert
        foreach (array_keys(array_diff_key($productsNew, $productsCurrent)) as $prodId) {
            $this->getConnection()->insertMultiple($this->attachmentProductTable, [
                'attachment_id' => $attachment->getId(),
                'product_id' => $prodId,
                'position' => $productsNew[$prodId]['position'],
            ]);
        }
    }

    /**
     * @param \Talv\ProductAttachments\Model\Attachment $attachment
     * @return array
     */
    public function getProductsPosition(\Talv\ProductAttachments\Model\Attachment $attachment)
    {
        $select = $this->getConnection()
            ->select()
            ->from(
                $this->attachmentProductTable,
                ['product_id', 'position']
            )
            ->where(
                'attachment_id = :attachment_id'
            )
        ;

        return $this->getConnection()->fetchPairs($select, ['attachment_id' => (int) $attachment->getId()]);
    }

    /**
     * @param \Talv\ProductAttachments\Model\Attachment $attachment
     * @return array
     */
    public function getProductsIds(\Talv\ProductAttachments\Model\Attachment $attachment)
    {
        $select = $this->getConnection()
            ->select()
            ->from(
                $this->attachmentProductTable,
                'product_id'
            )
            ->where(
                'attachment_id = :attachment_id'
            )
        ;

        return $this->getConnection()->fetchCol($select, ['attachment_id' => (int) $attachment->getId()]);
    }

    /**
     * @param \Talv\ProductAttachments\Model\Attachment $attachment
     * @return array
     */
    public function getProducts(\Talv\ProductAttachments\Model\Attachment $attachment)
    {
        $select = $this->getConnection()
            ->select()
            ->from(
                $this->attachmentProductTable
            )
            ->where(
                'attachment_id = :attachment_id'
            )
        ;

        return $this->getConnection()->fetchAll($select, ['attachment_id' => (int) $attachment->getId()]);
    }
}
