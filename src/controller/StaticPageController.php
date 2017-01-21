<?php

namespace maxime\controller;

use lib\core\Context;

/**
 * Class StaticPageController
 */
class StaticPageController
{
    /**
     * @param $request
     * @param Context $context
     *
     * @return string
     */
    public static function index($request, $context)
    {
        return Context::SUCCESS;
    }

    /**
     * @param $request
     * @param Context $context
     *
     * @return string
     */
    public static function mentionsLegales($request, $context)
    {
        return Context::SUCCESS;
    }

    /**
     * @param $request
     * @param Context $context
     *
     * @return string
     */
    public static function contact($request, $context)
    {
        return Context::SUCCESS;
    }
}
