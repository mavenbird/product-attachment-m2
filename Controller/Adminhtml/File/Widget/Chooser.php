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

namespace Mavenbird\ProductAttachment\Controller\Adminhtml\File\Widget;

use Magento\Framework\Controller\ResultFactory;

class Chooser extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = 'Mavenbird_ProductAttachment::files_list';

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;
    
    /**
     * Construct
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\LayoutFactory $layoutFactory
    ) {
        parent::__construct($context);
        $this->layoutFactory = $layoutFactory;
    }

    /**
     * Chooser Source action
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Raw $resultRaw */
        $resultRaw = $this->resultFactory->create(ResultFactory::TYPE_RAW);

        $uniqId = $this->getRequest()->getParam('uniq_id');
        $layout = $this->layoutFactory->create();
        $filesGrid = $layout->createBlock(
            \Mavenbird\ProductAttachment\Block\Adminhtml\Widget\Grid::class,
            '',
            [
                'data' => [
                    'id' => $uniqId,
                    'use_massaction' => false
                ]
            ]
        );

        return $resultRaw->setContents($filesGrid->toHtml());
    }
}
