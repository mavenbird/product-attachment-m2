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

namespace Mavenbird\ProductAttachment\Model\Filesystem;

class Directory
{
    public const AMFILE_DIRECTORY = 'mavenbird' . DIRECTORY_SEPARATOR . 'file' . DIRECTORY_SEPARATOR;

    public const ATTACHMENT = 'attachment';

    public const ICON = 'icon';

    public const TMP_DIRECTORY = 'tmp';

    public const IMPORT = 'import';

    public const IMPORT_FTP = 'ftp';

    public const DIRECTORY_CODES = [
        self::ATTACHMENT => self::AMFILE_DIRECTORY . 'attach',
        self::ICON => self::AMFILE_DIRECTORY . 'icon',
        self::TMP_DIRECTORY => self::AMFILE_DIRECTORY . 'tmp',
        self::IMPORT => self::AMFILE_DIRECTORY . 'import',
        self::IMPORT_FTP => self::AMFILE_DIRECTORY . 'import' . DIRECTORY_SEPARATOR . 'ftp'
    ];
}
