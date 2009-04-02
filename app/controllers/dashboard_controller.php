<?php
    include_once "base_controller.php";
    include_once "tasks_controller.php";

    uses('sanitize');

    class DashboardController extends BaseController
    {
        var $uses = array('Person', 'Task', 'Note', 'Group', 'Company', 'Lru');
        var $helpers = array('Html','Javascript','Textile','Ajax');

        function index()
        {
            $this->set('tab', 'dashboard');

            $notes = $this->Note->findAll("(Note.created > NOW() - " .
                "INTERVAL 7 DAY OR Note.modified > NOW()" .
                " - INTERVAL 7 DAY) AND Note.creator_id IN (" . implode(",",
                    AdminController::extract_users($this,
                        $this->logged_on_group_id())) . ") LIMIT 10");

            $tasks = $this->Task->findAll("completed > NOW() - INTERVAL 7 DAY " .
                " AND Task.creator_id = " . $this->logged_on_account_id() .
                " LIMIT 10");

            if($notes == null)
              $notes = array();
            if($tasks == null)
              $tasks = array();

            $notes = $this->fill_notes($notes);
            $tasks = $this->fill_tasks($tasks);

            $tasks_notes = array_merge($notes, $tasks);

            usort($tasks_notes, array('DashboardController', 'cmp'));

            $this->set('tasks_notes', $tasks_notes);

            TasksController::generate_task_list($this);
        }

        function cmp($a, $b)
        {
            if(!empty($a['Task']))
            $a_date = strtotime($a['Task']['completed']);
            else
            $a_date = strtotime($a['Note']['created']);

            if(!empty($b['Task']))
            $b_date = strtotime($b['Task']['completed']);
            else
            $b_date = strtotime($b['Note']['created']);

            if ($a_date == $b_date) {
                return 0;
            }
            return ($a_date < $b_date) ? 1 : -1;
        }

        function fill_tasks($tasks)
        {
            $not = array();

            foreach($tasks as $task)
            {
                if(!empty($task['Task']['associated_with_id']))
                {
                    $task['Task']['associated_url'] =
                    NotesController::link_from_association(
                        $task['Task']['associated_with_id'],
                        $task['Task']['association_type']);

                    $task['Task']['associated_name'] =
                    NotesController::name_from_association($this->Person,
                        $task['Task']['associated_with_id'],
                        $task['Task']['association_type']);
                }

                array_push($not, $task);
            }
            return $not;
        }

        function fill_notes($notes)
        {
            $not = array();

            foreach($notes as $note)
            {
                $note['Note']['subject'] = 'Note by ' .
                  $note['Person']['first_name'] . ' ' .
                  $note['Person']['surname'];// . ' on ' .
                  //$note['Note']['created'];
                $length = 100;
                if(strlen($note['Note']['text']) < 100)
                  $length = strlen($note['Note']['text']);
                else
                  $note['Note']['shortened'] = true;

                $note['Note']['short_note'] = substr(
                    $note['Note']['text'], 0, $length);

                $note['Note']['associated_url'] =
                    NotesController::link_from_association(
                        $note['Note']['associated_with_id'],
                        $note['Note']['association_type']);

                $note['Note']['associated_name'] =
                NotesController::name_from_association($this->Person,
                    $note['Note']['associated_with_id'],
                    $note['Note']['association_type']);

                if($note['Note']['association_type'] == 0
                    or $note['Note']['association_type'] == 100)
                {
                  $ret = $this->Person->query(
                    "SELECT image_id FROM ". BaseController::get_db_prefix()
                    . "people AS people WHERE id = "
                    . $note['Note']['associated_with_id']);
                  $note['Note']['image_id'] = $ret[0]['people']['image_id'];
                }
                else if($note['Note']['association_type'] == 1
                    or $note['Note']['association_type'] == 101)
                {
                  $ret = $this->Company->query(
                    "SELECT image_id FROM ". BaseController::get_db_prefix()
                    . "companies AS companies WHERE id = "
                    . $note['Note']['associated_with_id']);
                  $note['Note']['image_id'] = $ret[0]['companies']['image_id'];
                }

                array_push($not, $note);
            }
            return $not;
        }

        function partial($id)
        {
            $mr_clean = new Sanitize();
            $this->params = $mr_clean->clean($this->params);

            if(empty($id))
            {
                $name = '';
                $this->set('people', array());
                $this->set('search_string', $name);
                $this->layout = "";
                return;
            }
            else
            $name = $id;

            $users = AdminController::extract_users_string($this,
                $this->logged_on_group_id());

            $people = $this->Person->findAll(
                        "(lower(surname) LIKE '{$name}%' "
                . "or lower(first_name) LIKE '{$name}%')" .
                " AND Person.creator_id IN " . $users);

            $this->set('people', $people);
            $this->set('search_string', $name);
            $this->layout = "";
        }
    }
?>
