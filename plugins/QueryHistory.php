<?php
return call_user_func(function () use ($post) {
    $db = new PDO(sprintf('sqlite:%s.sqlite', basename(__FILE__)));
    $query_create_talbe = 'CREATE TABLE IF NOT EXISTS query_history (id integer primary key autoincrement, query text, created_at datetime)';
    $query_add = 'INSERT INTO query_history (query, created_at) VALUES (?, ?)';
    $query_get_last = 'SELECT query FROM query_history ORDER BY created_at DESC LIMIT 10';
    
    $db->exec($query_create_talbe);
    if ($post['query']) {
        $stmt = $db->prepare($query_add);
        $stmt->execute([$post['query'], date('Y-m-d H:i:s')]);
    }
    $stmt = $db->prepare($query_get_last);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
});
