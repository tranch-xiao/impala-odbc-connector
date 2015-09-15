<?php
return [
    'db' => [
        'dsn' => 'DSN=impala;',
        'user' => '',
        'password' => ''
    ],
    'util' => [
        'odbc_result_all' => function ($result_resource, $tableFeatures="") {
              $noFields = odbc_num_fields($result_resource);
              
              $table = "<table $tableFeatures>" . PHP_EOL;
              $table .= "<thead>" . PHP_EOL;
              $table .= "<tr>" . PHP_EOL;
              
              for ($i = 1; $i <= $noFields; $i++) {
                  $field = odbc_field_name($result_resource, $i);
                  $table .= "<th>$field</th>" . PHP_EOL;
              }
              
              $table .= '</tr>' . PHP_EOL;
              $table .= '</thead>' . PHP_EOL;
              
              $table .= '<tbody>' . PHP_EOL;
              while ($r = odbc_fetch_array($result_resource)) {
                  $table .= "<tr>" . PHP_EOL;
                  foreach ($r as $kolonne) {
                      $table .= "<td>$kolonne</td>" . PHP_EOL;
                  }
                  $table .= "</tr>" . PHP_EOL;
              }
              $table .= '</tbody>' . PHP_EOL;
              $table .= "</table>" . PHP_EOL;
              
              return $table;
        }
    ],
    'language' => 'zh_CN'
];