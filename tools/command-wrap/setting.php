<?php

$project_path = dirname(dirname(__DIR__));
$data_barter_folder_name = 'command-data-call';
$today = @date('Y-m-d');

return [

    /**
     *  專案路徑
     *  NOTE: 如果要移動該程式的位置, 請注意路徑是否正確
     */
    'project_path' => $project_path,

    /**
     *  要交換的資料, 放置於 temp 目錄中的 folder name
     */
    'data_barter_folder_name' => 'command-data-call',

    /**
     *  取得 call folder
     */
    'call_path' => "{$project_path}/var/{$data_barter_folder_name}",

    /**
     *  取得歸檔的 folder
     */
    'complete_path' => "{$project_path}/var/{$data_barter_folder_name}/{$today}",

];
