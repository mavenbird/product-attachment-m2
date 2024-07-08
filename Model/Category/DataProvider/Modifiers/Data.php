<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\Category\DataProvider\Modifiers;

use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\File\FileScope\FileScopeDataProviderInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Registry;

class Data
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var FileScopeDataProviderInterface
     */
    private $fileScopeDataProvider;

    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * Construct
     *
     * @param Registry $registry
     * @param RequestInterface $request
     * @param FileScopeDataProviderInterface $fileScopeDataProvider
     */
    public function __construct(
        Registry $registry,
        RequestInterface $request,
        FileScopeDataProviderInterface $fileScopeDataProvider
    ) {
        $this->registry = $registry;
        $this->fileScopeDataProvider = $fileScopeDataProvider;
        $this->request = $request;
    }

    /**
     * Execute
     *
     * @param array $data
     * @return void
     */
    public function execute(array $data)
    {
        $category = $this->registry->registry('category');

        if ($category) {
            $data[$category->getId()]['attachments']['files'] = $this->fileScopeDataProvider->execute(
                [
                    RegistryConstants::STORE => $this->request->getParam('store', 0),
                    RegistryConstants::CATEGORY => $category->getId()
                ],
                'category'
            );
        }

        return $data;
    }
}
