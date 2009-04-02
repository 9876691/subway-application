<?php
    include_once "base_controller.php";

    uses('sanitize');

    class LicenceController extends AppController
    {
        var $uses = array('Configuration');
        var $layout = 'marketing';
        var $helpers = array('Html','Javascript','Ajax','Form');

        function licence()
        {
            $sdata = array();
            $sdata['host'] = $_SERVER['HTTP_HOST'];
            $sdata['host'] = str_replace('www.', '', $sdata['host']);
            $sdata['ip'] = $_SERVER['SERVER_ADDR'];
            if (!$sdata['ip']) { $sdata['ip'] = $_SERVER['LOCAL_ADDR']; }
            if (!$sdata['ip']) { $sdata['ip'] = gethostbyname($sdata['host']); }
            if ($sdata['ip'] == '0.0.0.0') { $sdata['ip'] = gethostbyname($sdata['host']); } // Lighthttpd Fix

            $this->set('sdata', $sdata);

            // Validate the licence
            if(!empty($this->params['form']))
            {
                $this->Configuration->query("DELETE FROM ". BaseController::get_db_prefix().
                  "configurations WHERE name = 'licence'");
                $this->Configuration->query("INSERT INTO ". BaseController::get_db_prefix().
                  "configurations (value, name) VALUES ('".
                    $this->params['form']['license']."','licence')");

                $this->redirect('/');
            }

        }
    }
?>
