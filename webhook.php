<?php
require_once "vendor/autoload.php";
use Twilio\TwiML\MessagingResponse;
use Twilio\Rest\Client;

$body = $_REQUEST['Body'];
$from = $_REQUEST['From'];

// Set the content-type to XML to send back TwiML from the PHP Helper Library
header("content-type: text/xml");
date_default_timezone_set('America/Los_Angeles');

$response = new MessagingResponse();

$database = mysqli_connect('hcm4e9frmbwfez47.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', 'i2mwrf74ip9zubek', 'p0yzpu5xyq4ybqjr', 'qt4nvdslegbdc6h6');

if (($body == 'PLAN') or ($body == 'Plan') or ($body == 'PLAN ') or ($body == 'Plan ')) {

    $select_query = "SELECT phone_number FROM user WHERE user.phone_number = $from";
    error_log("Executing query: " . $select_query);
    error_log("Mysql error for query: " . mysqli_error($database));
    $select_query_result = mysqli_query($database, $select_query);
    $user_rows = mysqli_num_rows($select_query_result);
    
    if ($user_rows == 0) {
        $response->message("Thank you for activating me. I am the PocketPlan Daily Task Bot. I will help you manage your daily tasks. Click this link: https://pocketplan2020.herokuapp.com/register_info.php?phoneNumber=$from to get started. My creator is Yashvardhan Khaitan.");
        print $response;
    } else {
        $response->message("It looks like you have already registered before. Reply REMIND to input your tasks.");
        print $response;
    }
}

if (($body == 'REMIND') or ($body == 'Remind') or ($body == 'REMIND ') or ($body == 'Remind ')) {

    $select_activation_status = "SELECT user.activated FROM user WHERE user.phone_number = $from";
    error_log("Executing query: " . $select_activation_status);
    error_log("Mysql error for query: " . mysqli_error($database));
    $select_activation_status_result = mysqli_query($database, $select_activation_status);
    $activated_rows = mysqli_fetch_assoc($select_activation_status_result);
    $activation = $activated_rows['activated'];

    if ($activation == 'Y') {
        $response->message("Click this link: https://pocketplan2020.herokuapp.com/daily_task.php?phoneNumber=$from to give me your daily tasks.");
        print $response;
    } else {
        $response->message("This command is invalid because you have not registered. Reply PLAN to register.");
        print $response;
    }
}

if (($body == 'COMMANDS') or ($body == 'Commands') or ($body == 'COMMANDS ') or ($body == 'Commands ')) {

    $response->message("Here are a list of commands:
1. PLAN - Register information
2. REMIND - Input tasks
3. TASKS - Request incoming tasks
4. COMMANDS - List commands
5. DELETE - Delete a task
6. FEEDBACK - Submit your feedback. Completely anonymous.
    ");
    print $response;

}

if (($body == 'FEEDBACK') or ($body == 'Feedback') or ($body == 'FEEDBACK ') or ($body == 'Feedback ')) {
    $response->message("Click this link: https://pocketplan2020.herokuapp.com/feedback.php to enter your feedback");
    print $response;
}

if (($body == 'TASKS') or ($body == 'Tasks') or ($body == 'TASKS ') or ($body == 'Tasks ')) {

    $get_user_id = "SELECT user.user_id FROM user WHERE user.phone_number = $from";
    error_log("Executing query: " . $get_user_id);
    error_log("Mysql error for query: " . mysqli_error($database));
    $get_user_id_result = mysqli_query($database, $get_user_id);
    $user_id_rows = mysqli_fetch_assoc($get_user_id_result);
    $user_id = $user_id_rows['user_id'];

    $select_activation_status1 = "SELECT user.activated FROM user WHERE user.phone_number = $from";
    error_log("Executing query: " . $select_activation_status1);
    error_log("Mysql error for query: " . mysqli_error($database));
    $select_activation_status_result1 = mysqli_query($database, $select_activation_status1);
    $user_activated_rows = mysqli_fetch_array($select_activation_status_result1);
    $activation1 = $user_activated_rows[0];

    error_log(print_r($user_activated_rows,true));

    if ($activation1 == 'Y') {

        $select_tasks = "SELECT task, reminder_time, date FROM user_task WHERE user_id = $user_id AND reminder_sent = 'N'";
        error_log("Executing query: " . $select_tasks);
        error_log("Mysql error for query: " . mysqli_error($database));
        $select_tasks_result = mysqli_query($database, $select_tasks);
        $number_of_tasks = mysqli_num_rows($select_tasks_result);

        if ($number_of_tasks == 0) {
            $response->message("You have no upcoming tasks. Reply REMIND to enter more tasks.");
            print $response;
        } else {

            $tasks_for_the_user = array();
            $tasks_reminder_time = array();
            $tasks_date = array();

            while ($task_rows = mysqli_fetch_array($select_tasks_result)) {
                error_log(print_r('row: ' . $task_rows['task'],true));
                $tasks_for_the_user[] = $task_rows['task'];
                $tasks_reminder_time[] = $task_rows['reminder_time'];
                $tasks_date[] = $task_rows['date'];
            }

            error_log(print_r($tasks_for_the_user,true));
            error_log(print_r($tasks_reminder_time,true));
            error_log(print_r($tasks_date,true));

            $formatted_tasks = '';
            for ($x = 0; $x < count($tasks_for_the_user); $x++) {
                $task_counter = $x + 1;

                if (count($tasks_for_the_user) < 2 ) {
                    if (date('m/d', $tasks_date[$x]) == date('m/d')) {
                        $formatted_tasks = 'Task: ' . $tasks_for_the_user[$x] . ' at ' . date('h:i A', strtotime($tasks_reminder_time[$x])) . ' (Today)';
                    } elseif (date('m/d', $tasks_date[$x]) == date('m/d',strtotime("tomorrow"))) {
                        $formatted_tasks = 'Task: ' . $tasks_for_the_user[$x] . ' at ' . date('h:i A', strtotime($tasks_reminder_time[$x])) . ' (Tomorrow)';
                    } else {
                        $formatted_tasks = 'Task: ' . $tasks_for_the_user[$x] . ' at ' . date('h:i A', strtotime($tasks_reminder_time[$x])) . ' on ' . date('m/d', $tasks_date[$x]);
                    }
                } else {
                    if (end($tasks_for_the_user) == $tasks_for_the_user[$x]) {
                        if (date('m/d', $tasks_date[$x]) == date("m/d")) {
                            $formatted_tasks .= 'Task ' . $task_counter . ': ' . $tasks_for_the_user[$x] . ' at ' . date('h:i A', strtotime($tasks_reminder_time[$x])) . ' (Today)';
                        } elseif (date('m/d', $tasks_date[$x]) == date('m/d',strtotime("tomorrow"))) {
                            $formatted_tasks .= 'Task ' . $task_counter . ': ' . $tasks_for_the_user[$x] . ' at ' . date('h:i A', strtotime($tasks_reminder_time[$x])) . ' (Tomorrow)';
                        } else {
                            $formatted_tasks .= 'Task ' . $task_counter . ': ' . $tasks_for_the_user[$x] . ' at ' . date('h:i A', strtotime($tasks_reminder_time[$x])) . ' on ' . date('m/d', $tasks_date[$x]);
                        }
                    } else {
                        if (date('m/d', $tasks_date[$x]) == date('m/d')) {
                            $formatted_tasks .= 'Task ' . $task_counter . ': ' . $tasks_for_the_user[$x] . ' at ' . date('h:i A', strtotime($tasks_reminder_time[$x])) . ' (Today)' . ', ';
                        } elseif (date('m/d', $tasks_date[$x]) == date('m/d',strtotime("tomorrow"))) { 
                            $formatted_tasks .= 'Task ' . $task_counter . ': ' . $tasks_for_the_user[$x] . ' at ' . date('h:i A', strtotime($tasks_reminder_time[$x])) . ' (Tomorrow)' . ', ';
                        } else {
                            $formatted_tasks .= 'Task ' . $task_counter . ': ' . $tasks_for_the_user[$x] . ' at ' . date('h:i A', strtotime($tasks_reminder_time[$x])) . ' on ' . date('m/d', $tasks_date[$x]) . ', ';
                        }
                    }
                }
            }

            error_log(print_r($formatted_tasks,true));

            $response->message("Here are your upcoming tasks: 

${formatted_tasks}");
            print $response;
        }
    } else {
        $response->message("This command is invalid because you have not registered. Reply PLAN to register.");
        print $response;
    }
}

if (($body == 'DELETE') or ($body == 'Delete') or ($body == 'DELETE ') or ($body == 'Delete ')) {
    $response->message("Click this link: https://pocketplan2020.herokuapp.com/delete_tasks.php?phoneNumber=$from to delete any incoming tasks.");
    print $response;
}

?>