<?php
namespace App\controllers;

class IconeController
{
    public function list()
    {
        $icons = [];
        $style = 'solid';
        $path = "{$_SERVER['DOCUMENT_ROOT']}/assets/fontawesome/svgs/$style";

        if (is_dir($path)) {
            $files = scandir($path);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'svg') {
                    $name = pathinfo($file, PATHINFO_FILENAME);
                    $icons[] = [
                        'style' => $style,
                        'name' => $name,
                        'class' => "fa-$style fa-$name"
                    ];
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($icons);
        exit;
    }
}
