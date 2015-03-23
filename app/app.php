<?php
    //linking to src file
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Task.php";
    require_once __DIR__."/../src/Category.php";

    $app = new Silex\Application();

    $DB = new PDO('pgsql:host=localhost;dbname=to_do');

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app['debug']=TRUE;

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.twig');
    });
    //READ all tasks
    $app->get("/tasks", function() use ($app) {
        return $app['twig']->render('tasks.twig', array('tasks' => Task::getAll()));
    });
    //READ all categories
    $app->get("/categories", function() use ($app) {
        return $app['twig']->render('categories.twig', array('categories' => Category::getAll()));
    });
    //READ singular category
    $app->get("/categories/{id}", function($id) use ($app) {
      $category = Category::find($id);
      return $app['twig']->render('category.twig', array('category' => $category,
        'tasks' => $category->getTasks(), 'all_tasks' => Task::getAll()));
    });
    //READ singular task
    $app->get("/tasks/{id}", function($id) use ($app) {
        $task = Task::find($id);
        return $app['twig']->render('task.twig', array('task' => $task, 'categories' =>
            $task->getCategories(), 'all_categories' => Category::getAll()));
    });
    //READ edit forms
    $app->get("/categories/{id}/edit", function($id) use ($app) {
        $category = Category::find($id);
        return $app['twig']->render('category_edit.html.twig', array('category' => $category));
    });

    $app->get("/tasks/{id}/edit", function($id) use ($app) {
        $task = Task::find($id);
        return $app['twig']->render('task_edit.twig', array('task' => $task));
    });
    //CREATE task
    $app->post("/categories", function() use ($app) {
        $category = new Category($_POST['name']);
        $category->save();
        return $app['twig']->render('categories.twig', array('categories' => Category::getAll()));
    });
    //CREATE category
    $app->post("/tasks", function() use ($app) {
        $description = $_POST['description'];
        $checkbox = $_POST['checkbox'];
        $task = new Task($description, $checkbox);
        $task->save();
        return $app['twig']->render('tasks.twig', array('tasks' => Task::getAll()));
    });
    //CREATE add tasks to category
    $app->post("/add_tasks", function() use ($app) {
        $category = Category::find($_POST['category_id']);
        $task = Task::find($_POST['task_id']);
        $category->addTask($task);
        return $app['twig']->render('category.twig', array('category' => $category, 'categories' => Category::getAll(),
            'tasks' => $category->getTasks(), 'all_tasks' => Task::getAll()));
    });
    //CREATE add categories to task
    $app->post("/add_categories", function() use ($app) {
        $category = Category::find($_POST['category_id']);
        $task = Task::find($_POST['task_id']);
        $task->addCategory($category);
        return $app['twig']->render('task.twig', array('task' => $task, 'tasks' => Task::getAll(),
            'categories' => $task->getCategories(), 'all_categories' => Category::getAll()));
    });
    //DELETE all tasks
    $app->post("/delete_tasks", function() use ($app) {
        Task::deleteAll();
        return $app['twig']->render('index.twig');
    });
    //DELETE all categories
    $app->post("/delete_categories", function() use ($app) {
        Category::deleteAll();
        return $app['twig']->render('index.twig');
    });
    //DELETE singular category
    $app->delete("/categories/{id}", function($id) use ($app) {
        $category = Category::find($id);
        $category->delete();
        return $app['twig']->render('index.twig', array('categories' => Category::getAll()));
    });
    //DELETE singular task
    $app->delete("/tasks/{id}", function($id) use ($app) {
        $task = Task::find($id);
        $task->delete();
        return $app['twig']->render('tasks.twig', array('tasks' => Task::getAll()));
    });
    //patch routes called from the edit form for each object
    $app->patch("/categories/{id}", function($id) use ($app) {
        $name = $_POST['name'];
        $category = Category::find($id);
        $category->update($name);
        return $app['twig']->render('category.twig', array('category' => $category, 'tasks' =>
            $category->getTasks(), 'all_tasks' => Task::getAll()));
    });

    $app->patch("/tasks/{id}", function($id) use ($app) {
        $task = Task::find($id);
        $task->delete();
        return $app['twig']->render('tasks.twig', array('task' => $task, 'categories' =>
            $task->getCategories(), 'all_categories' => Category::getAll()));
    });

    return $app;


?>
