<?php
session_start();
$config = require __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
$util = $config['util'];
$args = ['query' => FILTER_UNSAFE_RAW];
$post = filter_var_array($_POST, $args);
$odbc = [];
$result = null;
$_SESSION['__flash'] = [];
$result_plugins = [];

foreach ($config['plugins'] as $plugin) {
    $plugin_file = implode(DIRECTORY_SEPARATOR, [__DIR__, 'plugins', "$plugin.php"]);
    if (file_exists($plugin_file)) {
        $result_plugins[$plugin] = require_once($plugin_file);
    }
}

$odbc['connection_id'] = @odbc_connect($config['db']['dsn'], $config['db']['user'], $config['db']['password']);
if ($odbc['connection_id']) {
    if ($post['query']) {
        $odbc['result_id'] = @odbc_exec($odbc['connection_id'], $post['query']);
        if ($odbc['result_id']) {   
            $result = call_user_func_array($util['odbc_result_all'], [
                $odbc['result_id'], 
                'class="table table-striped table-bordered"'
            ]);
        } else {
            $_SESSION['__flash']['error'] = odbc_errormsg($odbc['connection_id']);
        }
    }
} else {
    $_SESSION['__flash']['error'] = _("Connection could not be established: ") . odbc_errormsg();
}
odbc_close($odbc['connection_id']);
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= _('Impala ODBC Connector') ?></title>
    <link rel="stylesheet" href="assets/components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="page-header"><?= _('Impala ODBC Connector') ?></h1>
                
                <?php foreach ($_SESSION['__flash'] as $type => $message): ?>
                    <div class="alert alert-<?= str_replace('error', 'danger', $type) ?>" role="alert"><?= $message ?></div>
                <?php endforeach ?>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <h3 class="panel-title"><?= _('Query Editor') ?></h3>
                          </div>
                          <div class="panel-body">
                              <form class="form" id="query_form" method="POST">
                                  <div class="form-group">
                                      <textarea name="query" class="form-control hide"><?= $post['query'] ?></textarea>
                                      <pre id="editor"></pre>
                                  </div>
                                  <button type="submit"
                                      class="btn btn-primary"
                                      id="execute-btn"
                                      title="<?= _('Ctrl + Enter or F9') ?>"><?= _('Execute') ?></button>
                              </form>
                          </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                              <h3 class="panel-title"><?= _('Query History') ?></h3>
                            </div>
                            <div class="panel-body">
                                <ol id="query_statements" reversed="reversed" >
                                    <?php foreach ($result_plugins['QueryHistory'] as $row): ?>
                                        <li data-statement="<?= htmlentities($row['query']) ?>">
                                            <a href="#editor" title="<?= htmlentities($row['query']) ?>">
                                                <code><?= substr(str_replace(["\r", "\n"], ' ', $row['query']), 0, 35) ?></code>
                                            </a>
                                        </li>
                                    <?php endforeach ?>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xs-12">
                        <div class="result-container">
                            <div class="query-result">
                                <?= $result ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="assets/components/jquery/dist/jquery.js"></script>
    <script src="assets/components/ace-builds/src/ace.js"></script>
    <script src="assets/components/jquery.floatThead/dist/jquery.floatThead.js"></script>
    <script>
    (function($) {
        var editor = ace.edit("editor"),
            textarea = $('textarea[name="query"]'),
            query_form = $('#query_form');
        editor.getSession().setMode('ace/mode/impala');
        editor.getSession().setValue(textarea.val());
        editor.getSession().setTabSize(2);
        editor.getSession().on('change', function() {
            textarea.val(editor.getSession().getValue());
        });
        editor.commands.addCommand({
            name: "execute",
            exec: function() {
                query_form.submit();
            },
            bindKey: {mac: "cmd-enter", win: "ctrl-enter"}
        });
        
        $('.query-result .table').floatThead({
            scrollContainer: function($table){
                return $table.closest('.query-result');
            }
        });
        
        $('#query_statements').find('li').on('click', function () {
            editor.insert($(this).data('statement'));
        });
    })(jQuery);
    </script>
</body>
</html>
