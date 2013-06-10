<?php


namespace Tbf\Component\FileSystem;
use Tbf\Util\PathUtil;
use Tbf\Component\FileSystem\Exception\TooDeepRecursionException;
use Tbf\Component\FileSystem\Exception\FileException;
/**
 * 文件系统的一些操作封装
 * 默认行为：
 *     所有文件操作，默认行为均为覆盖。（尽最大努力删除旧的，写入新的。。）
 *     所有操作默认均为递归执行。
 */
final class FileSystemFunction {
    /**
     * 递归拷贝文件
     */
    static function copy($origin,$target,$override = true){
        return self::copyByCallBack($origin,$target,$override);
    }
    /**
     * 递归拷贝文件。
     * 使用回调确定是否拷贝该文件。
     * func (path,isDir)(needCopy)
     */
    static function copyByCallBack($origin,$target,$override = true,$cb=null){
        if (is_file($origin)){
            return self::copy($origin,$target,$override);
        }
        if ($cb===null){
            $cb = function(){
                return true;
            };
        }
        PathUtil::listFileRByCallback($origin,
            function($path,$isDir)use($origin,$target,$override,$cb){
                if (!$cb($path,$isDir)){
                    return false;
                }
                $ref = PathUtil::refPath($path,$origin);
                $thisOrigin = $path;
                $thisTarget = $target.'/'.$ref;
                self::copyOneFile($thisOrigin,$thisTarget,$override);
                return true;
            });
    }
    /**
     * 拷贝一个文件
     */
    static function copyOneFile($origin,$target,$override = true){
        //不覆盖
        if ( !$override && file_exists($target)){
            return ;
        }
        //创建目录
        self::mkdir(dirname($target));
        if ( is_dir($origin)){
            self::mkdir($target);
        }else{
            copy($origin,$target);
        }
    }
    /**
     * 递归创建目录,如果目录存在，则什么也不做
     * 如果选择覆盖，如果这位置有一个文件不是目录，则删除该文件，并创建目录
    @param string $path
     */
    static function mkdir($path,$mode = 0777,$override = true){
        if (file_exists($path)){
            if (!$override){
                return;
            }
            if (self::isPlainDir($path)){
                //一个指向目录的链接也是目录。。。
                return ;
            }else{
                self::delete($path);
            }
        }
        mkdir($path,$mode,true);
    }
    /**
     * 建立软链接
     */
    static function symlink($origin,$target,$override = true){
        if (file_exists($target)){
            if ($override){
                self::delete($target);
            }else{
                return;
            }
        }
        symlink($origin,$target);
    }
    /**
     * 删除文件或文件夹
     * 1.文件不存在不报错
     * 2.没有权限会报php error
     * 3.无论文件的内容是什么,一定删除
     */
    static function delete($path,$deep = 10){
        if ($deep<0){
            throw new TooDeepRecursionException('toDeep? path:'.$path);
        }
        //文件不存在。。
        if (!file_exists($path)){
            return ;
        }
        if (self::isPlainDir($path)){
            $basePath = $path;
            $list = PathUtil::listDir($path,array('.git'=>null));
            foreach($list as $v1){
                self::delete($v1,$deep-1);
            }
            $ret = rmdir($path);
            if ($ret){
                return;
            }
            throw new FileException('can not delete dir path:'.$path);
        }
        $ret = unlink($path);
        if ($ret){
            return;
        }
        throw new FileException('can not delete dir path:'.$path);
    }
    /**
     * 写入文件
     * 默认
     * 1.目录不存在创建目录
     * 2.覆盖原有文件
     * 配置
     * 1.isMkdir 是否创建目录？ 是
     * 2.override 是否覆盖？  是
     */
    static function putContent($path,$content,$option=array()){
        $default = array('override'=>true,
            'isMkdir'=>true);
        $option = array_merge($default,$option);
        //不覆盖
        if ( !$option['override'] && file_exists($path)){
            return ;
        }
        //创建目录
        if ($option['isMkdir']){
            self::mkdir(dirname($path));
        }
        file_put_contents($path,$content);
    }
    /**
     * 是普通目录？
     */
    static function isPlainDir($path){
        return is_dir($path)&&(!is_link($path));
    }

    /**
     * 移动文件
     * 1.目录不存在创建目录
     * 2.覆盖目标文件
     * bool 是否成功
     */
    static function move($origin_path,$dest_path){
        self::mkdir(dirname($dest_path));
        return rename($origin_path,$dest_path);
    }
}