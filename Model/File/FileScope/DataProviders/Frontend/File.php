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

namespace Mavenbird\ProductAttachment\Model\File\FileScope\DataProviders\Frontend;

use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\ConfigProvider;
use Mavenbird\ProductAttachment\Model\File\FileScope\ResourceModel\FileStore;
use Mavenbird\ProductAttachment\Model\Icon\GetIconForFile;
use Mavenbird\ProductAttachment\Model\SourceOptions\OrderFilterType;
use Mavenbird\ProductAttachment\Model\SourceOptions\UrlType;
use Magento\Customer\Model\Session;
use Magento\Framework\Url;

class File implements \Mavenbird\ProductAttachment\Model\File\FileScope\DataProviders\FileScopeDataInterface
{
    /**
     * @var FileStore
     */
    private $fileStore;

    /**
     * @var GetIconForFile
     */
    private $getIconForFile;

    /**
     * @var Url
     */
    private $urlBuilder;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var ConfigProvider
     */
    private $configProvider;
    
    /**
     * Construct
     *
     * @param FileStore $fileStore
     * @param GetIconForFile $getIconForFile
     * @param Session $customerSession
     * @param Url $urlBuilder
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        FileStore $fileStore,
        GetIconForFile $getIconForFile,
        Session $customerSession,
        Url $urlBuilder,
        ConfigProvider $configProvider
    ) {
        $this->fileStore = $fileStore;
        $this->getIconForFile = $getIconForFile;
        $this->urlBuilder = $urlBuilder;
        $this->customerSession = $customerSession;
        $this->configProvider = $configProvider;
    }

    /**
     * @inheritdoc
     */
    public function execute($params)
    {
        /** @var FileInterface $file */
        $file = $params[RegistryConstants::FILE];
        $store = $params[RegistryConstants::STORE];
        $fileStoreData = [];
        if ($store) {
            $fileStoreData = $this->fileStore->getByStoreId($file->getFileId(), $store);
            foreach (RegistryConstants::USE_DEFAULT_FIELDS as $field) {
                $file->setData(
                    RegistryConstants::USE_DEFAULT_PREFIX . $field,
                    (!isset($fileStoreData[$field]) || $fileStoreData[$field] === null)
                );
            }
        }

        $defaultFileStoreData = $this->fileStore->getByStoreId($file->getFileId(), 0);
        foreach (RegistryConstants::USE_DEFAULT_FIELDS as $field) {
            if (isset($fileStoreData[$field])) {
                if ($fileStoreData[$field] === null) {
                    $file->setData($field, $defaultFileStoreData[$field]);
                } else {
                    $file->setData($field, $fileStoreData[$field]);
                }
            } else {
                $file->setData($field, $defaultFileStoreData[$field]);
            }
        }

        return $this->processFileParams($file, $params);
    }

    /**
     * ProcessFileParams
     *
     * @param FileInterface $file
     * @param [type] $params
     * @return void
     */
    public function processFileParams(FileInterface $file, $params)
    {
        if (!$file->isVisible()) {
            return false;
        }

        if (isset($params[RegistryConstants::INCLUDE_FILTER])) {
            switch ($params[RegistryConstants::INCLUDE_FILTER]) {
                case OrderFilterType::INCLUDE_IN_ORDER_ONLY:
                    if (!$file->isIncludeInOrder()) {
                        return false;
                    }
                    break;
            }
        } else {
            if ($this->configProvider->excludeIncludeInOrderFiles() && $file->isIncludeInOrder()) {
                return false;
            }
        }
        if ($customerGroups = $file->getCustomerGroups()) {
            if (isset($params[RegistryConstants::CUSTOMER_GROUP])) {
                /** through api */
                if (!in_array($params[RegistryConstants::CUSTOMER_GROUP], $customerGroups)) {
                    return false;
                }
            } else {
                if (!in_array($this->customerSession->getCustomerGroupId(), $customerGroups)) {
                    return false;
                }
            }
        }

        $file->setIconUrl($this->getIconForFile->byFileExtension($file->getFileExtension()));

        $extraUrlParams = !empty($params[RegistryConstants::EXTRA_URL_PARAMS])
            ? $params[RegistryConstants::EXTRA_URL_PARAMS]
            : [];
        $file->setFrontendUrl(
            $this->urlBuilder->setScope((int)$params[RegistryConstants::STORE])->getUrl(
                'file/file/download',
                array_merge([
                    'file' => $this->configProvider->getUrlType() === UrlType::ID ? $file->getFileId()
                        : $file->getUrlHash(),
                    '_nosid' => true,
                ], $extraUrlParams)
            )
        );

        return $file;
    }
}
