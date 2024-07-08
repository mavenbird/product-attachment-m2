<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Ui\Component\Listing\Columns\Column;

use Mavenbird\ProductAttachment\Api\Data\IconInterface;
use Mavenbird\ProductAttachment\Model\Filesystem\UrlResolver;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var UrlResolver
     */
    private $iconUrl;

    /**
     * Construct
     *
     * @param UrlResolver $iconUrl
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        UrlResolver $iconUrl,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->iconUrl = $iconUrl;
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
                if (!empty($item[IconInterface::IMAGE])) {
                    $item[$fieldName . '_src'] = $this->iconUrl->getIconUrlByName($item[IconInterface::IMAGE]);
                }
            }
        }

        return $dataSource;
    }
}
