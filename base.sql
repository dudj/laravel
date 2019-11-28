/*
Navicat MySQL Data Transfer

Source Server         : xampp
Source Server Version : 50505
Source Host           : 127.0.0.1:3306
Source Database       : ldemo

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-11-28 15:47:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for access
-- ----------------------------
DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL COMMENT '名称',
  `eng_name` varchar(512) DEFAULT NULL COMMENT '英文名称',
  `controller` varchar(128) NOT NULL COMMENT '控制器名称',
  `method` varchar(128) DEFAULT NULL COMMENT '方法',
  `parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '父类id',
  `order_by` int(10) DEFAULT '0' COMMENT '排队顺序',
  `type` tinyint(2) DEFAULT '1' COMMENT '是否在左侧菜单显示 1 显示 0 不显示',
  `icon` varchar(128) DEFAULT NULL COMMENT '图标',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE,
  UNIQUE KEY `cm` (`controller`,`method`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='权限表';

-- ----------------------------
-- Records of access
-- ----------------------------
INSERT INTO `access` VALUES ('1', '系统管理', 'system manager', 'system', null, '0', '1', '1', '&#xe6ae;');
INSERT INTO `access` VALUES ('2', '管理员管理', 'Member', 'system', 'member', '1', '1', '1', '&#xe726;');
INSERT INTO `access` VALUES ('3', '角色管理', 'role', 'system', 'group', '1', '2', '1', '&#xe6a7;');
INSERT INTO `access` VALUES ('4', '权限管理', 'access', 'system', 'access', '1', '3', '1', '&#xe6a7;');
INSERT INTO `access` VALUES ('6', '订单管理', null, 'order', 'list', '0', '2', '1', '&#xe723;');
INSERT INTO `access` VALUES ('14', '管理员增加', 'member add', 'member', 'add', '2', '1', '0', '&#xe697;');
INSERT INTO `access` VALUES ('15', '管理员修改', 'member edit', 'member', 'edit', '2', '2', '0', '&#xe697;');
INSERT INTO `access` VALUES ('16', '管理员删除', 'member del', 'member', 'del', '2', '3', '0', '&#xe697;');
INSERT INTO `access` VALUES ('17', '权限添加', 'node add', 'access', 'add', '4', '1', '0', '&#xe697;');
INSERT INTO `access` VALUES ('18', '权限修改', 'node edit', 'access', 'edit', '4', '2', '0', '&#xe697;');
INSERT INTO `access` VALUES ('19', '权限删除', 'node del', 'access', 'del', '4', '3', '0', '&#xe697;');
INSERT INTO `access` VALUES ('20', '角色添加', 'role add', 'role', 'add', '3', '1', '0', '&#xe697;');
INSERT INTO `access` VALUES ('21', '角色修改', 'role edit', 'role', 'edit', '3', '2', '0', '&#xe697;');
INSERT INTO `access` VALUES ('22', '角色删除', 'role del', 'role', 'del', '3', '3', '0', '&#xe697;');

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `updatetime` int(10) DEFAULT NULL,
  `group_id` int(10) NOT NULL COMMENT '角色id',
  `status` enum('1','0') COLLATE utf8_unicode_ci DEFAULT '1' COMMENT '1 正常 0 禁止',
  `logo` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '头像',
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '地址',
  `mobile` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号',
  `mailbox` varchar(256) COLLATE utf8_unicode_ci NOT NULL COMMENT '邮箱',
  `salt` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of admins
-- ----------------------------
INSERT INTO `admins` VALUES ('1', 'admin', 'b5d9b886da0e187b7abd1743741e7ac04b6499f5', 'Rg1ozJGDBz7R6pL4z86HhTJ4OfUIYYryocGWfTTGHtAOUFCYklUnWpKb3jc3', '0', '0', '1', '1', '/static/upload/default.jpg', null, '', '', '111');
INSERT INTO `admins` VALUES ('2', 'Ciara', 'b5d9b886da0e187b7abd1743741e7ac04b6499f5', 'IEIY4W9EFn', '0', '1574764668', '2', '1', '/static/upload/default.jpg', '', '16300000001', 'ciara@163.com', '111');
INSERT INTO `admins` VALUES ('4', 'test123', 'd047211d2d75ccea119ec428b537828bee02161f', null, '1574762980', '1574762980', '2', '1', '/static/upload/default.jpg', 'aaaaaa', '13020078873', '204586313@qq.com', 'Iy94836Mh');
INSERT INTO `admins` VALUES ('5', 'zhangsan', 'b5d9b886da0e187b7abd1743741e7ac04b6499f5', null, '1574762980', '1574762980', '2', '1', '/static/upload/default.jpg', 'aaa', '13022225555', '555@163.com', '111');
INSERT INTO `admins` VALUES ('6', 'lisi', 'b5d9b886da0e187b7abd1743741e7ac04b6499f5', null, '1574762980', '1574762980', '2', '1', '/static/upload/default.jpg', 'aaa', '15801010252', '666@163.com', '111');
INSERT INTO `admins` VALUES ('7', 'wangwu', 'b5d9b886da0e187b7abd1743741e7ac04b6499f5', null, null, null, '2', '1', null, null, '', '', '111');
INSERT INTO `admins` VALUES ('8', 'koreyoshi', 'b5d9b886da0e187b7abd1743741e7ac04b6499f5', null, null, null, '2', '1', null, null, '', '', '111');
INSERT INTO `admins` VALUES ('9', 'phil', 'b5d9b886da0e187b7abd1743741e7ac04b6499f5', null, null, null, '2', '1', null, null, '', '', '111');
INSERT INTO `admins` VALUES ('10', 'billing', 'b5d9b886da0e187b7abd1743741e7ac04b6499f5', null, null, null, '2', '1', null, null, '', '', '');

-- ----------------------------
-- Table structure for group
-- ----------------------------
DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `createtime` int(10) DEFAULT NULL,
  `updatetime` int(10) DEFAULT NULL,
  `status` tinyint(2) DEFAULT '1' COMMENT '状态 1 可用 0 禁用',
  `name` varchar(128) NOT NULL COMMENT '名称',
  `nodestr` text COMMENT '权限串(1,2,4)',
  `isall` enum('1','0') DEFAULT '1' COMMENT '是否查看所有权限',
  `description` text COMMENT '描述',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='角色表(分组表) 权限串在角色表中匹配';

-- ----------------------------
-- Records of group
-- ----------------------------
INSERT INTO `group` VALUES ('1', '1569308007', '1569308007', '1', '超级管理员', 'all', '1', null);
INSERT INTO `group` VALUES ('2', '1569308007', '1574652878', '1', '普通管理员', '2', '0', '普通gianuan啊');
INSERT INTO `group` VALUES ('20', '1573698880', '1573803603', '1', 'test', 'all', '1', 'test管理员');
INSERT INTO `group` VALUES ('21', '1573701084', '1573701084', '1', '订单管理员', '6', '0', '对对对');
INSERT INTO `group` VALUES ('24', '1574652578', '1574652578', '1', '哎哎哎', 'all', '1', '哎哎哎');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('1', '2014_10_12_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('2', '2014_10_12_100000_create_password_resets_table', '1');
INSERT INTO `migrations` VALUES ('3', '2019_09_18_090032_create_admins_table', '1');

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
