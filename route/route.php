<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::group("login", function () {
    Route::rule('/locallogin', 'Index/Login.Login/index');
    Route::rule('/getRole', 'Index/Login.Login/getPosMiss');
    Route::rule('/pageRule', 'Index/Login.Login/GetPageRule');
    Route::rule('/updata', 'Index/Login.Login/AcceptRoleData');
    Route::rule('/loginout', 'Index/Login.Login/LoginOut');
    Route::rule('/editorrouter', 'Index/Login.Login/getEditor');
    Route::rule('/newRole', 'Index/Login.Login/newRole');
    Route::rule('/getgroupinfo', 'Index/Login.Login/GetgroupInfo');
    Route::rule('/getPeopleInfo', 'Index/Login.Login/getUserPeopleInfo');
    Route::rule('/UpUserEditorRole', 'Index/Login.Login/UpUserEditorRole');
    Route::rule('/addRole', 'Index/DepartmentManage.Department/addRole');
    Route::rule('/newUserPasswdRole', 'Index/Login.Login/newUserPasswdRole');
    Route::rule('/AddRoleLocalTrue', 'Index/Login.Login/AddRoleLocalTrue');
    Route::rule('/DelectRoleList', 'Index/Login.Login/DelectRoleList');
    Route::rule('/UpdataRoleTest', 'Index/Login.Login/UpdataRoleTest');
})->allowCrossDomain();
Route::group('user', function () {
    Route::rule('/info', 'Index/Login.Login/userInfo');
})->allowCrossDomain();

Route::group('OrderInfo', function () {
    Route::rule('/info', 'Index/OrderInfomation.OrderInfo/GetOrderInfo');
    Route::rule('/quertDetailInfo', 'Index/OrderInfomation.OrderInfo/GetIdPageInfo');
    Route::rule('/PageTable', 'Index/OrderInfomation.OrderInfo/GetPageTableListData');
    Route::rule('/SearchGetOrderInfo', 'Index/OrderInfomation.OrderInfo/SearchGetOrderInfo');
})->allowCrossDomain();
Route::group('role', function () {
    Route::rule('/TeamGroup', 'Index/DepartmentManage.Department/GetTeamGroupTreeList');
    Route::rule('/personRole', 'Index/DepartmentManage.Department/GetRolePeople');
    Route::rule('/GetDepartData', 'Index/DepartmentManage.Department/GetDepartData');
    Route::rule('/GetSectionChildren', 'Index/DepartmentManage.Department/GetSectionChildren');
    Route::rule('/DeleteSectionData', 'Index/DepartmentManage.Department/DeleteSectionData');
    Route::rule('/addNewPeople', 'Index/DepartmentManage.Department/addNewPeople');
    Route::rule('/updateGroupData', 'Index/DepartmentManage.Department/updateGroupData');
    Route::rule('/DeleteGroupList', 'Index/DepartmentManage.Department/DeleteGroupList');
})->allowCrossDomain();

Route::group('product', function () {
    Route::rule('/ProductClass', 'Index/Production.Recipe/productClass');
    Route::rule('/QueyProduct', 'Index/Production.Recipe/QueyProduct');
    Route::rule('/QueryRepicList', 'Index/Production.Recipe/QueryRepicList');
    Route::rule('/upRepicList', 'Index/Production.Recipe/upRepicList');
    Route::rule('/newDiageAdd', 'Index/Production.Recipe/newDiageAdd');
    Route::rule('/getRecipeDetail', 'Index/Production.Recipe/getRecipeDetail');
    Route::rule('/delateClick', 'Index/Production.Recipe/delateClick');
    Route::rule('/newProductDiageDetil', 'Index/Production.Recipe/newProductDiageDetil');
    Route::rule('/DeleteDetails', 'Index/Production.Recipe/DeleteDetails');
    Route::rule('/UpdataNewReciptDatas', 'Index/Production.Recipe/UpdataNewReciptData');
    Route::rule('/GetProductList', 'Index/Production.LiYanProductBills/GetProductList');
    Route::rule('/CompeleterSync', 'Index/Production.LiYanProductBills/CompeleterSync');
    Route::rule('/DiagSelectSwitchDataList', 'Index/Production.LiYanProductBills/DiagSelectSwitchDataList');
    Route::rule('/UpdataComplexTables', 'Index/Production.LiYanProductBills/UpdataComplexTables');
    Route::rule('/SearchIdComplexData', 'Index/Production.LiYanProductBills/SearchIdComplexData');
    Route::rule('/getFenQieListData', 'Index/Production.FenqieProduct/getFenQieListData');
    Route::rule('/FenQieListFormData', 'Index/Production.FenqieProduct/FenQieListFormData');
    Route::rule('/isCompleteSync', 'Index/Production.FenqieProduct/isCompleteSync');
    Route::rule('/LiuYanCodeStyle', 'Index/Production.LiYanProductBills/LiuYanCodeStyle');
    Route::rule('/innerVisibleClickFunc', 'Index/Production.LiYanProductBills/innerVisibleClickFunc');
})->allowCrossDomain();

Route::group('Dynamic', function () {
    Route::rule('/SellOrder', 'Index/DynamicLayout.Composing/SellOrder');
    Route::rule('/LimitPageSellOrderData', 'Index/DynamicLayout.Composing/LimitPageSellOrderData');
    Route::rule('/HangData', 'Index/DynamicLayout.Composing/HangData');
    Route::rule('/GroupTeamSelectData', 'Index/DynamicLayout.Composing/GroupTeamSelectData');
    Route::rule('/LimitPageSlitData', 'Index/DynamicLayout.Composing/LimitPageSlitData');
})->allowCrossDomain();

Route::group('TeamGroupManage', function () {
    Route::rule('/AddTeam', 'Index/TeamGroupManage.TeamGroupData/AddNewTeamGroup');
    Route::rule('/GetGroupManage', 'Index/TeamGroupManage.TeamGroupData/GetGroupManage');
})->allowCrossDomain();
return [

];
