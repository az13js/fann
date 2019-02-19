# PHP FANN

## 创建神经网络

函数`fann_create_standard`用来创建一个神经网络。它可以具有多个输入参数。用下面
的代码来说明，第一个参数`3`表示创建的神经网络具有3层（含输入和输出层），第二个
参数`3`表示神经元输入层有3个神经元，`2`表示隐含层具有2个神经元。显然最后的`1`表
示输出层有1个神经元。

    $network = fann_create_standard(3, 3, 2, 1);

当创建神经网络成功时，`$network`将会是一个资源。创建失败`$network`将是`false`。
因此可以用下面的语句创建并判断神经网络有没有被成功创建：

    if (!$network = fann_create_standard(3, 3, 2, 1)) {
        echo 'create fail';
        die();
    }
    // 后续代码

## 释放神经网络占用的内存

调用`fann_destroy`可以释放指定的神经网络占用的资源。释放后网络和网络占用的相关
内存会被释放。

    $network = fann_create_standard(3, 3, 2, 1);
    fann_destroy($network);

## 获取一个神经网络的结构信息

当神经网络创建成功后，有时因为调试的需要，要获取神经网络的层数和神经元数量的信
息。这时可以使用`fann_get_layer_array`。这个函数将返回神经网络每一层神经元的数
量。

    $network = fann_create_standard(3, 3, 2, 1);
    $layer_array = fann_get_layer_array($network);
    var_dump($layer_array);

上面的代码执行后将输出：

    array(3)
      [0]=>
      int(3)
      [1]=>
      int(2)
      [2]=>
      int(1)
    }

## 网络类型信息

默认将是每一层连接后面的所有神经元，这时使用`fann_get_network_type`将会得到
`FANN_NETTYPE_LAYER`。而`FANN_NETTYPE_SHORTCUT`是后面的所有神经元都与前面的神经
元连接的神经网络，这种网络通过`fann_create_shortcut`创建。

    $network = fann_create_standard(3, 3, 2, 1);
    $network_type = fann_get_network_type($network);
    switch ($network_type) {
        case FANN_NETTYPE_LAYER:
            echo 'FANN_NETTYPE_LAYER';
            break;
        case FANN_NETTYPE_SHORTCUT:
            echo 'FANN_NETTYPE_SHORTCUT';
            break;
        default:
            echo 'unknow';
    }

神经网络的创建和获取内部信息的函数有不少，这里不再继续写下去了。

## 利用给定输入运行神经网络

神经网络是用来计算得到输入的。函数`fann_run`用来计算给定的输入数据。

    $network = fann_create_standard(3, 3, 2, 1);
    $result = fann_run($network, [0.5, 0.5, 0.5]);
    var_dump($result);

由于输出神经元只有1个，因此`var_dump`的输出只有1个元素。

## 设置神经网络的激活函数

    $network = fann_create_standard(3, 3, 2, 1);
    // 设置隐含层激活函数
    fann_set_activation_function_hidden($network, FANN_SIGMOID_SYMMETRIC);
    // 设置输出层激活函数
    fann_set_activation_function_output($network, FANN_SIGMOID);

## 训练算法

    fann_set_training_algorithm($network, FANN_TRAIN_RPROP);
