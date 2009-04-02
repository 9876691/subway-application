<?php
    include_once "base_controller.php";
    include_once "notes_controller.php";
    include_once "admin_controller.php";

    uses('sanitize');

    class TasksController extends BaseController
    {
        var $uses = array('Task', 'Group', 'Person', 'Lru');
        var $helpers = array('Html','Javascript','Ajax');
        var $components = array('RequestHandler');

        function index()
        {
            $this->set('tab', 'tasks');

            $this->set('stand_alone', 'true');

            TasksController::generate_task_list($this);
        }

        function edit($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->task_secure_read($id);
            $this->Task->id = $id;
            $this->data = $this->Task->read();

            if(! empty($this->data['Task']['associated_with_id']))
            {
                $this->set('association',
                    $this->data['Task']['associated_with_id']);
                $this->set('type',
                    $this->data['Task']['association_type']);
            }

            $this->set('due_date',
                strtotime($this->data['Task']['due_date']) * 1000);

            $time = $this->data['Task']['due_time'];
            $this->set('hours', substr($time, 0, 2));
            $this->set('minutes', substr($time, 3, 5));
            $this->set('task_id', $id);

            if(! $this->RequestHandler->isAjax())
            {
                $this->set('tab', 'tasks');
            }
        }

        function update($id)
        {
            $mr_clean = new Sanitize();
            $mr_clean->paranoid($id);
            $this->data = $mr_clean->clean($this->data);
            $this->params = $mr_clean->clean($this->params);

            if(! empty($this->data['Task']['subject']))
            {
                $this->task_secure_read($id);
                $this->Task->id = $id;
                $this->Task->read();
                $this->populate_task_form();
                if ($this->Task->save($this->data))
                {

                }
            }

            if(! $this->RequestHandler->isAjax())
            {
                $this->redirect('/tasks');
                exit;
            }

            if(! empty($this->params['form']['associated_with_id']))
            TasksController::generate_task_list($this,
                $this->params['form']['associated_with_id'],
                $this->params['form']['association_type']);
            else
            TasksController::generate_task_list($this);
        }

        function delete($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->Task->query("DELETE FROM ". BaseController::get_db_prefix() . "tasks WHERE id = {$id}" .
                        " AND creator_id IN (" . implode(",",
                    AdminController::extract_users($this,
                        $this->logged_on_group_id())) . ")");
        }

        function complete($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->Task->query(
                        "UPDATE ". BaseController::get_db_prefix() . "tasks SET completed = NOW() WHERE id = {$id}" .
                        " AND creator_id IN (" . implode(",",
                    AdminController::extract_users($this,
                        $this->logged_on_group_id())) . ")");

            $this->set('tasks', $this->Task->findAll(
                    array('assigned_id' => $this->logged_on_user_id(),
                'completed' => 0)));
        }

        function addtask()
        {
            if($this->RequestHandler->isAjax())
              $this->layout = "ajax";

            $mr_clean = new Sanitize();
            $this->data = $mr_clean->clean($this->data);
            $this->params = $mr_clean->clean($this->params);

            if(! empty($this->data['Task']['subject']))
            {
                $this->populate_task_form();
                $this->Task->save($this->data);
            }

            if(! empty($this->params['form']['associated_with_id']))
                TasksController::generate_task_list($this,
                    $this->params['form']['associated_with_id'],
                    $this->params['form']['association_type']);
            else
                TasksController::generate_task_list($this);

            if(! $this->RequestHandler->isAjax())
            {
                $this->redirect('/tasks');
            }
        }

        private function populate_task_form()
        {
            $this->data['Task']['creator_id'] =
            $this->logged_on_user_id();
            $this->data['Task']['assigned_id'] =
            $this->logged_on_user_id();
            $this->data['Task']['due_date'] =
            date('Y-m-d', $this->params['form']['due_date'] / 1000);
            if(!empty($this->params['form']['time_check']))
            $this->data['Task']['due_time'] = date('H:i:s',
                ($this->params['form']['hours'] - 1) * 3600
                + $this->params['form']['minutes'] * 60);

            // Association
            if(! empty($this->params['form']['associated_with_id']))
            {
                $this->data['Task']['associated_with_id'] =
                $this->params['form']['associated_with_id'];
                $this->data['Task']['association_type'] =
                $this->params['form']['association_type'];
            }
        }

        public static function generate_task_list(
            $the_view, $id = -1, $type = 0)
        {
            $par = 'assigned_id = ' .
              $the_view->logged_on_user_id() . ' AND completed IS NULL';

            if($id != -1)
            {
                $par .= ' AND associated_with_id = ' . $id;
                $par .= ' AND association_type = ' . $type;
            }

            $the_view->set('overdue',
                TasksController::load_associations($the_view->Task,
                    $the_view->Task->findAll($par . ' AND due_date < \'' .
                        date('Y-m-d', strtotime("today")) . '\'')));

            $the_view->set('today',
                TasksController::load_associations($the_view->Task,
                    $the_view->Task->findAll($par . ' AND due_date = \'' .
                        date('Y-m-d', strtotime("today")) . '\'')));

            $the_view->set('later',
                TasksController::load_associations($the_view->Task,
                    $the_view->Task->findAll($par . ' AND due_date > \'' .
                        date('Y-m-d', strtotime("today")) . '\'')));
        }

        public static function load_associations($model, $tasks)
        {
            foreach($tasks as $key => $task)
            {
                if(! empty($task['Task']['associated_with_id']))
                {
                    $task['Task']['associated_url'] =
                    NotesController::link_from_association(
                        $task['Task']['associated_with_id'],
                        $task['Task']['association_type']);
                    $task['Task']['associated_name'] =
                    NotesController::name_from_association($model,
                        $task['Task']['associated_with_id'],
                        $task['Task']['association_type']);

                    $tasks[$key] = $task;
                }
            }
            return $tasks;
        }

        private function task_secure_read($id)
        {
            $users = AdminController::extract_users($this,
                $this->logged_on_group_id());

            $obj = $this->Task->find(array('Task.id' => $id,
                        'Task.creator_id' => $users));

            if(empty($obj))
            {
                $this->redirect('/admin/security');
                exit;
            }
            return $obj;
        }
    }
?>
