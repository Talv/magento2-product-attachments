<?php

namespace Talv\ProductAttachments\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $table = $installer->getConnection()
            ->newTable($installer->getTable('talv_attachments_attachment'))
            ->addColumn(
                'attachment_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Attachment ID'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Attachment name'
            )
            ->addColumn(
                'number_of_downloads',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true, 'default' => 0],
                'Number of downloads'
            )
            ->addColumn(
                'filename',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Filename'
            )
            ->addColumn(
                'file_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                20,
                [],
                'File type'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Created At'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Updated At'
            )
            ->addColumn(
                'enabled',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                1,
                [],
                'Enabled'
            )
            ->setComment('Attachment Product Table')
        ;
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('talv_attachments_attachment_product'))
            ->addColumn(
                'attachment_product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Attachment ID'
            )
            ->addColumn(
                'attachment_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Product ID'
            )
            ->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Product ID'
            )
            ->addColumn(
                'position',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => false, 'nullable' => false, 'default' => '0'],
                'Position sort'
            )
            ->addForeignKey(
                $installer->getFkName('talv_attachments_product', 'product_id', 'catalog_product_entity', 'entity_id'),
                'product_id',
                $installer->getTable('catalog_product_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('talv_attachments_product', 'attachment_id', 'talv_attachments_attachment', 'attachment_id'),
                'attachment_id',
                $installer->getTable('talv_attachments_attachment'),
                'attachment_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addIndex(
                $installer->getIdxName('talv_attachments_product', ['product_id']), ['product_id']
            )
            ->addIndex(
                $installer->getIdxName('talv_attachments_product', ['attachment_id']), ['attachment_id']
            )
            ->addIndex(
                $installer->getIdxName(
                    'talv_attachments_product',
                    [
                        'product_id',
                        'attachment_id'
                    ],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                [
                    'product_id',
                    'attachment_id'
                ],
                [
                    'type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ]
            )
            ->setComment('Attachment Product Link Table')
        ;
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
