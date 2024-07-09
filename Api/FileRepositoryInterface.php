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

namespace Mavenbird\ProductAttachment\Api;

interface FileRepositoryInterface
{
    /**
     * Save file.
     *
     * @param \Mavenbird\ProductAttachment\Api\Data\FileInterface $file
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return \Mavenbird\ProductAttachment\Api\Data\FileInterface
     */
    public function save(\Mavenbird\ProductAttachment\Api\Data\FileInterface $file);

    /**
     * Save file with relations.
     *
     * @param \Mavenbird\ProductAttachment\Api\Data\FileInterface $file
     * @param array $params
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return \Mavenbird\ProductAttachment\Api\Data\FileInterface
     */
    public function saveAll(\Mavenbird\ProductAttachment\Api\Data\FileInterface $file, $params = []);

    /**
     * Retrieve file.
     *
     * @param int $fileId
     * @return \Mavenbird\ProductAttachment\Api\Data\FileInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($fileId);

    /**
     * Retrieve file by Url Hash.
     *
     * @param string $hash
     * @return \Mavenbird\ProductAttachment\Api\Data\FileInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByHash($hash);

    /**
     * Delete file.
     *
     * @param \Mavenbird\ProductAttachment\Api\Data\FileInterface $file
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Mavenbird\ProductAttachment\Api\Data\FileInterface $file);

    /**
     * Delete file by ID.
     *
     * @param int $fileId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($fileId);
}
