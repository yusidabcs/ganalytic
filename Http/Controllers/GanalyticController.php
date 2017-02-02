<?php
namespace Modules\Ganalytic\Http\Controllers;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

/**
 * Created by PhpStorm.
 * User: yusida
 * Date: 2/2/17
 * Time: 10:58 AM
 */
class GanalyticController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        return 1;
    }
}