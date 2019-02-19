<?php
$train_data = fann_create_train_from_callback(4, 2, 1, function($num, $num_input, $num_output) {
    static $index = 0;
    $data = [
        ['input' => [0, 0], 'output' => [0]],
        ['input' => [0, 1], 'output' => [1]],
        ['input' => [1, 0], 'output' => [1]],
        ['input' => [1, 1], 'output' => [0]]
    ];
    return $data[$index++];
});
fann_save_train($train_data, 'xor.txt');
fann_shuffle_train_data($train_data);

$network = fann_create_standard(3, 2, 20000, 1);
fann_set_activation_function_hidden($network, FANN_SIGMOID_SYMMETRIC);
fann_set_activation_function_output($network, FANN_SIGMOID);
fann_set_training_algorithm($network, FANN_TRAIN_RPROP);
fann_set_train_error_function($network, FANN_ERRORFUNC_LINEAR);
fann_set_train_stop_function($network, FANN_STOPFUNC_MSE);
fann_set_callback($network, function($ann, $train, $max_epochs, $epochs_between_reports, $desired_error, $epochs) {
    echo $epochs / $max_epochs;
    echo ' ';
    echo fann_get_MSE($ann);
    echo PHP_EOL;
    return true;
});
fann_train_on_data($network, $train_data, 2000, 100, 0);
fann_save($network, 'network.dat');
