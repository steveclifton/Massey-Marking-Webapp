<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd14b701456a190287802b67245d9893b
{
    public static $classMap = array (
        'Marking\\Controllers\\Admin' => __DIR__ . '/../..' . '/Controllers/Admin.Controller.php',
        'Marking\\Controllers\\Assignment' => __DIR__ . '/../..' . '/Controllers/Assignment.Controller.php',
        'Marking\\Controllers\\AssignmentConfig' => __DIR__ . '/../..' . '/Controllers/AssignmentConfig.Controller.php',
        'Marking\\Controllers\\Authentication' => __DIR__ . '/../..' . '/Controllers/Authentication.Controller.php',
        'Marking\\Controllers\\Base' => __DIR__ . '/../..' . '/Controllers/Base.Controller.php',
        'Marking\\Controllers\\Errors' => __DIR__ . '/../..' . '/Controllers/Errors.Controller.php',
        'Marking\\Controllers\\Folders' => __DIR__ . '/../..' . '/Controllers/Folders.Controller.php',
        'Marking\\Controllers\\Welcome' => __DIR__ . '/../..' . '/Controllers/Welcome.Controller.php',
        'Marking\\Exceptions\\CustomException' => __DIR__ . '/../..' . '/Exceptions/Custom.Exception.php',
        'Marking\\Models\\Base' => __DIR__ . '/../..' . '/Models/Base.Model.php',
        'Marking\\Models\\Feedback' => __DIR__ . '/../..' . '/Models/Feedback.Model.php',
        'Marking\\Models\\Marks' => __DIR__ . '/../..' . '/Models/Marks.Model.php',
        'Marking\\Models\\User' => __DIR__ . '/../..' . '/Models/User.Model.php',
        'Marking\\Services\\DB' => __DIR__ . '/../..' . '/Services/DB.Service.php',
        'Marking\\Services\\Database' => __DIR__ . '/../..' . '/Services/Database.Service.php',
        'Marking\\Services\\Env' => __DIR__ . '/../..' . '/Services/Env.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitd14b701456a190287802b67245d9893b::$classMap;

        }, null, ClassLoader::class);
    }
}
