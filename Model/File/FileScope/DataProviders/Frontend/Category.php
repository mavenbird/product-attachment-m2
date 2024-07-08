<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
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
