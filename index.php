<?php
//$getcontent = iconv("gb2312", "utf-8",$html);


ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; GreenBrowser)');
ini_set('max_execution_time', '0');

$base = 'https://www.qu.la/book/19434/';
$start = '10533547.html';

//$content_grep = '/&nbsp;&nbsp;&nbsp;&nbsp;(.*)<br\/>/';
$content_grep = '/<div id="content">(.*)<br\/>/sS';

$next_grep = '/<a id="pager_next" href=\"(\d+\.html)\" target="_top" class="next">下一章<\/a>/';


$next = $start;
$file_name = '小农民大明星.txt';

while($next) {
    echo 'getting ' . $next . PHP_EOL;
    $result = file_get_contents($base . $next);

    preg_match_all($content_grep, $result, $match);

    $isTitle = true;
    $content = "";
    foreach($match[1] as $line) {
        $line   = str_replace("<br/>", '', $line);
        $line   = str_replace("&nbsp;", '', $line);
        if($isTitle) {
            $content = $line . PHP_EOL . PHP_EOL;
            $isTitle = false;
        } else {
            $content .= '        ' . $line . PHP_EOL . PHP_EOL;
        }
    }

    $file = fopen($file_name, 'a');
    echo 'write length: ' . strlen($content) . PHP_EOL;
    fwrite($file, $content);
    fclose($file);

    echo '.';

    preg_match($next_grep, $result, $match);
    $next = $match[1];
}