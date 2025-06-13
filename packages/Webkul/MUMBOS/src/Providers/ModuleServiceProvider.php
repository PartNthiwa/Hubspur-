<?php

namespace Webkul\MUMBOS\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [

                // Add your models here,
                \Webkul\MUMBOS\Models\Shareholder::class,
                \Webkul\MUMBOS\Models\Contribution::class,
         
    ];
}