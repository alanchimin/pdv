<?php
namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\controllers\IconController;

/**
 * Testes para o controller de ícones.
 */
class IconTest extends TestCase
{
    /**
     * Controller com ResponseTrait em modo de teste.
     */
    private function makeTestableController(): IconController
    {
        return new class extends IconController {
            public function __construct()
            {
                $this->enableTestMode();
            }
        };
    }

    /**
     * Deve retornar um JSON contendo os ícones disponíveis no diretório assets/fontawesome/svgs/solid.
     * O teste cria arquivos mock .svg e um arquivo não relacionado para verificar o filtro por extensão.
     */
    public function testListShouldReturnJsonWithIcons()
    {
        $controller = $this->makeTestableController();

        // Simula diretório com arquivos SVG
        $iconsDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/fontawesome/svgs/solid';
        if (!is_dir($iconsDir)) {
            mkdir($iconsDir, 0777, true);
        }

        // Cria alguns arquivos mock SVG
        file_put_contents("$iconsDir/house.svg", '<svg></svg>');
        file_put_contents("$iconsDir/user.svg", '<svg></svg>');
        file_put_contents("$iconsDir/README.md", 'ignore'); // não deve ser incluído

        $controller->list();

        $headers = $controller->getMockedHeaders();
        $output = $controller->getMockedOutput();

        $this->assertContains('Content-Type: application/json', $headers);
        $this->assertJson($output);

        $data = json_decode($output, true);

        $this->assertIsArray($data);
        $this->assertCount(2, $data); // house, user

        $this->assertEquals('fa-solid fa-house', $data[0]['class']);
        $this->assertEquals('fa-solid fa-user', $data[1]['class']);

        // Limpeza
        unlink("$iconsDir/house.svg");
        unlink("$iconsDir/user.svg");
        unlink("$iconsDir/README.md");
        rmdir($iconsDir);
        rmdir(dirname($iconsDir)); // /solid
        rmdir(dirname(dirname($iconsDir))); // /svgs
    }

    /**
     * Deve retornar um array vazio quando o diretório de ícones não existir.
     */
    public function testListShouldReturnEmptyArrayIfDirNotFound()
    {
        $controller = $this->makeTestableController();

        // Garante que diretório não existe
        $path = $_SERVER['DOCUMENT_ROOT'] . '/assets/fontawesome/svgs/solid';
        if (is_dir($path)) {
            rmdir($path);
        }

        $controller->list();

        $headers = $controller->getMockedHeaders();
        $output = $controller->getMockedOutput();

        $this->assertContains('Content-Type: application/json', $headers);
        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertEquals([], $data);
    }
}
