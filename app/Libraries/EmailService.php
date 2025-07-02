<?php

namespace App\Libraries;

use RuntimeException;

class EmailService
{
    protected $email;
    protected $useResend;
    protected $resendKey;

    public function __construct()
    {
        // Cliente de e-mail nativo do CodeIgniter
        $this->email      = \Config\Services::email();
        // Flag e chave para Resend tiradas do .env
        $this->useResend  = getenv('USE_RESEND') === 'true';
        $this->resendKey  = getenv('RESEND_API_KEY');
    }

    /**
     * @param array $dados
     *   from       => string (email do remetente)
     *   fromName   => string (nome do remetente)
     *   to         => string|array
     *   subject    => string
     *   message    => string (html ou texto)
     *   cc         => string|array (opcional)
     *   bcc        => string|array (opcional)
     *   anexo      => string (caminho) ou array (opcional)
     *
     * @return bool
     */
    public function enviar(array $dados): bool
    {
        if ($this->useResend) {
            // --- ENVIO VIA RESEND API ---
            $payload = [
                'from'    => $dados['from']     ?? 'no-reply@dominio.com',
                'to'      => (array) ($dados['to'] ?? []),
                'subject' => $dados['subject']  ?? '(sem assunto)',
                'html'    => $dados['message']  ?? '',
            ];

            $client = \Config\Services::curlrequest([
                'baseURI' => 'https://api.resend.com',
                'timeout' => 30,
            ]);

            $resp = $client->post('/emails', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->resendKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $payload,
            ]);

            $code = $resp->getStatusCode();
            if ($code >= 200 && $code < 300) {
                return true;
            }

            throw new RuntimeException("Erro ao enviar via Resend ({$code}): " . $resp->getBody());
        }

        // --- ENVIO VIA SMTP / PROTOCOLO DO CI4 ---
        $this->email->clear(true);
        $this->email->setFrom(
            $dados['from']     ?? 'no-reply@dominio.com',
            $dados['fromName'] ?? 'Sistema'
        );
        $this->email->setTo($dados['to'] ?? []);
        $this->email->setSubject($dados['subject'] ?? '(sem assunto)');
        $this->email->setMessage($dados['message'] ?? '');

        if (!empty($dados['cc'])) {
            $this->email->setCC($dados['cc']);
        }
        if (!empty($dados['bcc'])) {
            $this->email->setBCC($dados['bcc']);
        }
        if (!empty($dados['anexo'])) {
            $this->email->attach($dados['anexo']);
        }

        if (! $this->email->send()) {
            // opcional: lance exceção ou retorne false
            throw new RuntimeException('Falha no envio SMTP: ' . $this->email->printDebugger(['headers', 'subject', 'body']));
        }

        return true;
    }

    /**
     * Para debug de SMTP
     */
    public function debug(): string
    {
        return $this->email->printDebugger(['headers', 'subject', 'body']);
    }
}
