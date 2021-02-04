<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Тестовое задание';
// s$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
<div class="container">

    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <h1><?= Html::encode($this->title) ?></h1>
            <h4>&nbsp;&nbsp;На вакансию frontend разработчик</h4>
        </div>       
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <br>
            <br>
            <br>
                <p>
                    Ссылка на <a href="https://back.test2task.ru/documentation/" target="_blank">Swagger</a> <b>REST API</b> приложения.
                    <br>
                    
                    Используя endpoints описанные в <a href="https://back.test2task.ru/documentation/" target="_blank">Swagger</a>:
                    <ul>
                        <li>https://back.test2task.ru/v1/auth/login</li>
                        <li>https://back.test2task.ru/v1/user</li>
                        <li>https://back.test2task.ru/v1/posts</li>
                        <li>И т.д. ...</li>
                    </ul>
                    
                    Frontend-часть приложения должна реализовывать следующий функционал:
                    <br>
                
                    <ol>
                        <li>
                            Необходимо разработать веб-интерфейс для регистрации и аутентификации(<b>Bearer</b>) пользователей. После аутентификации
                            пользователь попадает на страницу со списком постов и может производить над ними операции CRUD.
                            Всё взаимодействие с backend-частью приложения, осуществляется средствами <b>REST API</b> запросов.
                        </li>
                        <li>
                            У пользователя должна быть возможность поменять свои учетные данные и удалить профиль.
                        </li>
                        <li>
                            Результат нужно выложить на github<b>\</b>bitbucket <b>pages</b> и прислать ссылку на выполненное задание.
                        </li>
                    </ol>

                

                    <dl>
                        <dd>*<i>Для выполнения задания можно использовать js-фреймворк <b>VueJs</b> и всю его инфраструктуру.</i></dd>
                        <dd>*<i>Для верстки можно использовать <b>Bootstrap 4</b> либо аналогичные библиотеки <b>VueJS</b>.</i></dd>
                    </dl>

                    
                    
                </p>

        </div>
    
    </div>
    
</div>
    

    

    
</div>
