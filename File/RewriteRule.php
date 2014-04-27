<?php
namespace Lms\Bundle\UploadBundle\File;
/**
 * @author lesmyrmidons <lesmyrmidons@gmail.com>
 *
 */
class RewriteRule
{
    /**
     *
     * @var string
     */
    private $pathBase;

    /**
     *
     * @var integer
     */
    private $depth;

    public function __construct($baseDir, $depth = 3)
    {
        $this->pathBase = $baseDir;
        $this->depth = $depth;
    }

    /**
     *
     * @param integer $depth
     */
    public function setDepth($depth) {
        $this->depth = $depth;
    }

    /**
     *
     * @param string $filename
     * @param string $typeName
     * @return mixed
     */
    public function getBrowserPath($filename, $typeName = null)
    {
        $path = $this->getHash($filename, '/');
        $path = empty($typeName) ? $path : $typeName . '/' . $path;
        $path = $this->pathBase . '/' . $path;

        return str_replace(pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_DIRNAME), '', realpath($path));
    }

    /**
     *
     * @param string $filename
     * @param string $typeName
     * @return string
     */
    public function getPath($filename, $typeName = null)
    {
        $path = $this->getHash($filename);
        $path = empty($typeName) ? $path : $typeName . DIRECTORY_SEPARATOR . $path;

        return realpath($this->pathBase . DIRECTORY_SEPARATOR . $path);
    }

    /**
     *
     * @param string $filename
     * @param string $typeName
     * @return string
     */
    public function getTmpPath($filename, $typeName = null)
    {
        $path = $this->getHash($filename);
        $path = empty($typeName) ? $path : $typeName . DIRECTORY_SEPARATOR . $path;

        return $this->pathBase . DIRECTORY_SEPARATOR . $path;
    }

    /**
     *
     * @param string $file the name of the file to get Hash
     * @param string $ds
     *
     * @return string the hash
     */
    private function getHash($file, $ds = DIRECTORY_SEPARATOR)
    {
        $fileName = pathinfo($file, PATHINFO_FILENAME);

        if (0 === $this->depth) {
            return $fileName;
        }

        if (preg_match('/^(.+)([A-Za-z0-9]{1,' . $this->depth . '})$/xU', $fileName, $matches)) {
            $endFilename = $matches[2];
            if (strlen($endFilename) < $this->depth) {
                $startFilename = $matches[1];
                $endFilename = str_pad($endFilename, 3, "0", STR_PAD_LEFT);
                $fileName = $startFilename . $endFilename;
            }
            $hash = join($ds, array_slice(array_reverse(str_split($fileName)), 0, 3));

            return $hash;
        }

        return '';
    }

}
