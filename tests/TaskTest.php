<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Task.php";
    require_once "src/Category.php";

    $DB = new PDO('pgsql:host=localhost;dbname=to_do_test');

    class TaskTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Task::deleteAll();
            Category::deleteAll();
        }

        function test_getDescription()
        {
            //Arrange
            $description = "Do dishes";
            $test_task = new Task($description);

            //Act
            $result = $test_task->getDescription();

            //Assert
            $this->assertEquals($description, $result);
        }

        function test_setDescription()
        {
            //Arrange
            $description = "Do dishes";
            $test_task = new Task($description);

            //Act
            $test_task->setDescription("Drink coffee");
            $result = $test_task->getDescription();

            //Assert
            $this->assertEquals("Drink coffee", $result);
        }

        function test_getId()
        {
             //Arrange
             $description = "Wash the dog";
             $id = 1;
             $test_Task = new Task($description, $id);


             //Act
             $result = $test_Task->getId();

             // Assert
             $this->assertEquals(1, $result);

         }

         function test_setId()
         {
             //Arrange
             $description = "Wash the dog";
             $id = 1;
             $test_Task = new Task($description, $id);
             $test_Task->save();

             //Act
             $test_Task->setId(2);

             //Assert
             $result = $test_Task->getId();
             $this->assertEquals(2, $result);
         }

         function test_saveSetsId()
         {
             //Arrange
             $description = "Wash the dog";
             $id = 1;
             $test_task = new Task($description, $id);

             //Act
             $test_task->save();

             //Assert
             $this->assertEquals(true, is_numeric($test_task->getId()));
         }

        function test_save()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $test_Task = new Task($description, $id);


            //Act
            $test_Task->save();

            //Assert
            $result = Task::getAll();
            $this->assertEquals($test_Task, $result[0]);
        }

        function test_getAll()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $test_Task = new Task($description, $id);
            $test_Task->save();

            $description2 = "Water the lawn";
            $id2 = 2;
            $test_Task2 = new Task($description2, $id2);
            $test_Task2->save();

            //Act
            $result = Task::getAll();

            //Assert
            $this->assertEquals([$test_Task, $test_Task2], $result);
        }

      function test_deleteAll()
      {
          //Arrange
          $description = "Wash the dog";
          $id = 1;
          $test_Task = new Task($description, $id);
          $test_Task->save();

          $description2 = "Water the lawn";
          $id2 = 2;
          $test_Task2 = new Task($description2, $id2);
          $test_Task2->save();


          //Act
          Task::deleteAll();


          //Assert
          $result = Task::getAll();
          $this->assertEquals([], $result);

      }

       function test_find()
       {
           //Arrange
           $description = "Wash the dog";
           $id = 1;
           $test_Task = new Task($description, $id);
           $test_Task->save();

           $description2 = "Water the lawn";
           $id2 = 2;
           $test_Task2 = new Task($description2, $id2);
           $test_Task2->save();

           //Act
           $result = Task::find($test_Task->getId());

           //Assert
           $this->assertEquals($test_Task, $result);
       }

       function test_Update()
       {
           //Arrange
           $description = "Wash the dog";
           $id = 1;
           $test_task = new Task($description, $id);
           $test_task->save();

           $new_description = "Clean the dog";

           //Act
           $test_task->update($new_description);

           //Assert
           $this->assertEquals("Clean the dog", $test_task->getDescription());
       }

       function test_deleteTask()
       {
           //Arrange
           $description = "Wash the dog";
           $id = 1;
           $test_task = new Task($description, $id);
           $test_task->save();

           $description2 = "Water the lawn";
           $id2 = 2;
           $test_task2 = new Task($description2, $id2);
           $test_task2->save();

           //Act
           $test_task->delete();

           //Assert
           $this->assertEquals([$test_task2], Task::getAll());
       }

    }
?>
