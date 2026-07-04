# Changelog

All notable changes to `category` will be documented in this file.

## v1.0.10 - 2026-07-04

### What's Changed

- 更新通过 Livewire::addNamespace 注册组件
- 分类 page 页 slug 修改为 page-categories

## v1.0.9 - 2026-06-16

### What's Changed

- 分类组件点击分类默认使用事件，useUrl 默认为 request()->fullUrlWithoutQuery() 获取，isActive 优先根据在模型设置的 activeCategoryId 来决定
- getRecordUrl 获取当前地址优化，model 要增加 media 支持
- Fix styling

**Full Changelog**: https://github.com/Wsmallnews/category/compare/v1.0.8...v1.0.9

## v1.0.8 - 2026-06-05

* 分类适配最新 filaemnt-nestedset
* 完善 canManage 时 level 问题，更新 record 时，关联小部件自动更新问题
* 更新 readme 和 boost ai guidelines

## v1.0.7 - 2026-06-01

* Base 继承 supportBase 组件
* category 模块支持多语言
* 支持 filamentv5
* 修改 category 组件，适配 filament-nestedset v3

## v1.0.6 - 2026-05-09

* table 增加 ID 列
* 上传图片，使用formComponents

## v1.0.5 - 2026-04-03

* 优化 scopeable
* resource 支持时间区间筛选

## v1.0.4 - 2026-03-02

分类支持 url 跳转当前分类

## v1.0.3 - 2026-02-04

* 适配 filament-nestedset

## v1.0.2 - 2026-02-03

* 适配 filament-nestedset

## v1.0.1 - 2026-02-03

* 修复 FieldSet to Fieldset
* 优化 scopeable 未设置提示
* category 移除自定义模板，在调用处 (cms) 自定义

## v1.0.0 - 2025-12-15

* 分类增加无限级分类用户端组件
* resource 的 pages 增加 resource/page/scopeable traits，为了快捷获取 resource 上设置的 scopeable 信息
* 分类增加无限级分类用户端组件
* model 可自定义
* 组件使用完整插件标识，单独创建 的 categoryType 手动传入 team_id
* 优化 category 选中状态，优化获取租户方式

## 1.0.0 - 202X-XX-XX

- initial release
