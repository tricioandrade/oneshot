<?php

namespace OneShot\Builder\Traits;

use Illuminate\Support\Facades\File;

trait EssentialsTrait
{
    /**
     * Add file name suffix
     *
     * @param string $fileName
     * @param string $suffix
     * @return string
     */
    public function addFileNameSuffix(string $fileName, string $suffix): string
    {
        if (!preg_match( "/" . $suffix . "$/", $fileName))
            $fileName = $fileName.$suffix;

        return $fileName;
    }

    /**
     * Create dir or throw an error
     *
     * @param string $dir
     * @return true
     */
    public function createDir(string $dir): true
    {
        if (!File::exists($dir)) {
            if (!File::makeDirectory($dir, 755, true))
                throw new \Error('Cant create dir: '. $dir);
        }

        return true;
    }

    /**
     * Replace given content with that new one
     *
     * @param array|string $target
     * @param array|string $newContent
     * @param string $content
     * @return string
     */
    public function replaceContent(array|string $target, array|string $newContent, string $content): string
    {
        return str_replace($target, $newContent, $content);
    }

    /**
     * Store content to given file
     *
     * @param string $content
     * @param string $fileName
     * @return void
     */
    public function storeContent(string $fileName, string $content): mixed
    {
        return File::put($fileName, $content);
    }
}
