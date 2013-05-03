<?php
/*
  $Id: english.php,v 1.101 2002/11/11 13:30:16 project3000 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat6.0 I used 'en_US'
// on FreeBSD 4.0 I use 'en_US.ISO_8859-1'
// this may not work under win32 environments..
define('DATE_FORMAT_SHORT', '%Y/%m/%d');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%Y/%m/%d'); // this is used for strftime()
define('DATE_FORMAT', 'Y/m/d'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%m:%s');

////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 0, 2) . substr($date, 3, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
  }
}

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="zh"');

// charset for web pages and emails
define('CHARSET', 'gb2312');

// page title
define('TITLE', STORE_NAME);

// header text in includes/header.php
define('HEADER_TITLE_TOP', '系统管理');
define('HEADER_TITLE_SUPPORT_SITE', '支持网站');
define('HEADER_TITLE_ONLINE_CATALOG', '商品目录');
define('HEADER_TITLE_ADMINISTRATION', '系统管理');

// text for gender
define('MALE', '男');
define('FEMALE', '女');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

// configuration box text in includes/boxes/configuration.php
define('BOX_HEADING_CONFIGURATION', '系统设置');
define('BOX_CONFIGURATION_MYSTORE', '我的商店');
define('BOX_CONFIGURATION_LOGGING', '系统记录');
define('BOX_CONFIGURATION_CACHE', '系统缓存区');

// modules box text in includes/boxes/modules.php
define('BOX_HEADING_MODULES', '外挂模块');
define('BOX_MODULES_PAYMENT', '付款方式');
define('BOX_MODULES_SHIPPING', '送货方式');
define('BOX_MODULES_ORDER_TOTAL', '订单总计');

// categories box text in includes/boxes/catalog.php
define('BOX_HEADING_CATALOG', '商品目录');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', '分类/商品');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', '商品属性');
define('BOX_CATALOG_MANUFACTURERS', '制造商');
define('BOX_CATALOG_REVIEWS', '商品评论');
define('BOX_CATALOG_SPECIALS', '特价商品');
define('BOX_CATALOG_PRODUCTS_EXPECTED', '即将到货商品');

// customers box text in includes/boxes/customers.php
define('BOX_HEADING_CUSTOMERS', '顾客');
define('BOX_CUSTOMERS_CUSTOMERS', '顾客');
define('BOX_CUSTOMERS_ORDERS', '订单');

// taxes box text in includes/boxes/taxes.php
define('BOX_HEADING_LOCATION_AND_TAXES', '地区 / 税率');
define('BOX_TAXES_COUNTRIES', '国家');
define('BOX_TAXES_ZONES', '地区');
define('BOX_TAXES_GEO_ZONES', '税区');
define('BOX_TAXES_TAX_CLASSES', '税别');
define('BOX_TAXES_TAX_RATES', '税率');

// reports box text in includes/boxes/reports.php
define('BOX_HEADING_REPORTS', '报表');
define('BOX_REPORTS_PRODUCTS_VIEWED', '商品浏览情况');
define('BOX_REPORTS_PRODUCTS_PURCHASED', '商品购买情况');
define('BOX_REPORTS_ORDERS_TOTAL', '订单统计');

// tools text in includes/boxes/tools.php
define('BOX_HEADING_TOOLS', '系统工具');
define('BOX_TOOLS_BACKUP', '数据库备份');
define('BOX_TOOLS_BANNER_MANAGER', '广告管理器');
define('BOX_TOOLS_CACHE', '系统缓存区控制');
define('BOX_TOOLS_DEFINE_LANGUAGE', '定义语言');
define('BOX_TOOLS_FILE_MANAGER', '文件管理器');
define('BOX_TOOLS_MAIL', '发送邮件');
define('BOX_TOOLS_NEWSLETTER_MANAGER', '电子新闻管理器');
define('BOX_TOOLS_SERVER_INFO', '服务器资料');
define('BOX_TOOLS_WHOS_ONLINE', '在线用户');

// localizaion box text in includes/boxes/localization.php
define('BOX_HEADING_LOCALIZATION', '本土化设置');
define('BOX_LOCALIZATION_CURRENCIES', '货币');
define('BOX_LOCALIZATION_LANGUAGES', '语言');
define('BOX_LOCALIZATION_ORDERS_STATUS', '订单状态');

// javascript messages
define('JS_ERROR', '您的表格在处理中发现错误！\n请修改：\n\n');

define('JS_OPTIONS_VALUE_PRICE', '* 商品属性应给出价格\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* 商品属性应给出价格前缀符\n');

define('JS_PRODUCTS_NAME', '* 新商品需要填写名字\n');
define('JS_PRODUCTS_DESCRIPTION', '* 新商品需要填写商品说明\n');
define('JS_PRODUCTS_PRICE', '* 新商品需要填写价格\n');
define('JS_PRODUCTS_WEIGHT', '* 新商品需要填写重量\n');
define('JS_PRODUCTS_QUANTITY', '* 新商品需要填写数量\n');
define('JS_PRODUCTS_MODEL', '* 新商品需要填写商品型号\n');
define('JS_PRODUCTS_IMAGE', '* 新商品需要商品图片\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* 请提供该商品的新价格\n');

define('JS_GENDER', '* \'性别\' 必须选.\n');
define('JS_FIRST_NAME', '* \'名\' 至少为 ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' 个字。\n');
define('JS_LAST_NAME', '* The \'姓\' 至少为 ' . ENTRY_LAST_NAME_MIN_LENGTH . '个字。\n');
define('JS_DOB', '* \'出生日期\' 格式为: xx/xx/xxxx (月/日/年)。\n');
define('JS_EMAIL_ADDRESS', '* \'电子邮件\' 至少为 ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' 个字长。\n');
define('JS_ADDRESS', '* \'街道门牌号码\' 至少为 ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' 个字。\n');
define('JS_POST_CODE', '* \'邮政编码\' 应为 ' . ENTRY_POSTCODE_MIN_LENGTH . ' 位数。\n');
define('JS_CITY', '* \'市（县）\' 至少为 ' . ENTRY_CITY_MIN_LENGTH . ' 个字。\n');
define('JS_STATE', '* \'省份\' 必须选。\n');
define('JS_STATE_SELECT', '-- 选以上各项 --');
define('JS_ZONE', '* \'省份\' 应从该国所属省份中选出。');
define('JS_COUNTRY', '* \'国家\' 必须选。\n');
define('JS_TELEPHONE', '* \'电话\'号码至少为 ' . ENTRY_TELEPHONE_MIN_LENGTH . ' 位数。\n');
define('JS_PASSWORD', '* \'密码\'和 \'密码确认\'栏必须相同，并至少为 ' . ENTRY_PASSWORD_MIN_LENGTH . ' 个字。\n');

define('JS_ORDER_DOES_NOT_EXIST', '订单号 %s 不存在！');

define('CATEGORY_PERSONAL', '个人');
define('CATEGORY_ADDRESS', '地址');
define('CATEGORY_CONTACT', '联系方式');
define('CATEGORY_COMPANY', '公司');
/*
define('CATEGORY_PASSWORD', '密码');
*/
define('CATEGORY_OPTIONS', '选项');
define('ENTRY_GENDER', '性别：');
define('ENTRY_GENDER_ERROR', '&nbsp;<span class="errorText">必须填</span>');
define('ENTRY_FIRST_NAME', '名：');
define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<span class="errorText">最少 ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' 个字</span>');
define('ENTRY_LAST_NAME', '姓：');
define('ENTRY_LAST_NAME_ERROR', '&nbsp;<span class="errorText">最少 ' . ENTRY_LAST_NAME_MIN_LENGTH . ' 个字</span>');
define('ENTRY_DATE_OF_BIRTH', '出生日期：');
define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<span class="errorText">(例如： 05/21/1970)</span>');
define('ENTRY_EMAIL_ADDRESS', '电子邮件：');
define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<span class="errorText">最少 ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' 个字</span>');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<span class="errorText">这个电子邮件地址无效！</span>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<span class="errorText">这个电子邮件地址已存在！</span>');
define('ENTRY_COMPANY', '公司名称：');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_STREET_ADDRESS', '街道门牌号：');
define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<span class="errorText">最少 ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' 个字</span>');
define('ENTRY_SUBURB', '县（区）：');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_POST_CODE', '邮政编码：');
define('ENTRY_POST_CODE_ERROR', '&nbsp;<span class="errorText">最少 ' . ENTRY_POSTCODE_MIN_LENGTH . ' 位</span>');
define('ENTRY_CITY', '市（县）：');
define('ENTRY_CITY_ERROR', '&nbsp;<span class="errorText">最少 ' . ENTRY_CITY_MIN_LENGTH . ' </span>');
define('ENTRY_STATE', '省份：');
define('ENTRY_STATE_ERROR', '&nbsp;<span class="errorText">必须填</span>');
define('ENTRY_COUNTRY', '国家：');
define('ENTRY_COUNTRY_ERROR', '');
define('ENTRY_TELEPHONE_NUMBER', '电话：');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<span class="errorText">最少 ' . ENTRY_TELEPHONE_MIN_LENGTH . ' 位</span>');
define('ENTRY_FAX_NUMBER', '传真：');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_NEWSLETTER', '电子新闻：');
define('ENTRY_NEWSLETTER_YES', '订阅');
define('ENTRY_NEWSLETTER_NO', '不订阅');
define('ENTRY_NEWSLETTER_ERROR', '');
/*
define('ENTRY_PASSWORD', '密码：');
define('ENTRY_PASSWORD_CONFIRMATION', '密码确认：');
define('PASSWORD_HIDDEN', '--隐藏--');
*/
// images
define('IMAGE_ANI_SEND_EMAIL', '正在发送电子邮件');
define('IMAGE_BACK', '返回');
define('IMAGE_BACKUP', '备份');
define('IMAGE_CANCEL', '取消');
define('IMAGE_CONFIRM', '确认');
define('IMAGE_COPY', '复制');
define('IMAGE_COPY_TO', '复制到');
define('IMAGE_DETAILS', '详情');
define('IMAGE_DELETE', '删除');
define('IMAGE_EDIT', '修改');
define('IMAGE_EMAIL', '电子邮件');
define('IMAGE_FILE_MANAGER', '文件管理器');
define('IMAGE_ICON_STATUS_GREEN', '已激活');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', '点击激活');
define('IMAGE_ICON_STATUS_RED', '未激活');
define('IMAGE_ICON_STATUS_RED_LIGHT', '点击关闭');
define('IMAGE_ICON_INFO', '资料');
define('IMAGE_INSERT', '插入');
define('IMAGE_LOCK', '锁住');
define('IMAGE_MODULE_INSTALL', '安装模块');
define('IMAGE_MODULE_REMOVE', '删除模块');

define('IMAGE_MOVE', '移动');
define('IMAGE_NEW_BANNER', '新广告');
define('IMAGE_NEW_CATEGORY', '新分类');
define('IMAGE_NEW_COUNTRY', '新国家');
define('IMAGE_NEW_CURRENCY', '新货币');
define('IMAGE_NEW_FILE', '新文件');
define('IMAGE_NEW_FOLDER', '新文件夹');
define('IMAGE_NEW_LANGUAGE', '新语言');
define('IMAGE_NEW_NEWSLETTER', '新电子新闻');
define('IMAGE_NEW_PRODUCT', '新商品');
define('IMAGE_NEW_TAX_CLASS', '新税别');
define('IMAGE_NEW_TAX_RATE', '新税率');
define('IMAGE_NEW_TAX_ZONE', '新税区');
define('IMAGE_NEW_ZONE', '新地区');
define('IMAGE_ORDERS', '订单');
define('IMAGE_ORDERS_INVOICE', '发票');
define('IMAGE_ORDERS_PACKINGSLIP', '装箱单');
define('IMAGE_PREVIEW', '预览');
define('IMAGE_RESTORE', '恢复');
define('IMAGE_RESET', '复原');
define('IMAGE_SAVE', '保存');
define('IMAGE_SEARCH', '搜寻');
define('IMAGE_SELECT', '选择');
define('IMAGE_SEND', '发送');
define('IMAGE_SEND_EMAIL', '发电子邮件');
define('IMAGE_UNLOCK', '开锁');
define('IMAGE_UPDATE', '更新');
define('IMAGE_UPDATE_CURRENCIES', '更新汇率');
define('IMAGE_UPLOAD', '上载');

define('ICON_CROSS', '否');
define('ICON_CURRENT_FOLDER', '当前文件夹');
define('ICON_DELETE', '删除');
define('ICON_ERROR', '错误');
define('ICON_FILE', '文件');
define('ICON_FILE_DOWNLOAD', '下载');
define('ICON_FOLDER', '文件夹');
define('ICON_LOCKED', '已锁住');
define('ICON_PREVIOUS_LEVEL', '前面一层');
define('ICON_PREVIEW', '预览');
define('ICON_STATISTICS', '统计资料');
define('ICON_SUCCESS', '完成');
define('ICON_TICK', '是');
define('ICON_UNLOCKED', '锁已打开');
define('ICON_WARNING', '警告');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', '第 %s 页 共 %d 页');
define('TEXT_DISPLAY_NUMBER_OF_BANNERS', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 个广告)');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 个国家)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 个顾客)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 种货币)'); 
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 种语言)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 个制造商)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 封电子新闻)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 个订单)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 种订单状态)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 种商品)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 即将到货商品)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 条商品评论)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 种特价商品)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 种税别)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 个税区)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 种税率)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', '显示 <b>%d</b> 至 <b>%d</b> (共 <b>%d</b> 个地区)');

define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

define('TEXT_DEFAULT', '预设值');
define('TEXT_SET_DEFAULT', '设为预设值');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* 必须填</span>');

define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', '错误: 无预设货币. 请去: 系统管理->本土化->货币');

define('TEXT_CACHE_CATEGORIES', '商品分类栏');
define('TEXT_CACHE_MANUFACTURERS', '制造商栏');
define('TEXT_CACHE_ALSO_PURCHASED', '另购模块');

define('TEXT_NONE', '--无--');
define('TEXT_TOP', '顶端');
define('ERROR_DESTINATION_DOES_NOT_EXIST', '错误：上载目录不存在。');
define('ERROR_DESTINATION_NOT_WRITEABLE', '错误：上载目录不可写。');
define('ERROR_FILE_NOT_SAVED', '错误：上载文件未存盘。');
define('ERROR_FILETYPE_NOT_ALLOWED', '错误：上载文件类型被拒绝。');
define('SUCCESS_FILE_SAVED_SUCCESSFULLY', '成功：上载文件已存盘。');
define('WARNING_NO_FILE_UPLOADED', '警告：无文件被上载。');
define('WARNING_FILE_UPLOADS_DISABLED', '警告：在 php.ini 设置文件中，文件上载功能已被解除。');

?>