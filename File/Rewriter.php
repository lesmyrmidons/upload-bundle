<?php
namespace Lms\UploadBundle\File;

use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Kévin ARBOUIN <lesmyrmidons@gmail.com>
 *
 */
class Rewriter
{
    /**
     *
     * @var RewriteRule
     */
    private $rewriteRule;

    /**
     * @param RewriteRule $rewriteRule
     */
    public function __construct(RewriteRule $rewriteRule)
    {
        $this->fileSystem = new Filesystem();
        $this->rewriteRule = $rewriteRule;
    }

    public function uploadFile()
    {
    }

    public function copyFile()
    {
    }

    /**
     * Generates a unique file name by keeping the original name and adding a unique key.
     *
     * @param string $filename
     *
     * @return string
     */
    public function formatFilename($filename)
    {
        $extension = $this->getExtension($filename);

        $baseName = substr($filename, 0, strlen($filename) - strlen($extension));
        $str = strtolower($this->removeAccents($baseName));
        $str = preg_replace('/ {1,}/', ' ', $str);
        $stripBaseName = preg_replace('/[^a-zA-Z0-9-]{1,}/', '-', trim($str));
        $stripBaseName = preg_replace('/[-]{1,}$/', '', trim($stripBaseName));

        $finalFilename = uniqid($stripBaseName . '-') . $extension;

        return $finalFilename;
    }

    /**
     * Returns the string $str every accent delete.
     *
     * @param string $str     String to format
     * @param string $charset Optional, indicate the charset you want. default is the charset UTF-8
     *
     * @return string
     */
    protected function removeAccents($str, $charset = 'utf-8')
    {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);
        $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
        $str = preg_replace('#&[^;]+;#', '', trim($str));

        return $str;
    }

    /**
     * Returns the file extension based on extensions permitted otherwise returns null
     *
     * @param string $fileName
     *
     * @return string|null
     */
    protected function getExtension($fileName)
    {
        return pathinfo($fileName, PATHINFO_EXTENSION);
    }
}
