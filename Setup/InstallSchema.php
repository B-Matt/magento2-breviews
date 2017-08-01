<?php

/**
 * Copyright © 2017 B-Matt. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Matej\bReviews\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /*
         * Create table 'review_likes'
         */
        $table = $installer->getConnection()->newTable($installer->getTable('review_likes'))
            ->addColumn(
                'like_id',
                Table::TYPE_BIGINT,
                20,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Review like ID'
            )
            ->addColumn(
                'review_id',
                Table::TYPE_BIGINT,
                20,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Review ID'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                5,
                [
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => '0'
                ],
                'Store ID'
            )
            ->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                10,
                [
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => null
                ],
                'Customer ID'
            )
            ->addColumn(
                'like_status',
                Table::TYPE_SMALLINT,
                1,
                [
                    'unsigned' => true,
                    'nullable' => false,
                    'default' => '1'
                ],
                '1 - Like, 2 - Dislike'
            )
            ->addIndex(
                $installer->getIdxName('review_likes', ['customer_id', 'review_id', 'store_id'], AdapterInterface::INDEX_TYPE_INDEX),
                ['customer_id', 'review_id', 'store_id'],
                ['type' => AdapterInterface::INDEX_TYPE_INDEX]
            )
            ->addForeignKey(
                $installer->getFkName('review_likes', 'customer_id', 'customer_entity','entity_id'),
                'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id',
                Table::ACTION_SET_NULL
            )
            ->addForeignKey(
                $installer->getFkName('review_likes', 'review_id', 'review','review_id'),
                'review_id',
                $installer->getTable('review'),
                'review_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('review_likes', 'store_id', 'store','store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                Table::ACTION_SET_NULL
            )
            ->setOption('type', 'InnoDB')
            ->setOption('charset', 'utf8')
            ->setComment('Review Comments Table');
        $installer->getConnection()->createTable($table);

        /*
         * Create table 'review_comments'
         */
        $table = $installer->getConnection()->newTable($installer->getTable('review_comments'))
            ->addColumn(
                'comment_id',
                Table::TYPE_BIGINT,
                20,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Comment ID'
            )
            ->addColumn(
                'review_id',
                Table::TYPE_BIGINT,
                20,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Review ID'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                5,
                [
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => '0'
                ],
                'Store ID'
            )
            ->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                10,
                [
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => null
                ],
                'Customer ID'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                1,
                [
                    'unsigned' => false,
                    'nullable' => false,
                    'default' => Table::TIMESTAMP_INIT
                ],
                'Comment creation timestamp'
            )
            ->addColumn(
                'text',
                Table::TYPE_TEXT,
                128,
                [
                    'unsigned' => false,
                    'nullable' => false,
                    'default' => 'none'
                ],
                'Comment text'
            )
            ->addColumn(
                'parent',
                Table::TYPE_INTEGER,
                '',
                [
                    'unsigned' => true,
                    'nullable' => true,
                ],
                'Comment parent (nesting)'
            )
            ->addIndex(
                $installer->getIdxName('review_comments', ['customer_id', 'review_id', 'store_id'], AdapterInterface::INDEX_TYPE_INDEX),
                ['customer_id', 'review_id', 'store_id'],
                ['type' => AdapterInterface::INDEX_TYPE_INDEX]
            )
            ->addForeignKey(
                $installer->getFkName('review_comments', 'customer_id', 'customer_entity','entity_id'),
                'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id',
                Table::ACTION_SET_NULL
            )
            ->addForeignKey(
                $installer->getFkName('review_comments', 'review_id', 'review','review_id'),
                'review_id',
                $installer->getTable('review'),
                'review_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('review_comments', 'store_id', 'store','store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                Table::ACTION_SET_NULL
            )
            ->setOption('type', 'InnoDB')
            ->setOption('charset', 'utf8')
            ->setComment('Review Comments Table');
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}