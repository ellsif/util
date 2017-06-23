<?php
namespace ellsif\util;


class FileUtil
{

    /**
     * ローカルファイルへの文字列出力を行う。
     *
     * ## パラメータ
     * <dl>
     *   <dt>path</dt>
     *     <dd>出力先パスを指定します。<br>指定されたディレクトリが存在しない場合、ディレクトリを作成します。指定されたファイルが既に存在している場合は追記します。</dd>
     *   <dt>string</dt>
     *     <dd>ファイルに書き出す文字列を指定します。</dd>
     * </dl>
     *
     * ## エラー/例外
     * 書き込みに失敗した場合、Exceptionをthrowします。
     *
     * ## 例
     *     writeFile('/path/to/file.txt', 'Hello!');
     *
     */
    public static function writeFile($path, $string)
    {
        if (file_exists($path) && !is_writable($path)) {
            throw new \RuntimeException("${path} に書き込み権限がありません。");
        }

        FileUtil::makeDirectory(dirname($path));
        if (!$handle = fopen($path, 'a')) {
            throw new \RuntimeException("${path} のオープンに失敗しました。");
        }
        if (fwrite($handle, $string . "\n") === FALSE) {
            throw new \RuntimeException("${path} の書き込みに失敗しました。");
        }
        fclose($handle);
    }

    /**
     * ディレクトリを作成する。
     */
    public static function makeDirectory($path, $mode = 0777)
    {
        if (!file_exists($path)) {
            if (!mkdir($path, $mode, true)) {
                throw new \Exception("${path} ディレクトリの作成に失敗しました。");
            }
        }
    }

    /**
     * ディレクトリを削除する。
     */
    public static function removeDirectory($dir)
    {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    /**
     * ファイルの一覧を取得する。
     *
     * ## 説明
     * 指定されたディレクトリのファイルを再帰的に取得して返します。
     */
    public static function getFileList(array $directories): array
    {
        $paths = [];
        foreach ($directories as $dir) {
            if (!is_dir($dir)) continue;
            $it = new \RecursiveDirectoryIterator($dir,
                \FilesystemIterator::CURRENT_AS_FILEINFO |
                \FilesystemIterator::KEY_AS_PATHNAME |
                \FilesystemIterator::SKIP_DOTS);
            $iterator = new \RecursiveIteratorIterator($it);
            foreach ($iterator as $path => $info) {
                if ($info->isFIle()) {
                    $paths[] = $path;
                }
            }
        }
        return $paths;
    }



    /**
     * phpファイルからnamespaceを取得する。
     *
     * ## パラメータ
     * <dl>
     *   <dt>phpFilePath</dt>
     *     <dd>PHPファイルのパスを指定します。</dd>
     * </dl>
     *
     * ## 戻り値
     * namespaceを返します。取得に失敗した場合はnullを返します。
     */
    public static function getNameSpace(string $phpFilePath)
    {
        $nameSpace = null;
        if (file_exists($phpFilePath)) {
            $fp = fopen($phpFilePath, 'r');
            while ($line = fgets($fp)) {
                if (strpos($line, 'namespace ') !== false) {
                    $nameSpace = rtrim(rtrim(substr($line, strpos($line, 'namespace ') + 10)), ";");
                    break;
                }
            }
            fclose($fp);
        }
        return $nameSpace;
    }

    /**
     * クラスファイルのフルパスを取得する。
     */
    public static function getClassFileAbsolutePath($path, $fromDirs)
    {
        if (is_string($fromDirs)) {
            $fromDirs = [$fromDirs];
        } else if (!is_array($fromDirs)) {
            return false;
        }
        $fileList = FileUtil::getFileList($fromDirs);

        foreach($fileList as $filePath) {
            if (StringUtil::endsWith($filePath, $path)) {
                return $filePath;
            }
        }
        return false;
    }

    /**
     * サービスクラスの完全修飾名を取得する。
     *
     * ## 返り値
     */
    public static function getFqClassName(string $className, $fromDirs)
    {
        $classFilePath = FileUtil::getClassFileAbsolutePath($className . '.php', $fromDirs);
        if ($classFilePath) {
            $nameSpace = FileUtil::getNameSpace($classFilePath);
            if ($nameSpace) {
                return "\\" . $nameSpace . "\\" . $className;
            } else {
                return "\\" . $className;
            }
        }
        return false;
    }
}