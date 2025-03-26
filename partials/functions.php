<?php
    function percentage($part, $total) {
        return ( $part / $total ) * 100;
    }
    function part() {
        return ( $total * $part ) / 100;
    }
    function get_server_data() {
        $server = $_SERVER['SERVER_NAME']; // localhost
        $uriArray = explode('/', $_SERVER['REQUEST_URI']);
        $pagename = basename($_SERVER["SCRIPT_FILENAME"], '.php');

        
        $folder = $uriArray[1];
        if($folder === "admin") {
            $path = '../';
            $scriptArray = explode('/', $_SERVER['SCRIPT_NAME']);
            $scriptFull = explode('.', $scriptArray[2]);  
            $scriptName = $scriptFull[0];  
            // $scriptType = $scriptFull[1]; 
        } else {
            $path = './';
            $scriptArray = explode('/', $_SERVER['SCRIPT_NAME']);
            $scriptFull = explode('.', $scriptArray[1]);
            $scriptName = $scriptFull[0];
            // $scriptType = $scriptFull[1];
        }

        $s = array(
            'server' => $server,
            'uri' => $uriArray,
            'pagename' => $pagename,
            'directory' => $folder,
            'rel_path' => $path,
            'script_name' => $scriptName
            // 'script_type' => $scriptType
        );
        return $s;
    }
    function datetime_now($tz='Europe/Dublin') {
        $now = new DateTime("now", new DateTimeZone($tz) );
        $datetime = $now->format('Y-m-d H:i:s');
        return $datetime;
    }
    function elapsed($dt_from, $tz='Europe/Dublin') {
        $created_at = new DateTime($dt_from, new DateTimeZone($tz) );
        $date = new DateTime("now", new DateTimeZone($tz) );

        $interval = $date->diff($created_at);
        $elapsed_str = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
        $elapsed_array = explode(' ', $elapsed_str);
        $elapsed = '';
        if(intval($elapsed_array[0]) > 0) {
            if(intval($elapsed_array[0]) == 1) {
                $elapsed = strval($elapsed_array[0]) . ' yr ago';
            } else {
                $elapsed = strval($elapsed_array[0]) . ' yrs ago';
            }
        } elseif(intval($elapsed_array[2]) > 0) {
            if(intval($elapsed_array[2]) == 1) {
                $elapsed = strval($elapsed_array[2]) . ' month ago';
            } else {
                $elapsed = strval($elapsed_array[2]) . ' months ago';
            }
        } elseif(intval($elapsed_array[4]) > 0) {
            if(intval($elapsed_array[4]) == 1) {
                $elapsed = strval($elapsed_array[4]) . ' day ago';
            } else {
                $elapsed = strval($elapsed_array[4]) . ' days ago';
            }
        } elseif(intval($elapsed_array[6]) > 0) {
            if(intval($elapsed_array[6]) == 1) {
                $elapsed = strval($elapsed_array[6]) . ' hr ago';
            } else {
                $elapsed = strval($elapsed_array[6]) . ' hrs ago';
            }
        } elseif(intval($elapsed_array[8]) > 0) {
            if(intval($elapsed_array[8]) == 1) {
                $elapsed = strval($elapsed_array[8]) . ' min ago';
            } else {
                $elapsed = strval($elapsed_array[8]) . ' mins ago';
            }
        } elseif(intval($elapsed_array[10]) > 0) {
            if(intval($elapsed_array[10]) == 1) {
                $elapsed = strval($elapsed_array[10]) . ' second ago';
            } else {
                $elapsed = strval($elapsed_array[10]) . ' seconds ago';
            }
        }
        return $elapsed;
    }
    function elapsed_check($dt_from, $tz='Europe/Dublin') {
        $created_at = new DateTime($dt_from, new DateTimeZone($tz) );
        $date = new DateTime("now", new DateTimeZone($tz) );

        $interval = $date->diff($created_at);
        $elapsed_str = $interval->format('%y %m %a %h %i %s');
        $elapsed_array = explode(' ', $elapsed_str);

        $years = intval($elapsed_array[1]);
        $months = intval($elapsed_array[2]);
        $days = intval($elapsed_array[3]);
        $hours = intval($elapsed_array[4]);
        $minutes = intval($elapsed_array[5]);

        if($years == 0 && $months == 0 && $days < 5) {
            $e = 1;
        } else {
            $e = 0;
        }
        return $e;
    }
    function createEmail($fname, $email, $msg, $msg_subject='') {
        $msg = nl2br($_POST['msg']);
        
        $created_at = datetime_now(); // '2022-06-05 13:36:50'
        
        $stmt = $this->con->prepare("INSERT INTO contacts (fname, email, orderid, msg_subject, msg, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fname, $email, $orderid, $msg_subject, $msg, $created_at);
        if($stmt->execute()) {
            $to = 'info@instafeez.com';
            $subject = $msg_subject;
            
            $msgBody = "
            <p>Name: $fname</p>
            <p>Email: $email</p>
            <p>$msg</p>";

            $this->sendEmailSwiftMailer($to, $subject, $msgBody);
            
            $status = '1'; 
        } else {
            $status = '2';
        }
        $stmt->close();

        echo $status;

    }
    function generatePwdLink($email) {
        // GENERATE PASSWORD LINK
        $selector = bin2hex(random_bytes(8));
        $token = random_bytes(32);
    
        
        // This link will be sent to the user by email
        $url = "http://testserver3.ga/confirmation?selector=".$selector."&validator=".bin2hex($token);
        // Expiration date for token (1800ms = 1hr)
        $expires = date("U") + 1800;
        // Insert token in the database (we'll need a new table for this)
    
        // DELETE EXISTING TOKENS
        $stmt = $this->con->prepare("DELETE FROM pwd_reset WHERE pwd_reset_email=?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->close();

        // INSERT NEW TOKEN
        $stmt = $this->con->prepare("INSERT INTO pwd_reset (pwd_reset_email, pwd_reset_selector, pwd_reset_token, pwd_reset_expires) VALUES (?, ?, ?, ?);");
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        $stmt->bind_param('ssss', $email, $selector, $hashedToken, $expires);
        $stmt->execute();
        $stmt->close();

        return $url;
    }
    function sendEmailSwiftMailer($to, $subject, $msgBody) {
        // Swiftmailer
        require_once '../vendor/autoload.php'; // In Wordpress, don't require here


        // Create the Transport
        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
        ->setUsername('testemail6329@gmail.com')
        ->setPassword('jqirpnikokarwynn')
        ;
        // $transport = (new Swift_SmtpTransport('server139.web-hosting.com', 465, 'ssl'))
        // ->setUsername('info@instafeez.com')
        // ->setPassword('password')
        // ;

        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);
    
        
        $message = (new Swift_Message($subject))
        ->setFrom(['testemail6329@gmail.com' => 'Buzzoid'])
        // ->setFrom(['info@instafeez.com' => 'Instafeez'])
        ->setTo([$to])
        ->setBody($msgBody,'text/html')
        ;
        // Send the message
        $result = $mailer->send($message);
    }
    function verification_email($email) {
        // SEND THE EMAIL
        $server = $_SERVER['SERVER_NAME'];

        if($server === 'localhost') {
            return;
        } else {
            // Generate verification link
            $url = generatePwdLink($email);

            // Send email
            $to = $email;
            $subject = 'Antelov Verification Email';
            $msgBody = "<p>Verify your email by clicking link below</p>";
            $msgBody .= '<a href="'.$url.'">'.$url.'</a>';
            sendEmailSwiftMailer($to, $subject, $msgBody);  
        }
    }
    function compress($source, $destination, $quality) {

        $info = getimagesize($source);
    
        if ($info['mime'] == 'image/jpeg') 
            $image = imagecreatefromjpeg($source);
    
        elseif ($info['mime'] == 'image/gif') 
            $image = imagecreatefromgif($source);
    
        elseif ($info['mime'] == 'image/png') 
            $image = imagecreatefrompng($source);
    
        elseif ($info['mime'] == 'image/webp') 
            $image = imagecreatefromwebp($source);
    
        imagejpeg($image, $destination, $quality);
    
        return $destination;
    }
    function resize_image($file, $w, $h, $crop=FALSE) {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
        }
        $info = getimagesize($file);
        if ($info['mime'] == 'image/jpeg') 
            $src = imagecreatefromjpeg($file);
    
        elseif ($info['mime'] == 'image/gif') 
            $src = imagecreatefromgif($file);
    
        elseif ($info['mime'] == 'image/png') 
            $src = imagecreatefrompng($file);
    
        elseif ($info['mime'] == 'image/webp') 
            $src = imagecreatefromwebp($file);
    
    
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    
        return $dst;
    }
    function kv($arr1) {
        $arr2 = array();
        foreach ($arr1 as $key => $value) {
            if($value == 'a') {
                array_push($arr2, $key);
            }
        }
        return $arr2;
    }
    function str_to_str($a) {
        $new_str = '';
        $b = json_decode($a, true);
        // $new_array = explode(",", $new_array);
        if(is_array($b)) {
            if(count($b) > 0) {
                for($s=0; $s < count($b); $s++) {
                    if($s == 0) {
                        $new_str .= $b[$s];
                    } else {
                        $new_str .= ', '.$b[$s];
                    }
                }
                $new_str .= '';
            }
        }
        return $new_str;

    }

    function empty_array($arr) {
        unset($arr);
        $arr = array();
        return $arr;
    }
    function segment($content) {
        $length = strlen($content);
        $content = strip_tags($content);
        if($length > 200) {
            $content = substr($content, 0, 200).'...'; 
        }  
        return $content;
    }
    function read_time($content) {
        $words = explode(' ',$content);
        $time = count($words) / 200;
        $time = explode('.', $time);
        return $time[0];
    }
    function convert_date($d, $f, $zone='America/Chicago') {
        $date = new DateTime($d, new DateTimeZone($zone) );
        $dte = $date->format($f);
        return $dte;
    }
    function get_pagename() {
        $pagename = basename($_SERVER["SCRIPT_FILENAME"], '.php');
        return $pagename;
    }
    function get_folder() {
        $uriArray = explode('/', $_SERVER['REQUEST_URI']);
        $folder = $uriArray[1];
        return $folder;
    }
?>