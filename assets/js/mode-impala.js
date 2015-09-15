define("ace/mode/sql_highlight_rules",["require","exports","module","ace/lib/oop","ace/mode/text_highlight_rules"], function(require, exports, module) {
    "use strict";

    var oop = require("../lib/oop");
    var TextHighlightRules = require("./text_highlight_rules").TextHighlightRules;

    var SqlHighlightRules = function() {

        var keywords = (
        "add|aggregate|all|alter|and|api_version|as|asc|avro|between|bigint|binary|boolean|by|cached|case|cast|change|char|class|close_fn|column|columns|comment|compute|create|cross|data|database|databases|date|"+
        "datetime|decimal|delimited|desc|describe|distinct|div|double|drop|else|end|escaped|exists|explain|external|false|fields|fileformat|finalize_fn|first|float|format|formatted|from|full|function|functions|"+
        "group|having|if|in|incremental|init_fn|inner|inpath|insert|int|integer|intermediate|interval|into|invalidate|is|join|last|left|like|limit|lines|load|location|merge_fn|metadata|not|null|nulls|offset|on|"+
        "or|order|outer|overwrite|parquet|parquetfile|partition|partitioned|partitions|prepare_fn|produced|rcfile|real|refresh|regexp|rename|replace|returns|right|rlike|row|schema|schemas|select|semi|sequencefile|"+
        "serdeproperties|serialize_fn|set|show|smallint|stats|stored|straight_join|string|symbol|table|tables|tblproperties|terminated|textfile|then|timestamp|tinyint|to|true|uncached|union|update_fn|use|using|"+
        "values|view|when|where|with"
        );

        var builtinConstants = (
            "true|false"
        );

        var builtinFunctions = (
        "avg|count|first|last|max|min|sum|ucase|lcase|mid|len|round|rank|now|format|" +
        "coalesce|ifnull|isnull|nv|to_date|abs|acos|asin|atan|bin|ceil|conv|cos|" +
        "nullifzero|nvl|zeroifnull" +
        "ascii|char_length|concat|concat_ws|find_in_set|group_concat|initcap|instr|" +
        "length|locate|lower|lpad|ltrim|parse_url|regexp_extract|regexp_replace|repeat|" +
        "reverse|rpad|rtrim|space|strleft|strright|substr|translate|trim|upper" +
        "degrees|e|exp|floor|fmod|fnv_hash|greatest|hex|is_inf|is_nan|least|ln|log|" +
        "max_int|min_int|negative|pi|pmod|positive|pow|precision|quotient|radians|" +
        "rand|round|scale|sign|sin|sqrt|tan|unhex|add_months|adddate|current_timestamp|" +
        "date_add|date_part|date_sub|datediff|day|dayname|dayofweek|dayofyear|days_add|" +
        "days_sub|extract|from_unixtime|from_utc_timestamp|hour|hours_add|hours_sub|" +
        "microseconds_add|microseconds_sub|milliseconds_add|milliseconds_sub|minute|" +
        "minutes_add|minutes_sub|month|months_add|months_sub|nanoseconds_add|" +
        "nanoseconds_sub|now|second|seconds_add|seconds_sub|subdate|to_date|" +
        "to_utc_timestamp|trunc|unix_timestamp|weekofyear|weeks_add|weeks_sub|year|years_add|years_sub"
        );

        var dataTypes = (
            "bigint|boolean|char|decimal|double|float|int|real|smallint|string|timestamp|tinyint|varchar"
        );

        var keywordMapper = this.createKeywordMapper({
            "support.function": builtinFunctions,
            "keyword": keywords,
            "constant.language": builtinConstants,
            "storage.type": dataTypes
        }, "identifier", true);

        this.$rules = {
            "start" : [ {
                token : "comment",
                regex : "--.*$"
            },  {
                token : "comment",
                start : "/\\*",
                end : "\\*/"
            }, {
                token : "string",           // " string
                regex : '".*?"'
            }, {
                token : "string",           // ' string
                regex : "'.*?'"
            }, {
                token : "text",             // ` string
                regex : "`.*?`"
            }, {
                token : "constant.numeric", // float
                regex : "[+-]?\\d+(?:(?:\\.\\d*)?(?:[eE][+-]?\\d+)?)?\\b"
            }, {
                token : keywordMapper,
                regex : "[a-zA-Z_$][a-zA-Z0-9_$]*\\b"
            }, {
                token : "keyword.operator",
                regex : "\\+|\\-|\\/|\\/\\/|%|<@>|@>|<@|&|\\^|~|<|>|<=|=>|==|!=|<>|="
            }, {
                token : "paren.lparen",
                regex : "[\\(]"
            }, {
                token : "paren.rparen",
                regex : "[\\)]"
            }, {
                token : "text",
                regex : "\\s+"
            } ]
        };
        this.normalizeRules();
    };

    oop.inherits(SqlHighlightRules, TextHighlightRules);

    exports.SqlHighlightRules = SqlHighlightRules;
});

define("ace/mode/impala",["require","exports","module","ace/lib/oop","ace/mode/text","ace/mode/sql_highlight_rules","ace/range"], function(require, exports, module) {
    "use strict";

    var oop = require("../lib/oop");
    var TextMode = require("./text").Mode;
    var SqlHighlightRules = require("./sql_highlight_rules").SqlHighlightRules;
    var Range = require("../range").Range;

    var Mode = function() {
        this.HighlightRules = SqlHighlightRules;
    };
    oop.inherits(Mode, TextMode);

    (function() {

        this.lineCommentStart = "--";

        this.$id = "ace/mode/sql";
    }).call(Mode.prototype);

    exports.Mode = Mode;

});
