/*
Navicat MySQL Data Transfer

Source Server         : aliyun
Source Server Version : 80011
Source Host           : localhost:3306
Source Database       : doc

Target Server Type    : MYSQL
Target Server Version : 80011
File Encoding         : 65001

Date: 2018-08-07 10:03:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for doc
-- ----------------------------
DROP TABLE IF EXISTS `doc`;
CREATE TABLE `doc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `method` varchar(10) DEFAULT 'get',
  `url` varchar(255) DEFAULT NULL,
  `param` varchar(2000) DEFAULT NULL,
  `param_json` varchar(1000) DEFAULT NULL,
  `return` text,
  `return_json` text,
  `module` varchar(50) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1' COMMENT '状态-checkbox提示|0:不进行自动测试,1:正常',
  `environment` varchar(255) DEFAULT 'test',
  `project_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='文档表';

-- ----------------------------
-- Records of doc
-- ----------------------------
INSERT INTO `doc` VALUES ('2', '手机登录', 'POST', '/user/login', 'mobile 手机\r\ncode 验证码', '{\"mobile\":\"13812341234\",\"code\":\"1234\",\"device_id\":\"aa\"}', '\r\nuser_id  用户d\r\nmobile  手机号\r\nlevel 会员等级\r\nqq_open_id qq三方登录id\r\n\r\n', '{\r\n    \"code\":1,\r\n    \"msg\":\"登录成功\",\r\n    \"data\":{\r\n        \"id\":\"1\",\r\n        \"user_id\":\"1\",\r\n   \r\n        \"mobile\":\"2147483647\",\r\n        \"password\":\"$2y$10$3homLODPdrciIoZtZvHGJulcub/L2wIPIACvHVfWZD.a9ONoJqgK6\",\r\n        \"status\":\"0\",\r\n        \"level\":\"0\",\r\n        \"qq_open_id\":\"\",\r\n        \r\n        \"extra\":[\r\n            {\r\n                \r\n                    \"username\":\"aaa\",\r\n                    \"password\":\"bbb\"\r\n               \r\n            }\r\n        ],\r\n        \"create_time\":null,\r\n        \"is_new\":\"1\",\r\n       \r\n    }\r\n}\r\n', '用户', '1488249225', '1', 'test', '1');
INSERT INTO `doc` VALUES ('12', '用户信息', 'GET', '/user/show', 'id 用户id', '{\"id\":\"\"}', '', '{\r\n    \"code\":1,\r\n    \"msg\":\"登录成功\",\r\n    \"data\":{\r\n        \"id\":\"1\",\r\n        \"user_id\":\"1\",\r\n   \r\n        \"mobile\":\"2147483647\",\r\n        \"password\":\"$2y$10$3homLODPdrciIoZtZvHGJulcub/L2wIPIACvHVfWZD.a9ONoJqgK6\",\r\n        \"status\":\"0\",\r\n        \"level\":\"0\",\r\n        \"qq_open_id\":\"\",\r\n        \r\n        \"extra\":[\r\n            {\r\n                \r\n                    \"username\":\"aaa\",\r\n                    \"password\":\"bbb\"\r\n               \r\n            }\r\n        ],\r\n        \"create_time\":null,\r\n        \"is_new\":\"1\",\r\n       \r\n    }\r\n}\r\n', '用户', '1488249207', '0', 'test', '1');

-- ----------------------------
-- Table structure for project
-- ----------------------------
DROP TABLE IF EXISTS `project`;
CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标题-hidden|0111',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题|8|require-unique',
  `domain` varchar(255) DEFAULT '' COMMENT '域名|1111',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态-select-提示内容|5|require|0:禁用,1:正常,2:审核中',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间|1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of project
-- ----------------------------
INSERT INTO `project` VALUES ('1', '视频项目', 'api.test.cn', '1', '2017-06-05 10:35:33');
INSERT INTO `project` VALUES ('2', '新闻项目', 'a.cn', '1', '2017-06-05 19:16:12');
INSERT INTO `project` VALUES ('3', '内部项目', 'b.cn', '1', '2017-06-05 19:16:33');
INSERT INTO `project` VALUES ('4', '外部项目', 'c.cn', '1', '2017-06-06 12:02:00');
INSERT INTO `project` VALUES ('5', '其它项目', 'd.cn', '1', '2017-06-07 10:59:12');
INSERT INTO `project` VALUES ('6', '游戏项目', 'e.cn', '1', '2017-06-07 11:12:20');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标题-hidden|0111',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名|1111|require-unique-<</\\w{3,6}/i>>:用户名格式不正确',
  `password` varchar(255) DEFAULT '' COMMENT '密码-password|1111|require',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `birthday` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '生日-datepicker|1111',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态-select-提示内容|5|require|0:禁用,1:正常,2:审核中',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间|1',
  `flag` varchar(255) NOT NULL DEFAULT '' COMMENT '标记-select-文章属性|1111|require|0:推荐,1:置顶,2:广告',
  `intro` text COMMENT '用户介绍-editor|1100',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'a', 'a', null, null, '0', '2017-06-05 10:35:33', '', null);
INSERT INTO `user` VALUES ('2', 'aa', 'aa', null, null, '1', '2017-06-05 19:16:12', '1', null);
INSERT INTO `user` VALUES ('3', '33', '333', null, null, '2', '2017-06-05 19:16:33', '0', null);
INSERT INTO `user` VALUES ('4', 'aa2', 'bb', null, null, '0', '2017-06-06 12:02:00', '0', null);
INSERT INTO `user` VALUES ('5', '44', '55', null, null, '0', '2017-06-07 10:59:12', '', null);
INSERT INTO `user` VALUES ('6', '', '66', null, null, '0', '2017-06-07 11:12:20', '0', null);
