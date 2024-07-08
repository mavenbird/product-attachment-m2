<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Controller\Adminhtml\Icon;

use Mavenbird\ProductAttachment\Api\Data\IconInterface;

class MassDelete extends \Mavenbird\ProductAttachment\Controller\Adminhtml\AbstractIconMassAction
{
    /**
     * ItemAction
     *
     * @param IconInterface $icon
     * @return void
     */
    protected function itemAction(IconInterface $icon)
    {
        $this->repository->deleteById($icon->getIconId());
    }
    /**
     * GetErrorMessage
     *
     * @return void
     */
    protected function getErrorMessage()
    {
        return __('We can\'t delete item right now. Please review the log and try again.');
    }
    /**
     * GetSuccessMessage
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
