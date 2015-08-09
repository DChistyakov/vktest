var module = angular.module('VkTestApp',
      [
         'truncate',
         'toastr',
         'ui.router',
         'ngResource',
         'ngSanitize',
         'VkTestApp.services',
         'VkTestApp.directives',
         'VkTestApp.controllers',
         'VkTestApp.resources',
         'VkTestApp.filters',
         "ui.bootstrap",
         "oc.lazyLoad",
         'ngCookies'
      ]);


module.run(function ($rootScope, $templateCache){
   $rootScope.$on('$routeChangeStart', function (event, next, current){
      if(typeof(current) !== 'undefined'){
         $templateCache.remove(current.templateUrl);
      }
   });
});

module.config(['$ocLazyLoadProvider', function ($ocLazyLoadProvider){
   $ocLazyLoadProvider.config({
      cssFilesInsertBefore: 'ng_load_plugins_before' // load the above css files before a LINK element with this ID. Dynamic CSS files must be loaded between core and theme css files
   });
}]);

/* Setup global settings */
module.factory('settings', ['$rootScope', function ($rootScope){
   // supported languages
   var settings = {
      layout: {
         pageSidebarClosed: false, // sidebar state
         pageAutoScrollOnLoad: 1000 // auto scroll to top on page load
      },
      layoutImgPath: Metronic.getAssetsPath() + 'admin/layout/img/',
      layoutCssPath: Metronic.getAssetsPath() + 'admin/layout/css/'
   };

   $rootScope.settings = settings;

   return settings;
}]);

module.config(['$httpProvider', function ($httpProvider){
   $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
   //$httpProvider.defaults.headers.common['Accept'] = 'application/json, text/javascript';
   //$httpProvider.defaults.headers.common['Content-Type'] = 'application/json; charset=utf-8';
}]);
