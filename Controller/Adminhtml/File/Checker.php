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
use Mavenbird\ProductAttachment\Model\Icon\GetIconForFile;
use Mavenbird\ProductAttachment\Model\Icon\ResourceModel\Icon;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Downloadable\Helper\Download as DownloadHelper;

class Checker extends File
{
    /**
     * @var DownloadHelper
     */
    private $downloadHelper;

    /**
     * @var Icon
     */
    private $iconResource;

    /**
     * @var GetIconForFile
     */
    private $getIconForFile;

    /**
     * Construct
     *
     * @param Action\Context $context
     * @param DownloadHelper $downloadHelper
     * @param Icon $iconResource
     * @param GetIconForFile $getIconForFile
     */
    public function __construct(
        Action\Context $context,
        DownloadHelper $downloadHelper,
        Icon $iconResource,
        GetIconForFile $getIconForFile
    ) {
        parent::__construct($context);
        $this->downloadHelper = $downloadHelper;
        $this->iconResource = $iconResource;
        $this->getIconForFile = $getIconForFile;
    }
    
    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        if ($url = $this->getRequest()->getParam('url')) {
            $this->downloadHelper->setResource($url, DownloadHelper::LINK_TYPE_URL);
            try {
                $fileName = $this->downloadHelper->getFilename();
                $file = [];
                /** @codingStandardsIgnoreStart */
                $file['filename'] = pathinfo($fileName, PATHINFO_FILENAME);
                $file['file_extension'] = pathinfo($fileName, PATHINFO_EXTENSION);
                /** @codingStandardsIgnoreEnd */
                $allowedExtensions = $this->iconResource->getAllowedExtensions();
                if (in_array($file['file_extension'], $allowedExtensions)) {
                    $file['previewUrl'] = $this->getIconForFile->byFileExtension($file['file_extension']);
                    $jsonData = [
                        'status' => 'success',
                        'message' => __('Success'),
                        'file' => $file
                    ];
                } else {
                    $jsonData = ['status' => 'error', 'message' => 'Disallowed Extension'];
                }
            } catch (\Exception $e) {
                $jsonData = ['status' => 'error', 'message' => $e->getMessage()];
            }
        } else {
            $jsonData = ['status' => 'error', 'message' => __('Empty Url')];
        }

        return $resultJson->setData($jsonData);
    }
}
