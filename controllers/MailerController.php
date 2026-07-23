<?php



require_once __DIR__ . '/../mail/Exception.php';
require_once __DIR__ . '/../mail/PHPMailer.php';
require_once __DIR__ . '/../mail/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class MailerController
{
    private PHPMailer $mailer;

    public function __construct(bool $enableDebug = false) 
    {
        $this->mailer = new PHPMailer(true);

        // Ativa o debug HTML caso seja solicitado no construtor
        if ($enableDebug) {
            $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
            $this->mailer->Debugoutput = 'html';
        }

        $this->configureSMTP();
    }

    /**
     * Define as configurações padrão do servidor SMTP (Google)
     */
    private function configureSMTP(): void 
    {
        $this->mailer->isSMTP();
        $this->mailer->Host       = 'smtp.gmail.com';
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = 'classificados.galatas6@gmail.com';
        $this->mailer->Password   = 'hnudyjusvuauayec'; // Insira a Senha de App do Google (16 caracteres)
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port       = 587;
        $this->mailer->CharSet    = 'UTF-8';

        // Remetente padrão
        $this->mailer->setFrom('classificados.galatas6@gmail.com', 'Gálatas 6');
    }



    public function sendEmailWelcome($mailTo, $name){
        try {


    // Assunto
    $subject = 'Bem-vindo! Seu cadastro foi recebido com sucesso';

    $mailBody = '
    <!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boas-vindas aos Classificados Gálatas 6</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f6f9; padding: 30px 10px;">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    
                    <!-- Topo / Cabeçalho -->
                    <tr>
                        <td style="background-color: #1a365d; padding: 28px 25px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: bold; letter-spacing: -0.5px;">
                                Classificados Gálatas 6
                            </h1>
                            <p style="color: #cbd5e0; margin: 6px 0 0 0; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                                Confirmação de Cadastro
                            </p>
                        </td>
                    </tr>

                    <!-- Conteúdo Principal -->
                    <tr>
                        <td style="padding: 30px 25px; color: #2d3748;">
                            <h2 style="color: #1a202c; font-size: 18px; margin-top: 0; margin-bottom: 16px;">
                                Seja muito bem-vindo(a)!
                            </h2>

                            <p style="font-size: 14px; line-height: 1.6; color: #4a5568; margin-bottom: 20px;">
                                Recebemos a sua solicitação de cadastro em nosso sistema de classificados.
                            </p>

                            <!-- Bloco de Alerta / Status -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #fffaf0; border-left: 4px solid #dd6b20; border-top: 1px solid #feebc8; border-right: 1px solid #feebc8; border-bottom: 1px solid #feebc8; border-radius: 6px; margin: 20px 0;">
                                <tr>
                                    <td style="padding: 16px;">
                                        <strong style="color: #c05621; font-size: 14px; display: block; margin-bottom: 6px;">
                                            ⏳ Análise em Andamento
                                        </strong>
                                        <p style="margin: 0; color: #7b341e; font-size: 13px; line-height: 1.5;">
                                            Por favor, aguarde a liberação do seu cadastro. Poderemos entrar em contato para solicitar algumas informações adicionais de segurança.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 14px; line-height: 1.6; color: #4a5568; margin-top: 15px; margin-bottom: 25px;">
                                Assim que seu acesso for aprovado, você receberá uma notificação para começar a publicar seus anúncios. Enquanto isso, aproveite para explorar os anúncios da nossa comunidade!
                            </p>

                            <!-- Botão de Ação (CTA) -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 30px;">
                                <tr>
                                    <td align="center">
                                        <a href="https://galatas6.com.br/" target="_blank" style="background-color: #2b6cb0; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-size: 14px; font-weight: bold; display: inline-block;">
                                            Explorar Anúncios
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <hr style="border: 0; border-top: 1px solid #edf2f7; margin: 25px 0;">

                            <!-- Versículo / Mensagem Final -->
                            <div style="background-color: #f7fafc; padding: 18px; border-radius: 8px; text-align: center; border: 1px dashed #e2e8f0;">
                                <p style="margin: 0 0 10px 0; font-size: 13px; color: #4a5568; font-style: italic; line-height: 1.5;">
                                    "Portanto, enquanto temos oportunidade, façamos o bem a todos, especialmente aos da família da fé."
                                </p>
                                <span style="font-size: 11px; color: #718096; font-weight: bold; display: block; margin-bottom: 12px; text-transform: uppercase;">
                                    — Gálatas 6:10
                                </span>
                                <p style="margin: 0; font-size: 15px; color: #2b6cb0; font-weight: bold;">
                                    Deus abençoe sua vida!
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Rodapé -->
                    <tr>
                        <td style="background-color: #f7fafc; padding: 18px 25px; text-align: center; border-top: 1px solid #edf2f7; color: #a0aec0; font-size: 11px; line-height: 1.4;">
                            Esta é uma mensagem automática do sistema de Classificados Gálatas 6.<br>
                            Por favor, não responda a este e-mail.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
    ';

    // Texto Alternativo (para clientes de e-mail que não aceitam HTML)
    $mailAltBody = "Seja muito bem-vindo(a)!\n\nAguarde a liberação do cadastro, poderemos entrar em contato para solicitar algumas informações.\n\nDeus abençoe sua vida.";

    $this->send($mailTo, $name, $subject, $mailBody, $mailAltBody);
    
} catch (Exception $e) {
    return  "Erro ao enviar e-mail: {$mail->ErrorInfo}";
}
    }


    /**
     * Envia o e-mail para o destinatário informado
     */
    private function send(
        string $toEmail, 
        string $toName, 
        string $subject, 
        string $htmlBody, 
        string $altBody = ''
    ): bool {
        try {
            // Limpa destinatários de envios anteriores
            $this->mailer->clearAddresses();

            // Adiciona o destinatário
            $this->mailer->addAddress($toEmail, $toName);

            // Define o conteúdo
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $htmlBody;
            $this->mailer->AltBody = $altBody ?: strip_tags($htmlBody);

            return $this->mailer->send();
        } catch (Exception $e) {
            // Registra a mensagem de erro detalhada no log de erros do sistema
            error_log("Erro no envio do PHPMailer: {$this->mailer->ErrorInfo}");
            return false;
        }
    }

    /**
     * Retorna a mensagem do último erro ocorrido no PHPMailer
     */
    public function getLastError(): string 
    {
        return $this->mailer->ErrorInfo;
    }
}