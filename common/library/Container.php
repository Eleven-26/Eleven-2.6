<?php
/**
 * 容器模式
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-5-29
 * Time: 16:01
 */

namespace lib;

class Container
{

    /**
     * 容器绑定，用来装提供的实例或者 提供实例的回调函数
     * @var array
     */
    public $building = [];

    public function __construct()
    {
    }

    /**
     * Note：注册一个绑定到容器
     * User：Eleven
     * Date：2019-5-29 16:06
     * @param $abstract 接口的名字
     * @param null $concrete    实现类的名字，如果实现类的名字未指定，则默认为和接口类是同一类名
     * @param bool $shared  是否开启单例模式
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        if(is_null($concrete)){
            $concrete = $abstract;
        }

        // 如果实现传入的不是一个闭包，则将其转为闭包形式
        if(!$concrete instanceOf \Closure){
            $concrete = $this->getClosure($abstract, $concrete);
        }

        $this->building[$abstract] =  compact("concrete", "shared");
    }

    /**
     * Note：注册一个共享的绑定 单例
     * User：Eleven
     * Date：2019-5-29 16:07
     * @param $abstract
     * @param $concrete
     * @param bool $shared
     */
    public function singleton($abstract, $concrete, $shared = true){
        $this->bind($abstract, $concrete, $shared);
    }

    /**
     * Note：默认生成实例的回调闭包
     * User：Eleven
     * Date：2019-5-29 16:07
     * @param $abstract
     * @param $concrete
     * @return \Closure
     */
    public function getClosure($abstract, $concrete)
    {
        // 此处的参数$container定义与调用的地方（build方法）
        // 如果传入的接口和实现是一样的，说明还没被注册过，因为注册过之后，实现应该是闭包才对。否则说明传入的实现是一个闭包，可以直接调用
        return function($container) use($abstract, $concrete){
            $method = ($abstract == $concrete)? 'build' : 'make';
            return $container->$method($concrete);
        };
    }

    /**
     * Note：生成实例，通过接口获取实现，如果可以进行生成实例，就生成，否则针对实现进行生成
     * User：Eleven
     * Date：2019-5-29 16:07
     * @param $abstract
     * @return mixed
     * @throws \Exception
     */
    public function make($abstract)
    {
//        Eleven($this->building);
        $concrete = $this->getConcrete($abstract);
        if($this->isBuildable($concrete, $abstract)){
            $object = $this->build($concrete);
        }else{
            $object = $this->make($concrete);
        }

        return $object;
    }

    /**
     * Note：获取绑定的回调函数
     * User：Eleven
     * Date：2019-5-29 16:08
     * @param $abstract
     * @return mixed
     */
    public function getConcrete($abstract)
    {
        // 如果该依赖没有在容器中注册过，就返回接口名称，如果注册过，则返回服务的闭包
        if(!isset($this->building[$abstract])){
            return $abstract;
        }

        return $this->building[$abstract]['concrete'];
    }

    /**
     * Note：判断 是否 可以创建服务实体
     * User：Eleven
     * Date：2019-5-29 16:08
     * @param $concrete
     * @param $abstract
     * @return bool
     */
    public function isBuildable($concrete, $abstract)
    {
        return $concrete === $abstract || $concrete instanceof \Closure;
    }

    /**
     * Note：根据实例具体名称实例具体对象
     * 通过实例名称进行反射，获得构造函数，以及构造函数中的参数，进行实例化
     * User：Eleven
     * Date：2019-5-29 16:08
     * @param $concrete
     * @return mixed
     * @throws \Exception
     */
    public function build($concrete)
    {

        // 如果传入的实例是必要的话，可以直接执行了
        if($concrete instanceof \Closure){
            return $concrete($this);
        }

        //创建反射对象，路径：$path = DS . 'App' . DS . $urls['module'] . DS . 'mobile' . DS . $urls['controller'];
//        $path = DS . 'App' . DS . 'admin' . DS . 'controller' . DS . $concrete;
//        $path = 'App' . DS . 'admin' . DS . 'controller' . DS . $concrete;

//        var_dump(preg_match("/\\\App.*/i",$path,$matches));echo "<hr>";
//        Eleven($path);
//        var_dump($concrete);echo "<hr>";
        //此处是能实例命名空间在：\App\admin\controller\*
//        if (!preg_match("/App.*/i",$concrete)){
//            $concrete = 'App' . DS . 'admin' . DS . 'controller' . DS . $concrete;
//        }

//        var_dump($concrete);echo "<hr>";

        $reflector = new \ReflectionClass($concrete);

        // 如果不能实例化就抛出异常
        if(!$reflector->isInstantiable()){
            //抛出异常
            throw new \Exception('无法实例化');
        }

        // 获得构造函数，以确定该如何实例化，如果没有构造函数，则可以直接无参实例化
        $constructor = $reflector->getConstructor();
        if(is_null($constructor)){
            return new $concrete;
        }

        // 如果有构造函数，则需要获得所有的入参，此入参即为依赖
        $dependencies = $constructor->getParameters();

        // 根据入参或者依赖的对象
        $instance = $this->getDependencies($dependencies);
//        var_dump($instance);echo "<hr>";
        return $reflector->newInstanceArgs($instance);
    }

    /**
     * Note：通过反射解决参数依赖
     * User：Eleven
     * Date：2019-5-29 16:09
     * @param array $dependencies
     * @return array
     * @throws \Exception
     */
    public function getDependencies(array $dependencies)
    {
        $results = [];
        foreach( $dependencies as $dependency ){
//            var_dump($dependency->getClass());echo "<hr>";
            $results[] = is_null($dependency->getClass())
                ? $this->resolvedNonClass($dependency)
                : $this->resolvedClass($dependency);
        }

        return $results;
    }

    /**
     * Note：解决一个没有类型提示依赖
     * User：Eleven
     * Date：2019-5-29 16:09
     * @param \ReflectionParameter $parameter
     * @return mixed
     * @throws \Exception
     */
    public function resolvedNonClass(\ReflectionParameter $parameter)
    {
        if($parameter->isDefaultValueAvailable()){
            return $parameter->getDefaultValue();
        }
        throw new \Exception('出错');
    }

    /**
     * Note：通过容器解决依赖
     * User：Eleven
     * Date：2019-5-29 16:09
     * @param \ReflectionParameter $parameter
     * @return mixed
     * @throws \Exception
     */
    public function resolvedClass(\ReflectionParameter $parameter)
    {
        return $this->make($parameter->getClass()->name);
    }
}