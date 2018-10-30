<html>
	<head>
		<meta charset="utf-8" />
	</head>
	<body>

      <?php
        //print php_ini_loaded_file();
        //ini_set('SMTP', 'imap.gmail.com');
        //ini_set('smtp_port', '993');
        //ini_set('sendmail_from', 'h_ishii@asobimo.com');
        
        $addr = htmlspecialchars($_POST["mail"], ENT_QUOTES);
        $subject = "TEST MAIL";
        $message = "Hello!\r\nThis is TEST MAIL.";
        $headers = "From: h_ishii@asobimo.com";
        
        if(mb_send_mail($addr, $subject, $message, $headers))
        {
          echo "メールを送信しました";
        }
        else
        {
          echo "メールの送信に失敗しました";
        }
      ?>
    </body>
</html>
