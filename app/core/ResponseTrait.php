<?php
namespace App\core;

/**
 * Encapsula métodos para enviar respostas HTTP de forma consistente,
 * facilitando testes e evitando chamadas diretas a header() e exit().
 * Permite ativar um modo de teste para capturar headers e saída sem finalizar a execução.
 */
trait ResponseTrait
{
    // Headers que foram "enviados" durante o modo de teste.
    protected array $mockedHeaders = [];

    // Conteúdo da resposta armazenado no modo de teste.
    protected ?string $mockedOutput = null;

    // Indica se o modo de teste está ativo.
    protected bool $testMode = false;

    /**
     * Envia uma resposta HTTP com conteúdo e tipo definido, e termina a execução.
     */
    protected function respond(string $content = '', string $contentType = 'text/html', ?int $status = null): void
    {
        $this->setHeader("Content-Type: $contentType", true, $status);
        $this->output($content);
        $this->terminate();
    }

    /**
     * Envia uma resposta JSON formatada com status HTTP.
     */
    protected function json(array $data, int $status = 200): void
    {
        $this->respond(json_encode($data), 'application/json', $status);
    }

    /**
     * Envia um redirecionamento HTTP com URL e status.
     */
    protected function redirect(string $url, int $status = 302): void
    {
        $this->setHeader("Location: $url", true, $status);
        $this->terminate();
    }

    /**
     * Registra um header HTTP.
     * No modo de teste, apenas armazena o header para verificação.
     */
    protected function setHeader(string $header, bool $replace = true, ?int $status = null): void
    {
        if ($this->testMode || defined('PHPUNIT_RUNNING')) {
            $this->mockedHeaders[] = $header;
        } else {
            if ($status !== null) {
                header($header, $replace, $status);
            } else {
                header($header, $replace);
            }
        }
    }

    /**
     * Envia o conteúdo da resposta.
     * No modo de teste, armazena o conteúdo para verificação.
     */
    protected function output(string $content): void
    {
        if ($this->testMode || defined('PHPUNIT_RUNNING')) {
            $this->mockedOutput = $content;
        } else {
            echo $content;
        }
    }

    /**
     * Termina a execução da resposta.
     * No modo de teste, apenas adiciona uma marcação no output.
     */
    protected function terminate(?string $msg = null): void
    {
        if ($this->testMode || defined('PHPUNIT_RUNNING')) {
            $isJson = str_contains(implode('', $this->mockedHeaders), 'application/json');

            if (!$isJson) {
                $terminatedMsg = empty($msg) ? '[TERMINATED]' : "[TERMINATED: $msg]";
                $this->mockedOutput .= $terminatedMsg;
            }

            return;
        }

        exit($msg ?? '');
    }

    // ===== Métodos públicos para auxiliar nos testes =====

    /**
     * Retorna os headers armazenados no modo de teste.
     */
    public function getMockedHeaders(): array
    {
        return $this->mockedHeaders;
    }

    /**
     * Retorna o conteúdo armazenado no modo de teste.
     */
    public function getMockedOutput(): ?string
    {
        return $this->mockedOutput;
    }

    /**
     * Ativa o modo de teste, que evita chamadas reais a header() e exit().
     */
    public function enableTestMode(): void
    {
        $this->testMode = true;
    }
}
