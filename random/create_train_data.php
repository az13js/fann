<?php
/**
 * 判断数字是不是质数
 *
 * @param int $number
 * @return bool
 */
function primeNumber(int $number): bool
{
    if ($number < 2) {
        return false;
    }
    if (2 == $number || 3 == $number || 5 == $number) {
        return true;
    }
    $finish = sqrt($number);
    if (intval(ceil($finish)) == intval(floor($finish))) {
        return false;
    }
    $finish = intval(ceil($finish));
    for ($i = 2; $i < $finish; ++$i) {
        if (0 == $number % $i) {
            return false;
        }
    }
    return true;
}

$data = fann_create_train_from_callback(100000, 32, 1, function($num, $num_input, $num_output) {
    static $index = 0;
    ++$index;
    return ['input' => str_split(sprintf('%32d', decbin($index))), 'output' => [intval(primeNumber($index))]];
});

// 打乱数据
fann_shuffle_train_data($data);

// 获取前80000个数据作为训练集
$train_data = fann_subset_train_data($data, 0, 80000);
fann_save_train($train_data, 'train_data.txt');

// 获取后20000个数据作为测试集
$test_data = fann_subset_train_data($data, 80000, 20000);
fann_save_train($test_data, 'test_data.txt');

// 销毁保存在内存的数据集
fann_destroy_train($train_data);
fann_destroy_train($test_data);
fann_destroy_train($data);
