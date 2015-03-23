<?php
    class Task
    {
        private $description;
        private $id;

        //setting a construct for Task class with arguments $description and $id
        function __construct($description, $id = null)
        {
            $this->description = $description;
            $this->id = $id;
            $this->category_id = $category_id;
        }
        //setting $new_id as an integer and equal to our $id
        function setId($new_id)
        {
            $this->id = (int) $new_id;
        }
        //setting $new_description as a string and equal to our $description
        function setDescription($new_description)
        {
            $this->description = (string) $new_description;
        }

        //getter for returning $id
        function getId()
        {
            return $this->id;
        }
        //getter for returning $description
        function getDescription()
        {
            return $this->description;
        }

        function save()
        {
            //setting variable using GLOBALS to call tasks table and insert data and return id.
            $statement = $GLOBALS['DB']->query("INSERT INTO tasks (description) VALUES
                ('{$this->getDescription()}') RETURNING id;");
            //use PDO fetch method to get id and put into assoc array
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            //setting setId to result
            $this->setId($result['id']);
        }


        static function getAll()
        {
            //calling Global variable DB, select all from tasks table, put into $returned_tasks
            $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
            //creating empty array
            $tasks = array();
            //foreach loop
            foreach($returned_tasks as $task) {
                //getting description and id from each task
                $description = $task['description'];
                $id = $task['id'];
                //instantiate new Task with description and id from above in $new_task
                $new_task = new Task($description, $id);
                //push everything into tasks array
                array_push($tasks, $new_task);
            }
            //return tasks array
            return $tasks;
        }

        static function deleteAll()
        {
            //use global function to call DB, then exec to run a SQL statement to delete ALL from tasks table
            $GLOBALS['DB']->exec("DELETE FROM tasks *;");
        }

        static function find($search_id)//giving find method search_id as an argument
        {
            $found_task = null;
            //use getAll method on Task class, save to tasks variable
            $tasks = Task::getAll();
            foreach($tasks as $task) {
                $task_id = $task->getId();//getting all task id's = new task_id variable
                if ($task_id == $search_id) {//if they are equal...
                    $found_task = $task;//set task == new variable found_task
                }
            }
            return $found_task;//return found task
        }
    }
?>
