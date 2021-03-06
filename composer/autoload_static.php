<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit51e373cb012756ccc2e1efe08179d5db
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twilio\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twilio\\' => 
        array (
            0 => __DIR__ . '/..' . '/twilio/sdk/src/Twilio',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit51e373cb012756ccc2e1efe08179d5db::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit51e373cb012756ccc2e1efe08179d5db::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
