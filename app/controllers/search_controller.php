<?php
    include_once "base_controller.php";
    include_once "admin_controller.php";

    class SearchController extends BaseController
    {
        var $uses = array('Note', 'Lru', 'Person', 'Group');
        var $helpers = array('Html','Javascript','Textile');

        function index()
        {
            $this->set('tab', 'search');

            if(empty($this->params['url']['search']))
            $search = '';
            else
            $search = $this->params['url']['search'];

            $users = AdminController::extract_users_string($this,
                $this->logged_on_group_id());

            if($search)
            {
                $sql = "SELECT Note.*, Person.* FROM ". BaseController::get_db_prefix() . "notes AS Note " .
                        "LEFT JOIN ". BaseController::get_db_prefix() . "people AS Person ON " .
                        "(Note.creator_id = Person.id) WHERE Note.creator_id IN " .
                $users . " AND Note.text LIKE '%" . $search . "%'";

                $notes = $this->Note->query($sql);
            }

            if(!empty($notes))
            $this->set('notes', $notes);
            else
            $this->set('notes', array());

            $this->set('search', $search);
        }
    }
?>
