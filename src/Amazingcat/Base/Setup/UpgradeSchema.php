<?php

namespace Amazingcat\Base\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Class UpgradeSchema
 * @package Amazingcat\Base\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Upgrades DB schema for a module
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     * @throws \Zend_Db_Exception
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.12') < 0) {

            /**
             * Create table 'amazingcat_base_versions'
             */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('amazingcat_base_versions')
            )->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Version Id'
            )->addColumn(
                'module',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Module Name'
            )->addColumn(
                'version',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Module Version'
            )->addColumn(
                'release_notes',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Release notes'
            );

            $table->setComment(
                'Lastest module\'s versions'
            );

            $installer->getConnection()->createTable($table);

            /**
             * Create table 'amazingcat_base_info'
             */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('amazingcat_base_info')
            )->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'reference_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Reference Id'
            )->addColumn(
                'text',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Text'
            )->addColumn(
                'date',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                null,
                ['nullable' => false],
                'Date'
            );

            $table->setComment(
                'Amazingcat info updates'
            );

            $installer->getConnection()->createTable($table);

        }

        if (version_compare($context->getVersion(), '1.0.13') < 0) {
            $setup->getConnection()->changeColumn(
                $setup->getTable('amazingcat_base_versions'),
                'release_notes',
                'release_notes',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => null
                ]
            );
        }

        if (version_compare($context->getVersion(), '1.0.14') < 0) {
            $connection = $setup->getConnection();
            $connection->dropTable($connection->getTableName('amazingcat_base_info'));
        }

        $setup->endSetup();
    }
}
