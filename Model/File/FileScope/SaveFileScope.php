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

class SaveFileScope implements SaveFileScopeInterface
{
    /**
     * @var SaveProcessors\FileScopeSaveProcessorInterface[]
     */
    private $saveProcessors;
    
    /**
     * Construct
     *
     * @param [type] $saveProcessors
     */
    public function __construct(
        $saveProcessors
    ) {
        $this->saveProcessors = $saveProcessors;
    }

    /**
     * @inheritdoc
     */
    public function execute($params, $saveProcessorName)
    {
        if (!isset($this->saveProcessors[$saveProcessorName])) {
            throw new \Mavenbird\ProductAttachment\Exceptions\NoSuchSaveProcessorException();
        }

        return $this->saveProcessors[$saveProcessorName]->execute($params);
    }
}
