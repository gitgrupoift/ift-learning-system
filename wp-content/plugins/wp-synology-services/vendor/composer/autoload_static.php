<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4e4676e0b19166e1d3ff9807bb97335f
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SynologyPS\\Api\\' => 15,
            'SynologyPS\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SynologyPS\\Api\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes/api',
        ),
        'SynologyPS\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'SynologyPS\\Api\\Api' => __DIR__ . '/../..' . '/includes/api/Api.php',
        'SynologyPS\\Api\\ApiAbstract' => __DIR__ . '/../..' . '/includes/api/ApiAbstract.php',
        'SynologyPS\\Api\\ApiAuth' => __DIR__ . '/../..' . '/includes/api/ApiAuth.php',
        'SynologyPS\\Api\\ApiException' => __DIR__ . '/../..' . '/includes/api/ApiException.php',
        'SynologyPS\\Api\\DownloadStation' => __DIR__ . '/../..' . '/includes/api/DownloadStation.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4e4676e0b19166e1d3ff9807bb97335f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4e4676e0b19166e1d3ff9807bb97335f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4e4676e0b19166e1d3ff9807bb97335f::$classMap;

        }, null, ClassLoader::class);
    }
}