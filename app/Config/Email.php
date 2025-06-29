<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    // Remetente padrão
    public string $fromEmail  = 'graeffdemourar@gmail.com';
    public string $fromName   = 'Viva Leve';

    // Protocolo SMTP
    public string $protocol   = 'smtp';
    public string $SMTPHost   = 'smtp.gmail.com';
    public string $SMTPUser   = 'graeffdemourar@gmail.com';
    public string $SMTPPass   = 'pfakntbuzmgybupx';  // senha de app, sem espaços
    public int    $SMTPPort   = 587;
    public string $SMTPCrypto = 'tls';

    // Outras opções
    public bool   $SMTPKeepAlive = false;
    public int    $SMTPTimeout   = 30;
    public bool   $wordWrap      = true;
    public int    $wrapChars     = 76;
    public string $mailType      = 'html';
    public string $charset       = 'UTF-8';
    public string $newline       = "\r\n";
    public string $CRLF          = "\r\n";
}
