<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\File\FileScope;

class SaveFileScope implements SaveFileScopeInterface
{
    /**
     * @var SaveProcessors\FileScopeSaveProcessorInterface[]
     */
    private $saveProcessors;
    /** */
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
