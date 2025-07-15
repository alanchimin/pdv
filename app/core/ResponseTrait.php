<?php
namespace App\core;

/**
 * Encapsula métodos de resposta HTTP para facilitar testes e evitar uso direto de `header()` e `exit()`.
 */
trait ResponseTrait
{
    protected array $mockedHeaders = [];
    protected ?string $mockedOutput = null;
    protected bool $testMode = false;

    protected function respond(string $content = '', string $contentType = 'text/html', ?int $status = null): void
    {
        $this->setHeader("Content-Type: $contentType", true, $status);
        $this->output($content);
        $this->terminate();
    }

    protected function json(array $data, int $status = 200): void
    {
        $this->respond(json_encode($data), 'application/json', $status);
    }

    protected function redirect(string $url, int $status = 302): void
    {
        $this->setHeader("Location: $url", true, $status);
        $this->terminate();
    }

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

    protected function output(string $content): void
    {
        if ($this->testMode || defined('PHPUNIT_RUNNING')) {
            $this->mockedOutput = $content;
        } else {
            echo $content;
        }
    }

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

    // Métodos de acesso para testes
    public function getMockedHeaders(): array
    {
        return $this->mockedHeaders;
    }

    public function getMockedOutput(): ?string
    {
        return $this->mockedOutput;
    }

    public function enableTestMode(): void
    {
        $this->testMode = true;
    }
}
