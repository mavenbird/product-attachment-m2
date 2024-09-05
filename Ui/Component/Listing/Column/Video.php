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

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
 
class Video extends \Magento\Ui\Component\Listing\Columns\Column
{
    public const NAME = 'thumbnail';
 
    public const ALT_FIELD = 'name';

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $file;

    /**
     *
     * @param ContextInterface                           $context
     * @param UiComponentFactory                         $uiComponentFactory
     * @param \Mavenbird\ProductAttachment\Model\Video    $videoHelper
     * @param \Magento\Framework\UrlInterface            $urlBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Filesystem\Io\File      $file
     * @param array                                      $components
     * @param array                                      $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Mavenbird\ProductAttachment\Model\Video $videoHelper,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem\Io\File $file,
        array $components = [],
        array $data = []
    ) {
        
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->videoHelper = $videoHelper;
        $this->_storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        $this->file = $file;
    }
 
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {

        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                $filename = $item['video'];
                if ($filename) {
                    
                    $ext = $this->file->getPathInfo($filename, PATHINFO_EXTENSION);
                    $file = $ext['basename'];

                    $item[$fieldName] = "<a href='".$this->_storeManager->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ).$filename."'  target='_blank'>".$file."</a>";
                } else {
                    $fieldName = '';
                }
            }
        }
        return $dataSource;
    }

    /**
     * Get Alt
     *
     * @param array $row
     * @return null|string
     */
    protected function getAlt($row)
    {
        $altField = $this->getData('config/altField') ?: self::ALT_FIELD;
        return isset($row[$altField]) ? $row[$altField] : null;
    }
}
