<?php

declare(strict_types=1);

namespace Framework;

class Viewer
{

    public function render(string $template, array $variables = [])
    {
        extract($variables, EXTR_SKIP);

        ob_start();

        require ROOT_PATH . "views/$template";

        return ob_get_clean();
    }
}
