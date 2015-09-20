<?php
return [
    'db' => [
        'dsn' => 'DSN=impala;',
        'user' => '',
        'password' => ''
    ],
    'util' => [
        'odbc_result_all' => function ($resultResource, $tableFeatures="") {
            $noFields = odbc_num_fields($resultResource);
            
            $table = "<table $tableFeatures>" . PHP_EOL;
            $table .= "<thead>" . PHP_EOL;
            $table .= "<tr>" . PHP_EOL;
            
            for ($i = 1; $i <= $noFields; $i++) {
                $field = odbc_field_name($resultResource, $i);
                $table .= "<th>$field</th>" . PHP_EOL;
            }
            
            $table .= '</tr>' . PHP_EOL;
            $table .= '</thead>' . PHP_EOL;
            
            $table .= '<tbody>' . PHP_EOL;
            $tableWithoutData = $table;
            while ($r = odbc_fetch_array($resultResource)) {
                $table .= "<tr>" . PHP_EOL;
                foreach ($r as $kolonne) {
                    $table .= "<td>$kolonne</td>" . PHP_EOL;
                }
                $table .= "</tr>" . PHP_EOL;
            }
            if ($table == $tableWithoutData) {
                $table .= sprintf("<tr><td colspan='%d'>%s</td></tr>", $noFields, _('No data result.'));
            }
            $table .= '</tbody>' . PHP_EOL;
            $table .= "</table>" . PHP_EOL;
              
            return $table;
        }
    ],
    'language' => 'zh_CN',
    'plugins' => [
        'QueryHistory'
    ]
];