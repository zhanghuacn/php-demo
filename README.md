## 目录结构
```
+---app
|   |   helpers.php // 函数
|   +---controller
|   |       UserController.php // 示例控制器
|   +---exceptions
|   +---middleware
|   \---models
|           User.php // 示例模型
+---config
|       database.php // 数据库配置
|       log.php // 日志配置
|       view.php // 视图配置
+---core
|   |   Config.php  // 配置
|   |   Controller.php      // 基础控制器
|   |   Response.php    // 响应
|   |   RouteCollection.php // 路由
|   |   database.php // 数据库ORM
|   +---log
|   |   |   Logger.php // 日志管理
|   |   \---driver // 不同类型日志实现
|   |           DailyLogger.php
|   |           StackLogger.php
|   +---request
|   |       PhpRequest.php  // 请求
|   |       RequestInterface.php 
|   \---view
|           Thinkphp.php    // tp模板引擎
|           View.php    // 视图适配器
|           ViewInterface.php 
+---public
|       index.php // 单一入口文件
+---routes
|       api.php 
|       web.php 
+---storage
+---phpunit.xml // phpunit的配置
+---app.php     // 框架要经过这个加载
```
