<?php
namespace Mavenbird\ProductAttachment\Plugin;

class AllowExtensions
{
    /**
     * Allow additional file types for upload
     *
     * @param \Magento\MediaStorage\Model\File\Validator\NotProtectedExtension $subject
     * @param array $result
     * @return array
     */
    public function afterGetProtectedFileExtensions($subject, $result)
    {
        // Remove the file types you want to allow from the default disallowed list
        $allowedExtensions = ['php', 'html', 'htm', 'xml'];
        
        // Filter out the disallowed extensions you want to allow
        return array_diff($result, $allowedExtensions);
    }
}
