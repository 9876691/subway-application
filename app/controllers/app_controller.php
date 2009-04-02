<?php
    include_once "base_controller.php";

    class AppController extends Controller
    {
        var $uses = array('Configuration');

        function beforeFilter()
        {
            if ($this->action != 'licence')
            {
                $licence_config = $this->Configuration->find(
                    array('Configuration.name' => 'licence'));
                $licence_checked = $this->Configuration->find(
                    array('Configuration.name' => 'licence-check'));

                if($this->check_licence(
                    $licence_config['Configuration']['value']) == 1)
                {
                    // Licence is valid, but when was the last time we dialed
                    // home
                    $daily = $this->generate_daily_licence();

                    if($daily != $licence_checked['Configuration']['value'])
                    {
                        // We only do this once a day to save bandwidth
                        if($this->fetch_licence_from_server(
                            $licence_config['Configuration']['value'],
                                $this->params['form']) == 1)
                        {
                            // Add the daily licence
                            $this->Configuration->query("DELETE FROM ". BaseController::get_db_prefix().
                              "configurations WHERE name = 'licence-check'");
                            $this->Configuration->query("INSERT INTO ". BaseController::get_db_prefix().
                              "configurations (value, name) VALUES ('".
                                $daily."','licence-check')");
                        }
                        else
                        {
                            $this->redirect('/licence/licence');
                            exit();
                        }
                    }
                }
                else
                {
                    $this->redirect('/licence/licence');
                    exit();
                }
            }

            parent::beforeFilter(); // call the AppController::beforeFilter()
        }

        // Is the licence valid for this hsot and ip
        function generate_daily_licence()
        {
            $host = $_SERVER['HTTP_HOST'];
            $host = str_replace('www.', '', $host);
            $ip = $_SERVER['SERVER_ADDR'];
            if (!$ip) { $ip = $_SERVER['LOCAL_ADDR']; }
            if (!$ip) { $ip = gethostbyname($host); }
            if ($ip == '0.0.0.0') { $ip = gethostbyname($host); } // Lighthttpd Fix

            $hash = 'pq8bytpq34ytnq3ptyq347ty0q34t734'; // Hash
            $key = '3hvf8734hfh3f2734f0273f2043fn7nf'; // Key
            $pre = sha1($ip.$hash.$host.date("Ymd"));
            $gen = sha1($pre.$key); // Generate License

            return $gen;
        }

        // Is the licence valid for this hsot and ip
        function check_licence($licence)
        {
            $host = $_SERVER['HTTP_HOST'];
            $host = str_replace('www.', '', $host);
            $ip = $_SERVER['SERVER_ADDR'];
            if (!$ip) { $ip = $_SERVER['LOCAL_ADDR']; }
            if (!$ip) { $ip = gethostbyname($host); }
            if ($ip == '0.0.0.0') { $ip = gethostbyname($host); } // Lighthttpd Fix

            if ($licence)
            {
                $lmethod = 'Code';
                //$license = trim($licence);
                $hash = 'pq8bytpq34ytnq3ptyq347ty0q34t734'; // Hash
                $key = '3hvf8734hfh3f2734f0273f2043fn7nf'; // Key
                $pre = sha1($ip.$hash.$host);
                $gen = sha1($pre.$key); // Generate License

                if ($gen == $licence) { return 1; }

                return 0;
            }
        }


        function fetch_licence_from_server($licence, $fields)
        {
            return 1;
            // Fetch License From Server
            if ($licence)
            {
                // Start CURL Fetch
                $lmethod = 'Curl';
                if (function_exists('curl_version'))
                {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'http://www.status2k.com/verify.php');
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Status2k');
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
                    $value = curl_exec($ch);
                    curl_close($ch);
                }
                else
                {
                    // If No Curl Use File
                    $lmethod = 'File Get Contents';
                    $value = file_get_contents("http://www.status2k.com/verify.php?host=".$sdata['host']."&ip=".$sdata['ip']);
                    if (!$value)
                    {
                        $lmethod = 'File'; $value = implode('',
                            file("http://www.status2k.com/verify.php?host=".$sdata['host']."&ip=".$sdata['ip']));
                    }
                }

                if ($value == '1') { return 0; };
                if ($value == '2') { return 1; };
                return -1;
            }
        }
    }
?>
