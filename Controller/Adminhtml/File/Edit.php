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

namespace Mavenbird\ProductAttachment\Controller\Adminhtml\File;

use Mavenbird\ProductAttachment\Controller\Adminhtml\File;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\File\FileFactory;
use Mavenbird\ProductAttachment\Model\File\Repository;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;

class Edit extends File
{
    /**
     * @var FileFactory
     */
    private $iconFactory;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var Registry
     */
    private $registry;
    
    /**
     * Construct
     *
     * @param FileFactory $iconFactory
     * @param Repository $repository
     * @param Registry $registry
     * @param Action\Context $context
     */
    public function __construct(
        FileFactory $iconFactory,
        Repository $repository,
        Registry $registry,
        Action\Context $context
    ) {
        parent::__construct($context);
        $this->iconFactory = $iconFactory;
        $this->repository = $repository;
        $this->registry = $registry;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        //TODO check ACL
        $resultPage->setActiveMenu('Mavenbird_ProductAttachment::files_list');

        if ($fileId = (int) $this->getRequest()->getParam(RegistryConstants::FORM_FILE_ID)) {
            try {
                $this->repository->getById($fileId);
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Attachment'));
                $resultPage->getLayout()->addBlock(
                    \Magento\Backend\Block\Store\Switcher::class,
                    'store_switcher',
                    'page.main.actions'
                );
            } catch (\Magento\Framework\Exception\NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This attachment no longer exists.'));

                return $this->_redirect('*/*/index');
            }
        } else {
            $resultPage->getConfig()->getTitle()->prepend(__('New Attachment'));
        }

        return $resultPage;
    }
}
