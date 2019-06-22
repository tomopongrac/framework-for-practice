<?php

namespace App\Controllers;

use App\Views\View;

class DashboardController
{
    protected $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }
    public function index()
    {
        return $this->view->render('dashboard/index.twig');
    }
}
