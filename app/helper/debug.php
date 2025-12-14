<?php

function dd($data, $exit = true)
{
    echo '<div style="
        background:#1e1e1e;
        color:#fff;
        padding:15px;
        border-radius:10px;
        margin:20px 0;
        font-family: Consolas, monospace;
        font-size:14px;
        line-height:1.4;
        box-shadow:0 0 10px rgba(0,0,0,0.3);
        ">
        <pre style="white-space:pre-wrap;">';
    
    var_dump($data);

    echo '</pre>
    </div>';

    if ($exit) {
        exit;
    }
}
