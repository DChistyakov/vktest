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
         .state("customer.item", {
            url: "/{id:int}",
            templateUrl: "pages/customer/item.html",
            controller: 'CustomerItemController',
            resolve: {

            }
         });
   ;

   //$urlRouterProvider.when('/', '/customer/index');
   //$urlRouterProvider.otherwise("/customer/index");
});
