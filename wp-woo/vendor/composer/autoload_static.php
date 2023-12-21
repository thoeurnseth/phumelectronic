<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2f656371e1531b348c5b5526bda997dc
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Automattic\\WooCommerce\\' => 23,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Automattic\\WooCommerce\\' => 
        array (
            0 => __DIR__ . '/..' . '/automattic/woocommerce/src/WooCommerce',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2f656371e1531b348c5b5526bda997dc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2f656371e1531b348c5b5526bda997dc::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2f656371e1531b348c5b5526bda997dc::$classMap;

        }, null, ClassLoader::class);
    }
}
