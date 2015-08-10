module.config(function ($stateProvider, $urlRouterProvider){

   $stateProvider
      // basic
         .state('index', {
            url: "/",
            template: "",
            controller: function ($state){
               $state.go('customer.index', {}, {location: 'replace'});
            }
         })

      // customer
         .state("customer", {
            abstract: true,
            url: "/customer",
            template: "<div ui-view></div>",
            controller: "CustomerCommonController"
         })
         .state("customer.index", {
            url: '/index',
            controller: function ($scope, $state, $stateParams){
               // дефолтные параметры
               $state.go('customer.list', {
                  'page': '1'
               }, {location: 'replace'});
            }
         })
         .state("customer.list", {
            url: "/list/{page}",
            templateUrl: "/pages/customer/index.html",
            controller: 'CustomerListController'
         })

      // executor
         .state("executor", {
            abstract: true,
            url: "/executor",
            template: "<div ui-view></div>",
            controller: "ExecutorCommonController"
         })
         .state("executor.index", {
            url: '/index',
            controller: function ($scope, $state, $stateParams){
               // дефолтные параметры
               $state.go('executor.list', {
                  'page': '1'
               }, {location: 'replace'});
            }
         })
         .state("executor.list", {
            url: "/list/{page}",
            templateUrl: "/pages/executor/index.html",
            controller: 'ExecutorListController'
         })

      // login
         .state("login", {
            url: '/login',
            templateUrl: "/pages/login/index.html",
            controller: 'LoginCommonController'
         })

      // login
         .state("logout", {
            url: '/logout',
            controller: 'LogoutCommonController',
            template: "<div ui-view></div>"
         });

   $urlRouterProvider.when('/', '/customer/index');
   $urlRouterProvider.otherwise("/customer/index");
});
