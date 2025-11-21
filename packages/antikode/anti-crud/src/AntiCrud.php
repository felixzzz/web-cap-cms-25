<?php

namespace Antikode\AntiCrud;

use Illuminate\Support\Str;

class AntiCrud extends ThePath
{
    public static function buildClass($name, $model, $table, $routeGroup)
    {
        static::controller($name, );
    }

    protected static function controller($name, $tableName, $routeGroup)
    {
        $viewPath = 'anti.'.Str::slug($name, "_");
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{viewPath}}',
                '{{routeGroup}}',
                '{{prefixPath}}'
            ],
            [
                $name,
                strtolower(str_plural($name)),
                strtolower($name),
                $viewPath,
                $routeGroup,
                static::prefix()
            ],
            static::getStub('controller')
        );

        if (!file_exists(app_path(static::$_pathController))) {
            mkdir(app_path(static::$_pathController), 0755, true);
        }

        file_put_contents(app_path(static::$_pathController."/{$name}Controller.php"), $controllerTemplate);
    }

    protected static function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }


}
