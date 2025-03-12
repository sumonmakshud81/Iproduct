<?php
/*
  ##############################################################################
  # iProduction - Production and Manufacture Management Software
  ##############################################################################
  # AUTHOR:		Door Soft
  ##############################################################################
  # EMAIL:		info@doorsoft.co
  ##############################################################################
  # COPYRIGHT:		RESERVED BY Door Soft
  ##############################################################################
  # WEBSITE:		https://www.doorsoft.co
  ##############################################################################
  # This is MailSendController
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmailAttachment;

class MailSendController extends Controller
{
    /**
     * Send a mail to user
     */
    public static function sendMailToUsers($mail_data)
    {
        $mailSubject = !empty($mail_data['subject']) ? $mail_data['subject'] : '';

        if (!empty($mail_data) && !empty($mail_data['to']) && !empty($mail_data['view'])) {
            $mail = new PHPMailer(true);
            try
            {
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Timeout = 30;
                $mail->Host = smtpInfo()['mail_host'];
                $mail->SMTPAuth = true;
                $mail->Username = smtpInfo()['mail_username'];
                $mail->Password = smtpInfo()['mail_password'];
                $mail->SMTPSecure = smtpInfo()['mail_encryption'];
                $mail->Port = smtpInfo()['mail_port'];
                $mail->setFrom(smtpInfo()['mail_from'], smtpInfo()['from_name']);
                foreach ($mail_data['to'] as $email) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $mail->addAddress($email, 'To');
                    }
                }
                $mail->isHTML(true);
                $mail->Subject = $mailSubject;
                $mail->Body = view('mail.' . $mail_data['view'], ['mail_data' => $mail_data])->render();
                $mail->send();
            } catch (Exception $e) {
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            }
        }
    }
    /**
     * Send a mail to user
     */
    public static function sendMailToUser($mail_data)
    {

        if (testSendinBlueApi() == 200 && !empty($mail_data) && !empty($mail_data['to']) && !empty($mail_data['view'])) {

            try {
                $credentials = Configuration::getDefaultConfiguration()->setApiKey('api-key', smtpInfo()['api_key']);
                $apiInstance = new TransactionalEmailsApi(new Client(), $credentials);

                $mailSubject = !empty($mail_data['subject']) ? $mail_data['subject'] : '';
                $reply_to = (isset($mail_data['reply_to']) && $mail_data['reply_to'] ? $mail_data['reply_to'] : smtpInfo()['mail_from']);
                $from_name = (isset($mail_data['from_name']) && $mail_data['from_name'] ? $mail_data['from_name'] : smtpInfo()['from_name']);

                foreach ($mail_data['to'] as $email) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
                            'subject' => $mailSubject,
                            'sender' => ['name' => $from_name, 'email' => $reply_to],
                            'replyTo' => ['name' => $from_name, 'email' => $reply_to],
                            'to' => [['name' => userNameByEmail($email), 'email' => $email]],
                            'htmlContent' => view('mail.' . $mail_data['view'], ['mail_data' => $mail_data])->render(),

                        ]);

                        if (isset($mail_data['file_path'])) {
                            $attachment = new SendSmtpEmailAttachment();
                            $attachment->setName($mail_data['file_name']);
                            $attachment->setContent(base64_encode(file_get_contents($mail_data['file_path'])));
                            $sendSmtpEmail->setAttachment([$attachment]);
                        }

                        try {
                            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
                        } catch (Exception $e) {
                            echo ($e->getMessage()), PHP_EOL;
                        }
                    }
                }
            } catch (Exception $e) {
                echo ($e->getMessage()), PHP_EOL;
            }

        }
    }
}
