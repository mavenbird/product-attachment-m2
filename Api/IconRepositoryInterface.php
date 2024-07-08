<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Api;

interface IconRepositoryInterface
{
    /**
     * Save icon.
     *
     * @param \Mavenbird\ProductAttachment\Api\Data\IconInterface $icon
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return \Mavenbird\ProductAttachment\Api\Data\IconInterface
     */
    public function save(\Mavenbird\ProductAttachment\Api\Data\IconInterface $icon);

    /**
     * Retrieve icon.
     *
     * @param int $iconId
     * @return \Mavenbird\ProductAttachment\Api\Data\IconInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($iconId);

    /**
     * Retrieve icons matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Mavenbird\ProductAttachment\Api\Data\IconInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete attachment.
     *
     * @param \Mavenbird\ProductAttachment\Api\Data\IconInterface $icon
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Mavenbird\ProductAttachment\Api\Data\IconInterface $icon);

    /**
     * Delete icon by ID.
     *
     * @param int $iconId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($iconId);
}
