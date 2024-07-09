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

use Mavenbird\ProductAttachment\Api\Data\FileScopeInterface;
use Mavenbird\ProductAttachment\Model\File\Repository;
use Mavenbird\ProductAttachment\Model\File\FileScope\DataProviders\Category as CategoryDataProvider;

class Category implements \Mavenbird\ProductAttachment\Model\File\FileScope\DataProviders\FileScopeDataInterface
{
    /**
     * @var CategoryDataProvider
     */
    private $categoryDataProvider;

    /**
     * @var File
     */
    private $fileDataProvider;

    /**
     * Repository
     *
     * @var [type]
     */
    private $fileRepository;
    
    /**
     * Construct
     *
     * @param CategoryDataProvider $categoryDataProvider
     * @param File $fileDataProvider
     * @param Repository $fileRepository
     */
    public function __construct(
        CategoryDataProvider $categoryDataProvider,
        File $fileDataProvider,
        Repository $fileRepository
    ) {
        $this->categoryDataProvider = $categoryDataProvider;
        $this->fileDataProvider = $fileDataProvider;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @inheritdoc
     */
    public function execute($params)
    {
        $result = [];
        if ($categoryFiles = $this->categoryDataProvider->execute($params)) {
            foreach ($categoryFiles as $categoryFile) {
                /** @var \Mavenbird\ProductAttachment\Model\File\File $file */
                $file = $this->fileRepository->getById($categoryFile[FileScopeInterface::FILE_ID]);
                $file->addData($categoryFile);
                if ($file = $this->fileDataProvider->processFileParams($file, $params)) {
                    $result[] = $file;
                }
            }
        }

        return $result;
    }
}
