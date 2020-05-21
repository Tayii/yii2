<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        This is the About page. You may modify the following file to customize its content:
    </p>

<script>
var conn = new WebSocket('ws://localhost:8080');
    conn.onmessage = function(e) {
        console.log('Response:' + e.data);
    };
    conn.onopen = function(e) {
        console.log("Connection established!");
        console.log('Hey!');
        conn.send(JSON.stringify({
"auth_key": "4rmq8ACYIyhE9ZPZtpzW6mQP2jOZ3teR",
"controller": "user",
"action": "index"
}));
        conn.send(JSON.stringify({
"auth_key": "4rmq8ACYIyhE9ZPZtpzW6mQP2jOZ3teR",
"controller": "user",
"action": "view",
"id": "1"
}));
        conn.send(JSON.stringify({
"auth_key": "4rmq8ACYIyhE9ZPZtpzW6mQP2jOZ3teR",
"controller": "user",
"action": "update",
"id": "1",
"password": "adminadmin"
}));
    };
</script>

    <code><?= __FILE__ ?></code>
</div>
