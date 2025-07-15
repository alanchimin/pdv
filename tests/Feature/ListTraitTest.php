<?php
namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\models\Unit;
use App\core\ListTrait;
use Tests\Traits\GlobalResetTrait;

/**
 * Testes para o trait ListTrait.
 * Verifica o comportamento de renderização de views completas e parciais (AJAX),
 * além do funcionamento da paginação e filtros.
 */
class ListTraitTest extends TestCase
{
    use GlobalResetTrait;

    protected function setUp(): void
    {
        parent::setUp();

        // Define o caminho temporário onde as views serão criadas para teste
        $_ENV['VIEWS_PATH'] = sys_get_temp_dir() . '/views';
    }

    /**
     * Cria um arquivo de view temporário com conteúdo definido.
     */
    private function createViewFile(string $path, string $content): void
    {
        $fullPath = $_ENV['VIEWS_PATH'] . '/' . $path;
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($fullPath, $content);
    }

    /**
     * Cria uma instância anônima que utiliza o ListTrait para facilitar testes.
     */
    private function getListTraitInstance(): object
    {
        return new class {
            use ListTrait;

            /**
             * Método auxiliar para chamar o método list do trait e capturar saída.
             */
            public function callList(
                Unit $model,
                string $viewIndex,
                string $viewTable,
                string $entity,
                ?array $filters = null
            ) {
                $this->enableTestMode();
                ob_start();
                $this->list($model, $viewIndex, $viewTable, $entity, $filters);
                return ob_get_clean() . ($this->getMockedOutput() ?? '');
            }
        };
    }

    /**
     * Testa se o método list inclui a view completa (index.php) quando não é requisição AJAX.
     */
    public function testListIncludesFullView()
    {
        $this->createViewFile('units/index.php', '<div id="view-completa">Index view carregada</div>');
        $this->createViewFile('units/table.php', '<div id="view-tabela">Table view carregada</div>');

        $_GET = [
            'q' => 'test',
            'page' => '1',
            'order' => 'unit_id',
            'direction' => 'asc',
        ];

        $mock = $this->createMock(Unit::class);
        $mock->method('count')->willReturn(1);
        $mock->method('list')->willReturn([
            ['unit_id' => 1, 'name' => 'Litro', 'symbol' => 'L']
        ]);

        $trait = $this->getListTraitInstance();

        $output = $trait->callList($mock, 'units/index.php', 'units/table.php', 'unit');

        $this->assertStringContainsString('Index view carregada', $output);
    }

    /**
     * Testa se o método list inclui apenas a view parcial (table.php) em requisição AJAX,
     * garantindo que a view completa não é exibida.
     */
    public function testListIncludesPartialViewInAjax()
    {
        $this->createViewFile('units/index.php', '<div>Index não deveria aparecer</div>');
        $this->createViewFile('units/table.php', '<div id="view-tabela">Tabela AJAX</div>');

        $_GET = [
            'ajax' => '1',
            'page' => '2',
            'q' => 'litro',
            'order' => 'name',
            'direction' => 'desc',
        ];

        $mock = $this->createMock(Unit::class);
        $mock->method('count')->willReturn(20);
        $mock->method('list')->willReturn([
            ['unit_id' => 5, 'name' => 'Litro', 'symbol' => 'L']
        ]);

        $trait = $this->getListTraitInstance();

        $output = $trait->callList($mock, 'units/index.php', 'units/table.php', 'unit');

        $this->assertStringContainsString('Tabela AJAX', $output);
        $this->assertStringNotContainsString('Index não deveria aparecer', $output);
    }
}
