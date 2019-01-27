<?php

/**
 * Copyright 2012-2019 Christoph M. Becker
 *
 * This file is part of Minicounter_XH.
 *
 * Minicounter_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Minicounter_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Minicounter_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Minicounter;

class CounterController
{
    /**
     * @var Model
     */
    private $model;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->model = new Model;
    }

    /**
     * @return void
     */
    public function defaultAction()
    {
        $count = isset($_COOKIE['minicounter']) ? abs($_COOKIE['minicounter']) : $this->model->count() + 1;
        $view = new View('counter');
        $view->count = $count;
        $view->render();
    }
}
