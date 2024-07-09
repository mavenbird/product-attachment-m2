<?php
/**
 * Mavenbird Technologies Private Limited
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://mavenbird.com/Mavenbird-Module-License.txt
 *
 * =================================================================
 *
 * @category   Mavenbird
 * @package    Mavenbird_ProductAttechment
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */

namespace Mavenbird\ProductAttachment\Controller\Adminhtml\Import;

use Mavenbird\ProductAttachment\Controller\Adminhtml\Import;
use Mavenbird\ProductAttachment\Model\Import\Repository;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class ImportContinue extends Import
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * Construct
     *
     * @param Repository $repository
     * @param Action\Context $context
     */
    public function __construct(
        Repository $repository,
        Action\Context $context
    ) {
        parent::__construct($context);
        $this->repository = $repository;
    }
    
    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        if ($importId = $this->getRequest()->getParam('import_id')) {
            try {
                $import = $this->repository->getById($importId);
                $storeIds = $import->getData(\Mavenbird\ProductAttachment\Model\Import\Import::STORE_IDS);
                if ($storeIds === null || $storeIds === '') {
                    $this->_redirect(
                        $this->getUrl(
                            'file/import/store',
                            ['import_id' => $importId]
                        )
                    );
                    return;
                }
                $this->_redirect(
                    $this->getUrl(
                        'file/import/fileImport',
                        ['import_id' => $importId]
                    )
                );
                return;
            } catch (\Exception $e) {
                "An exception occurred: " . $e->getMessage();
            }
        }
        $this->_redirect('/*/*');
    }
}
