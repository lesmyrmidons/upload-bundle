<?php
namespace Lms\Bundle\UploadBundle\File;

use Symfony\Component\Filesystem\Filesystem;

/**
 * @author lesmyrmidons
 *
 */
class Rewriter
{
    /**
     *
     * @var Filesystem
     */
    private $fileSystem;

    /**
     *
     * @var RewriteRule
     */
    private $rewriteRule;

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
     * Returns the string $str every accent delete.
     *
     * Retourne la chaine $str une fois tous les accent supprimer.
     *
     * @author Kévin ARBOUIN <lesmyrmidons@gmail.com>
     *
     * @param    string $str     String to format
     * @param    string $charset Optional, indicate the charset you want. default is the charset UTF-8
     *
     * @return   string
     * @static
     */
    public static function removeAccents($str, $charset = 'utf-8')
    {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);
        $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
        // supprime les autres caractères
        $str = preg_replace('#&[^;]+;#', '', trim($str));

        return $str;
    }

    /**
     * Returns the file extension based on extensions permitted otherwise returns null
     *
     * @author Kévin ARBOUIN <lesmyrmidons@gmail.com>
     *
     * @param    string $fileName
     * @param    mixed  $ext
     *
     * @return    string|null
     * @static
     */
    public static function getExtension($fileName, $ext = '/\.[a-zA-Z-]{2,4}$/i')
    {
        if (is_array($ext)) {
            foreach ($ext as $value) {
                if (preg_match("/{$value}$/", $fileName)) {
                    return $value;
                }
            }
        } else {
            preg_match($ext, $fileName, $tab, PREG_OFFSET_CAPTURE);

            return $tab[0][0];
        }

        return null;
    }

    /**
     * Generates a unique file name by keeping the original name and adding a unique key.
     *
     * Génère un nom de fichier unique en gardant le nom d'origine et en ajoutant une clé unique.
     *
     * @param    string $filename
     *
     * @return    string
     * @author Kévin ARBOUIN <lesmyrmidons@gmail.com>
     * @static
     */
    public static function formatFilename($filename)
    {
        $fileExtension = static::getExtension($filename);

        $baseName = substr($filename, 0, strlen($filename) - strlen($fileExtension));
        $str = strtolower(static::removeAccents($baseName));
        $str = preg_replace('/ {1,}/', ' ', $str);
        $stripBaseName = preg_replace('/[^a-zA-Z0-9-]{1,}/', '-', trim($str));
        $stripBaseName = preg_replace('/[-]{1,}$/', '', trim($stripBaseName));

        $finalFilename = uniqid($stripBaseName . '-') . $fileExtension;

        return $finalFilename;
    }
}
