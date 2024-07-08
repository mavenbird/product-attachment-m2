<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
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
