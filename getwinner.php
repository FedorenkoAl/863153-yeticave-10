<?php
require_once ('helpers.php');
require_once ('vendor/autoload.php');

$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
mysqli_set_charset($link, "utf8");
check($link);

if (isset($_SESSION['user'])) {
    $sql_lot = 'SELECT id, name, author_winner FROM lots WHERE data_end <= NOW()';
    $lot = db_fetch_data($link, $sql_lot, []);
    if (count($lot)) {
        foreach ($lot as $key => $value) {
            if ($value['author_winner'] == 0) {
                $id = $value['id'];
                $lot_rate = "SELECT r.rate_user, u.name, u.email FROM rate r
                        LEFT JOIN user u ON u.id = r.rate_user
                        WHERE r.rate_lots = $id  ORDER BY r.price DESC LIMIT 1";
                $rate = db_fetch_data_assos($link, $lot_rate, []);
                if ($rate['rate_user'] == $_SESSION['user']['id']) {
                    $rat = $rate['rate_user'];
                    $sql_user = "UPDATE lots SET  author_winner = '$rat' WHERE id = '$id'";
                    mysqli_query($link, $sql_user);

                        $recipients[$rate['email']] = $rate['name'];
                        $rate['lot-name'] = $value['name'];
                        $rate ['id'] = $value['id'];

                        $transport = new Swift_SmtpTransport("phpdemo.ru", 25);
                        $transport->setUsername("keks@phpdemo.ru");
                        $transport->setPassword("htmlacademy");



                        $message = new Swift_Message();
                        $message->setSubject("Ваша ставка победила");
                        $message->setFrom(['keks@phpdemo.ru' => 'GifTube']);
                        $message->setBcc($recipients);

                        $msg_content = include_template('email.php', [
                            'rate' => $rate
                        ]);
                        $message->setBody($msg_content, 'text/html');

                        $mailer = new Swift_Mailer($transport);
                        $result = $mailer->send($message);

                }
            }
        }
    }
}

