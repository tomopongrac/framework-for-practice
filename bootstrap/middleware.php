<?php

foreach ($container->get('config')->get('middleware') as $middleware) {
    $route->middleware($container->get($middleware));
}