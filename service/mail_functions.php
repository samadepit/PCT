<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function envoyerMail($to, $subject, $htmlContent) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'nombresalif225@gmail.com';
        $mail->Password = 'mzts wqjn aqfg njfr';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('nombresalif225@gmail.com', 'PCT groupe27');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlContent;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur envoi mail : {$mail->ErrorInfo}");
        return false;
    }
}

function notifierDemandeur($email, $codeDemande, $statut) {
    switch ($statut) {
        case 'cree':
            $sujet = "Votre demande a été créée";
            $messageHtml = "<p>Bonjour,</p>
                            <p>Votre demande a bien été créée. Voici votre code de demande : <strong>{$codeDemande}</strong>.</p>
                            <p>Nous vous tiendrons informé de la suite.</p>";
            break;
        case 'valide':
            $sujet = "Votre demande a été validée";
            $messageHtml = "<p>Bonjour,</p>
                            <p>Félicitations ! Votre demande (référence : <strong>{$codeDemande}</strong>) a été approuvée. Nous procédons actuellement à sa signature – nous vous informerons dès sa finalisation.</p>";
            break;
        case 'rejete':
            $sujet = "Votre demande a été rejetée";
            $messageHtml = "<p>Bonjour,</p>
                            <p>Nous regrettons de vous informer que votre demande (référence : <strong>{$codeDemande}</strong>) n'a pas pu être acceptée. Vous pouvez consulter les motifs de ce refus directement sur la plateforme.</p>";
            break;
        case 'signe':
            $sujet = "Votre document a été signé";
            $messageHtml = "<p>Bonjour,</p>
                            <p>Votre document lié à la demande (référence : <strong>{$codeDemande}</strong>) est disponible 
                            . Veuillez vous rendre sur la plateforme pour procéder au paiement du timbre et imprimez l'acte dès maintenant.</p>";
            break;
        default:
            return false;
    }
    return envoyerMail($email, $sujet, $messageHtml);
}
