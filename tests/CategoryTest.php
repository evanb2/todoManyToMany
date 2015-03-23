<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Category.php";
    require_once "src/Task.php";

    $DB = new PDO('pgsql:host=localhost;dbname=to_do_test');

    class CategoryTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Category::deleteAll();
            Task::deleteAll();
        }

        function test_getName()
        {
            //Arrange
            $name = "Work stuff";
            $id = null;
            $test_category = new Category($name, $id);

            //Act
            $result = $test_category->getName();

            //Assert
            $this->assertEquals($name, $result);
        }

        function test_getId()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_category = new Category($name, $id);

            //Act
            $result = $test_category->getId();

            //Assert
            $this->assertEquals(1, $result);
        }

        function test_setId()
        {
            //Arrange
            $name = "Home stuff";
            $id = null;
            $test_category = new Category($name, $id);

            //Act
            $test_category->setId(2);


            //Assert
            $result = $test_category->getId();
            $this->assertEquals(2, $result);
        }

        function test_save()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            //Act
            $result = Category::getAll();


            //Assert
            $this->assertEquals($test_category, $result[0]);

        }


        function  test_getAll()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $name2 = 'Home stuff';
            $id2 = 2;
            $test_category = new Category($name, $id);
            $test_category->save();
            $test_Category2 = new Category($name2, $id2);
            $test_Category2->save();

            //Act
            $result = Category::getAll();


            //Assert
            $this->assertEquals([$test_category, $test_Category2], $result);

       }


       function test_deleteAll()
       {
           //Arrange
           $name = "Wash the dog";
           $id = null;
           $name2 = "Home stuff";
           $id2 = null;
           $test_category = new Category($name, $id);
           $test_category->save();
           $test_Category2 = new Category($name2, $id2);
           $test_Category2->save();


            //Act
            Category::deleteAll();
            $result = Category::getAll();


            //Assert
            $this->assertEquals([], $result);
       }

       function test_find()
       {
           //Arrange
           $name = "Wash the dog";
           $id = 1;
           $name2 = "Home stuff";
           $id2 = 2;
           $test_category = new Category($name, $id);
           $test_category->save();
           $test_Category2 = new Category($name2, $id2);
           $test_Category2->save();

           //Act
           $result = Category::find($test_category->getId());

           //Assert
           $this->assertEquals($test_category, $result);
       }




       function test_update()
       {
            //Arrange
            $name = "Work stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $new_name = "Home stuff";

            //Act
            $test_category->update($new_name);

            //Assert
            $this->assertEquals("Home stuff", $test_category->getName());
        }

        function test_delete()
        {
            //Arrange


            $name = "Work stuff";
            $id = 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "File reports";
            $id2 = 2;
            $test_task = new Task($description, $id2);
            $test_task->save();

            //Act
            $test_category->addTask($test_task);
            $test_category->delete();

            //Assert
            $this->assertEquals([], $test_task->getCategories());
        }

        function test_addTask()
        {
            //Arrange
            $name = "Stuff";
            $id = 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "File Reports";
            $id2 = 2;
            $test_task = new Task($description, $id2);
            $test_task->save();

            //Act
            $test_category->addTask($test_task);

            //Assert
            $this->assertEquals($test_category->getTasks(), [$test_task]);
        }

        function test_getTasks()
        {
            //Arrange
            $name = "Home stuff";
            $id = 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Wash the dog";
            $id2 = 2;
            $test_task = new Task($description, $id2);
            $test_task->save();

            $description2 = "Take out the trash";
            $id3 = 3;
            $test_task2 = new Task($description2, $id3);
            $test_task2->save();

            //Act
            $test_category->addTask($test_task);
            $test_category->addTask($test_task2);

            //Assert
            $this->assertEquals($test_category->getTasks(), [$test_task, $test_task2]);
        }

    }
?>
