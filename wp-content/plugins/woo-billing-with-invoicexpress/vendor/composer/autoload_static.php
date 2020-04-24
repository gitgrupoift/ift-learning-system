<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitabd2de8cad73496be38bae28f2f13f7a
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Webdados\\InvoiceXpressWooCommerce\\' => 34,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Webdados\\InvoiceXpressWooCommerce\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'C' => 
        array (
            'Curl' => 
            array (
                0 => __DIR__ . '/..' . '/curl/curl/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitabd2de8cad73496be38bae28f2f13f7a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitabd2de8cad73496be38bae28f2f13f7a::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitabd2de8cad73496be38bae28f2f13f7a::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
