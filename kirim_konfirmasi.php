<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (isset($_POST['daftar'])) {
    $nama_pendaftar  = htmlspecialchars($_POST['nama']);
    $email_pendaftar = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USER'];
        $mail->Password   = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $_ENV['SMTP_PORT'];

        $mail->setFrom($_ENV['SMTP_USER'], $_ENV['SMTP_FROM_NAME']);
        $mail->addAddress($email_pendaftar, $nama_pendaftar);

        $mail->isHTML(true);
        $mail->Subject = 'Konfirmasi Pendaftaran Berhasil!';

        $mail->Body    = "
            <h3>Halo, <b>{$nama_pendaftar}</b>!</h3>
            <p>Terima kasih telah melakukan pendaftaran pada sistem kami.</p>
            <p>Pendaftaran Anda telah berhasil diproses. Silakan tunggu informasi selanjutnya.</p>
            <br>
            <p>Salam hangat,<br><b>{$_ENV['SMTP_FROM_NAME']}</b></p>
        ";

        $mail->AltBody = "Halo {$nama_pendaftar}, terima kasih telah mendaftar. Pendaftaran Anda berhasil diproses.";

        $mail->send();
        echo "<script>
                alert('Pendaftaran sukses! Email konfirmasi telah dikirim.');
                window.location.href = 'index.php';
            </script>";
    } catch (Exception $e) {
        echo "<script>
                alert('Pendaftaran gagal. Error: {$mail->ErrorInfo}');
                window.location.href = 'index.php';
            </script>";
    }
} else {
    header("Location: index.php");
    exit();
}
