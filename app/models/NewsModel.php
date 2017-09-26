<?php
/**
 * Новости сайта и комментарии к соответствующим новостям
 * @author Vika
 *
 */
class NewsModel
{    
    public $records = [];
    public $page;
    public $pages;
    private $id;
    public $records1 = [];
    public $comments = [];
    private $title_new;
    private $catagory_new;
    private $text_new;
    public $db;
    
    /**
     * Подключение функций, необходимых для проверки данных
     */
    use Check;
    
    public function __construct()
    {       
        
        $this->db = DataBase::getInstance();
                
        /**
         * Пагинация
         * Параметр, передаваемый в URL и определяющий номер страницы с новостями, 
         * на которой находится $limit новостей
         * @var integer
         */
        $this->page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        
        /**
         * Максимальное число новостей на одной странице
         * @var integer
         */
        $limit = 4;
        
        /**
         * Параметр, определяющий на какое количество новостей нужно сместиться вниз при выборке новостей
         * @var integer
         */
        $offset = ($this->page - 1)*$limit;
        
        /**
         * Количество всех существующих новостей
         * @var integer
         */
        $pages_result = $this->db->query("SELECT * FROM news")->count();

        
        /**
         * Параметр, определяющий количество страниц с новостями, на каждой из которых
         * находится $limit новостей
         * @var integer
         */
        $this->pages = ceil($pages_result/$limit);
            
        /**
         * Выборка всех существующих новостей и соответствующих им комментариев
         */
        if ($result = $this->db->query("SELECT news.* , COUNT(comments.id) AS comm
                                        FROM news
                                        LEFT JOIN comments
                                        ON news.id = comments.entry_id
                                        GROUP BY news.id
                                        ORDER BY date DESC
                                        LIMIT $offset, $limit")->results()) {

            foreach ($result as $v) {

                $row = [
                    'id' => $v->id,
                    'date' => $v->date,
                    'comm' => $v->comm,
                    'catagory' => $this->check_data($v->catagory),
                    'title' => $this->check_data($v->title),
                    'text' => $v->text,
                ];
                
                
                if (mb_strlen($v->text) > 150) {
                    $row['text'] =  mb_substr($this->check_data($v->text), 0, 147). '...';
                }
                
                $row['text'] = nl2br($row['text']);
                $this->records[] = $row;
                
            }
            
        }
        
        /**
         * Выборка отдельной новости по id, переданному в URL
         */
        if (isset($_GET['id'])) {
            
            $this->id = intval($_GET['id']);
            
            if ($result = $this->db->query("SELECT * FROM news
                                            WHERE id = $this->id
                                            LIMIT 1")->results()) {

                foreach ($result as $v) {
                    $row1 = [
                        'id' => $v->id,
                        'date' => $v->date,
                        'catagory' => $this->check_data($v->catagory),
                        'title' => $this->check_data($v->title),
                        'text' => nl2br($this->check_data($v->text)),
                    ];
     
                    $this->records1[] = $row1;
                }
                
            }
            
            /**
             * Выборка комментариев к отдельной новости
             */
            if ($result = $this->db->query("SELECT * FROM comments
                                            WHERE entry_id = $this->id
                                            ORDER BY date DESC
                                            LIMIT 10")->results()) {
                
                foreach ($result as $v) {

                    $row_com = [
                        'id' => $v->id,
                        'entry_id' => $v->entry_id,
                        'date' => $v->date,
                        'name' => $this->check_data($v->name),
                        'text' => nl2br($this->check_data($v->text)),
                    ];

                    $this->comments[] = $row_com;
                }
                
            }
                    
        }
        
        /**
         * Переадресация на страницу редактирования новости (для админа)
         */
        if (isset($_GET['act']) && $_GET['act'] == 'edit-news') {
            
            header('Location: /02/UltrafastLaser/public/editnews/editnewslaser?id='.$_GET['id']);                      
            
        }
        
        /**
         * Переадресация на страницу(скрипт) удаления новости (для админа)
         */
        if (isset($_GET['act']) && $_GET['act'] == 'remove-news') {
            
            header('Location: /02/UltrafastLaser/public/removenews?id='.$_GET['id']);
            
        }
        
        /**
         * Добавление новой новости (для админа)
         */
        if (isset($_GET['act']) && $_GET['act'] == 'add-news') {
            
            /**
             * Проверка данных, переданных через форму методом POST
             * @var mixed
             */
            $this->title_new = $this->check_data_in($_POST['title']);
            $this->catagory_new = $this->check_data_in($_POST['catagory']);
            $this->text_new = $this->check_data_in($_POST['text']);
            
            /**
             * Подготовленный запрос: вставка новой новости в таблицу news
             */
            if ($result = $this->db->query("INSERT INTO news (catagory, title, text, date)
                                            VALUES (?, ?, ?, NOW())",
                                            [$this->catagory_new, $this->title_new, $this->text_new])) {
                                                                    
                $result->results();
                                
                /**
                 * Переадресация на главную страницу с новостями
                 */
                header('Location: /02/UltrafastLaser/public/news/lasernews');
            }
        
        }
        
    }
    
}