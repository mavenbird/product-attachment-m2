<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Controller\Adminhtml\File;

use Mavenbird\ProductAttachment\Api\Data\FileInterface;

class MassDelete extends \Mavenbird\ProductAttachment\Controller\Adminhtml\AbstractFileMassAction
{
    /**
     * Itemaction
     *
     * @param FileInterface $file
     * @return void
     */
    protected function itemAction(FileInterface $file)
    {
        $this->repository->deleteById($file->getFileId());
    }
    /**
     * Geterrormessage
     *
     * @return void
     */
    protected function getErrorMessage()
    {
        return __('We can\'t delete item right now. Please review the log and try again.');
    }
    /**
     * Getsuccessmassage
     *
     * @param integer $collectionSize
     * @return void
     */
    protected function getSuccessMessage($collectionSize = 0)
    {
        if ($collectionSize) {
            return __('A total of %1 record(s) have been deleted.', $collectionSize);
        }
        return __('No records have been deleted.');
    }
}
