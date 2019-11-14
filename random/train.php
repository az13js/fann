<?php
// php train.php --layers=2 --struct=2,3 --record=record.csv
/*
Array
(
    [layers] => 2
    [struct] => 2,3
    [record] => record.csv
)
*/
$test_data = fann_read_train_from_file('test_data.txt');
$params = getopt('', ['layers::', 'struct::', 'record::']);
$network = fann_create_standard_array($params['layers'], explode(',', $params['struct']));
fann_set_activation_function_hidden($network, FANN_SIGMOID);
fann_set_activation_function_output($network, FANN_SIGMOID);
fann_set_training_algorithm($network, FANN_TRAIN_RPROP);
fann_set_train_error_function($network, FANN_ERRORFUNC_LINEAR);
fann_set_train_stop_function($network, FANN_STOPFUNC_MSE);
fann_set_callback($network, function($ann, $train, $max_epochs, $epochs_between_reports, $desired_error, $epochs) {
    $params = getopt('', ['layers::', 'struct::', 'record::']);
    echo sprintf('%.2f', $epochs / $max_epochs * 100) . ' %';
    echo PHP_EOL;
    echo $mse = fann_get_MSE($ann);
    echo ',';
    echo $test_mse = fann_test_data($ann, $test_data);
    echo PHP_EOL;
    file_put_contents($params['record'], "$epochs,$mse,$test_mse".PHP_EOL, FILE_APPEND);

    echo sprintf('%.2f', $epochs / $max_epochs * 100) . ' %';
    echo PHP_EOL;
    echo $mse = fann_get_MSE($ann);
    echo PHP_EOL;
    file_put_contents($params['record'], "$epochs,$mse".PHP_EOL, FILE_APPEND);
    return true;
});
fann_randomize_weights($network, -0.1, 0.1);
fann_train_on_file($network, 'train_data.txt', 200, 5, 0);
fann_save($network, 'network.dat');

// 销毁保存在内存的数据
fann_destroy($network);
fann_destroy_train($test_data);
