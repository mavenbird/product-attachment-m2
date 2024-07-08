<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\Icon\DataProvider;

use Mavenbird\ProductAttachment\Api\Data\IconInterface;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\Filesystem\Directory;
use Mavenbird\ProductAttachment\Model\Icon\Repository;
use Mavenbird\ProductAttachment\Model\Icon\ResourceModel\CollectionFactory;
use Mavenbird\ProductAttachment\Model\Filesystem\UrlResolver;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Filesystem;

/**
 * Class DataProvider
 */
class Form extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var UrlResolver
     */
    private $iconUrlResolver;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var Filesystem\Directory\WriteInterface
     */
    private $mediaDirectory;

    /**
     * Construct
     *
     * @param CollectionFactory $iconCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param Repository $repository
     * @param UrlResolver $iconUrlResolver
     * @param Filesystem $filesystem
     * @param [type] $name
     * @param [type] $primaryFieldName
     * @param [type] $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        CollectionFactory $iconCollectionFactory,
        DataPersistorInterface $dataPersistor,
        Repository $repository,
        UrlResolver $iconUrlResolver,
        Filesystem $filesystem,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $iconCollectionFactory->create();
        $this->iconUrlResolver = $iconUrlResolver;
        $this->repository = $repository;
        $this->dataPersistor = $dataPersistor;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    /**
     * GetData
     *
     * @return void
     */
    public function getData()
    {
        $data = parent::getData();
        if ($data['totalRecords'] > 0) {
            $icon = $this->repository->getById($data['items'][0][IconInterface::ICON_ID]);
            $iconData = $icon->getData();
            if ($icon->getImage()) {
                $iconData[RegistryConstants::ICON_FILE_KEY] = [
                    [
                        'name' => $icon->getImage(),
                        'url' => $this->iconUrlResolver->getIconUrlByName($icon->getImage()),
                        'type' => 'image',
                        'size' => $this->getIconSize($icon->getImage())
                    ]
                ];
            }
            if ($extensions = $icon->getExtension()) {
                $extensionId = 0;
                $iconData['extensions'] = [];
                foreach ($extensions as $extension) {
                    $iconData['extensions'][] = ['id' => ++$extensionId, IconInterface::EXTENSION => $extension];
                }
            }
            $data[$icon->getIconId()] = $iconData;
        }

        if ($savedData = $this->dataPersistor->get(RegistryConstants::ICON_DATA)) {
            $savedIconId = isset($savedData[IconInterface::ICON_ID]) ? $savedData[IconInterface::ICON_ID] : null;
            if (isset($data[$savedIconId])) {
                $data[$savedIconId] = array_merge($data[$savedIconId], $savedData);
            } else {
                $data[$savedIconId] = $savedData;
            }
            $this->dataPersistor->clear(RegistryConstants::ICON_DATA);
        }

        return $data;
    }

    /**
     * GetIconSize
     *
     * @param [type] $filename
     * @return void
     */
    public function getIconSize($filename)
    {
        $stat = $this->mediaDirectory->stat(
            Directory::DIRECTORY_CODES[Directory::ICON] . DIRECTORY_SEPARATOR . $filename
        );
        if ($stat) {
            return $stat['size'];
        } else {
            return 0;
        }
    }
}
