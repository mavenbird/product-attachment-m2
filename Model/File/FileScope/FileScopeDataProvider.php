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

namespace Mavenbird\ProductAttachment\Model\File\FileScope;

class FileScopeDataProvider implements FileScopeDataProviderInterface
{
    /**
     * @var DataProviders\FileScopeDataInterface[]
     */
    private $dataProviders;
    
    /**
     * Construct
     *
     * @param [type] $dataProviders
     */
    public function __construct(
        $dataProviders
    ) {
        $this->dataProviders = $dataProviders;
    }

    /**
     * @inheritdoc
     */
    public function execute($params, $dataProviderName)
    {
        if (!isset($this->dataProviders[$dataProviderName])) {
            throw new \Mavenbird\ProductAttachment\Exceptions\NoSuchDataProviderException();
        }

        return $this->dataProviders[$dataProviderName]->execute($params);
    }
}
