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
