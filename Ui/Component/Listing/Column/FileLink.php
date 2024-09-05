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
 * @package    Mavenbird_ProductAttachment
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */ 

namespace Mavenbird\ProductAttachment\Ui\Component\Listing\Column;

use Magento\Catalog\Helper\Image;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Attachment thumbnail Image
 */
class FileLink extends \Magento\Ui\Component\Listing\Columns\Column
{
    protected const IMAGE_WIDTH = '70%'; // Thumbnail Image Width
    protected const IMAGE_HEIGHT = '60'; // Thumbnail Image Height
    protected const IMAGE_STYLE = 'display: block;margin: auto;'; // Thumbnail Image Style
    
    /**
     * Current Store Manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $file;
    
    /**
     *
     * @param ContextInterface                           $context
     * @param UiComponentFactory                         $uiComponentFactory
     * @param \Magento\Framework\Filesystem              $filesystem
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Filesystem\Io\File      $file
     * @param array                                      $components
     * @param array                                      $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem\Io\File $file,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->_storeManager = $storeManager;
        $this->file = $file;
    }

    /**
     * Prepare Data source
     *
     * @param array $dataSource
     * @return $dataSource
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {

            foreach ($dataSource['data']['items'] as &$item) {

                if ($item[$this->getData('name')]) {
                    $ext = $this->file->getPathInfo($item[$this->getData('name')], PATHINFO_EXTENSION);
                    $file = $ext['basename'];

                    if ($item[$this->getData('name')] !=='') {
                        $item[$this->getData('name')] = "<a href='".$this->_storeManager->getStore()
                                   ->getBaseUrl(
                                       \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                                   ).$item[$this->getData('name')]."' target='_blank'>".$file."</a>";
                    }
                }
                
            }
        }
        return $dataSource;
    }
}
