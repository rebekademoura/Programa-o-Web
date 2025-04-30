<?php

namespace App\Libraries;

class EmailService
{
    protected $email;

    public function __construct()
    {
        $this->email = \Config\Services::email();
    }

    public function enviar(array $dados)
    {
        $this->email->clear(true); 

        $this->email->setFrom($dados['from'] ??
         'no-reply@dominio.com', $dados['fromName'] ?? 'Sistema');
        $this->email->setTo($dados['to']);
        $this->email->setSubject($dados['subject']);
        $this->email->setMessage($dados['message']);

        if (!empty($dados['cc'])) {
            $this->email->setCC($dados['cc']);
        }

      
        if (!empty($dados['bcc'])) {
            $this->email->setBCC($dados['bcc']);
        }

      
        if (!empty($dados['anexo'])) {
            $this->email->attach($dados['anexo']);
        }

        return $this->email->send();
    }

    public function debug()
    {
        return $this->email->printDebugger(['headers', 'subject', 'body']);
    }
}
