<?php
register_autoloaders();

function register_autoloaders()
{
    spl_autoload_register('autoload_modelrepository');
    spl_autoload_register('autoload_model');
    spl_autoload_register('autoload_controller');
    spl_autoload_register('autoload_view');
}

function autoload_modelrepository($class)
{
    //echo "Repo : $class\n";
    $file = 'model-repository/'.$class.'.php';
    if (is_readable($file))
    {
        require_once $file;
    }
}

function autoload_model($class)
{
    //echo "Model : $class\n";
    $file = 'model/'.$class.'.php';
    if (is_readable($file))
    {
        require_once $file;
    }
}

function autoload_controller($class)
{
    $file = 'controller/'.$class.'.php';
    if (is_readable($file))
    {
        require_once $file;
    }
}

function autoload_view($class)
{
    $file = 'view/'.$class.'.php';
    if (is_readable($file))
    {
        require_once $file;
    }
}
