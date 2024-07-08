<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Controller\Adminhtml\Icon;

use Mavenbird\ProductAttachment\Controller\Adminhtml\Icon;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\Icon\IconFactory;
use Mavenbird\ProductAttachment\Model\Icon\Repository;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;

class Edit extends Icon
{
    /**
     * @var IconFactory
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
     * @param IconFactory $iconFactory
     * @param Repository $repository
     * @param Registry $registry
     * @param Action\Context $context
     */
    public function __construct(
        IconFactory $iconFactory,
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
        $resultPage->setActiveMenu('Mavenbird_ProductAttachment::icon');

        if ($iconId = (int) $this->getRequest()->getParam(RegistryConstants::FORM_ICON_ID)) {
            try {
                $this->repository->getById($iconId);
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Icon'));
            } catch (\Magento\Framework\Exception\NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This icon no longer exists.'));

                return $this->_redirect('*/*/index');
            }
        } else {
            $resultPage->getConfig()->getTitle()->prepend(__('New Icon'));
        }

        return $resultPage;
    }
}
