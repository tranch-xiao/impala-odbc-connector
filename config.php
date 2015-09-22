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
            $noRows = 0;
            $noAffectedRows = odbc_num_rows($resultResource);
            $table = "<table $tableFeatures>" . PHP_EOL;
            
            $table .= "<thead>" . PHP_EOL;
            $table .= "<tr>" . PHP_EOL;
            
            if ($noFields > 0) {
                for ($i = 1; $i <= $noFields; $i++) {
                    $field = odbc_field_name($resultResource, $i);
                    $table .= "<th>$field</th>" . PHP_EOL;
                }
            } else {
                $table .= sprintf("<th>%s</th>\n", _('Result'));
            }
            
            $table .= '</tr>' . PHP_EOL;
            $table .= '</thead>' . PHP_EOL;
            
            $table .= '<tbody>' . PHP_EOL;
            
            while ($r = @odbc_fetch_array($resultResource)) {
                $table .= "<tr>" . PHP_EOL;
                foreach ($r as $kolonne) {
                    $table .= "<td>$kolonne</td>" . PHP_EOL;
                }
                $noRows ++;
                $table .= "</tr>" . PHP_EOL;
            }
            
            if ($noRows == 0) {
                $table .= sprintf("<tr><td colspan='%d'>%s</td></tr>\n", $noFields, _('No data result.'));
            } else {
                $table .= '<tfoot>' . PHP_EOL;
                $table .= '<tr>' . PHP_EOL;
                $table .= sprintf("<th colspan='%d'> %s: %d</th>\n", $noFields, _('Number of rows'), $noRows);
            
                $table .= '</tr>' . PHP_EOL;
                $table .= '</tfoot>' . PHP_EOL;
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
