<?php

header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Task.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_json = json_decode(file_get_contents("php://input"));
    $databse = new Database();
    $db = $databse->connect();
    $task = new Task($db);
    if ($post_json->name != '') {
        $result = $task->insert($post_json);

        $tasks_array = array();
        $tasks_array['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $task_item = array(
                'id' => $id,
                'taskname' => $post_json->name,
            );
            array_push($tasks_array['data'], $task_item);
        }
        echo json_encode($tasks_array);
    } elseif ($post_json->id != ''){
        $result = $task->update($post_json);
        echo json_encode($result);		
	}
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $post_json = json_decode(file_get_contents("php://input"));
    $databse = new Database();
    $db = $databse->connect();
    $task = new Task($db);
    if ($post_json->id != '') {
		$result = $task->delete($post_json);
		echo json_encode($result);
    }
} else {
    $queries = array();
    parse_str($_SERVER['QUERY_STRING'], $queries);
    $databse = new Database();
    $db = $databse->connect();
    $task = new Task($db);
    if (array_key_exists('id', $queries) or array_key_exists('user', $queries)) {
        $result = $task->read_single($queries['id']);
    } else {
        $result = $task->read();
    }
    $num = $result->rowCount();

    if ($num > 0) {
        $tasks_array = array();
        $tasks_array['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $task_item = array(
				'id' => $id,
				'completed' => $completed,
                'category' => $categoryname,
                'taskname' => $taskname,
            );

            array_push($tasks_array['data'], $task_item);
        }
        echo json_encode($tasks_array);
    } else {
        echo json_encode(array('message' => 'no tasks found'));
    }
}
