var controllers = angular.module('VkTestApp.controllers', []);

/* Setup App Main Controller */
controllers.controller('AppController', ['$scope', '$rootScope', function ($scope, $rootScope){
   $scope.$on('$viewContentLoaded', function (){
      Metronic.initComponents(); // init core components
   });
}]);


/* Setup Layout Part - Header */
controllers.controller('HeaderController', function ($rootScope, $scope, CommonInfo){
   CommonInfo.startTimer();

   $scope.$on(CommonInfo.EVENT_UPDATE, function (event, data){
      $rootScope.commonInfo = data;
   });

});

/* Setup Layout Part - Sidebar */
controllers.controller('SidebarController', ['$scope', function ($scope){
   $scope.$on('$includeContentLoaded', function (){

   });
}]);

/* Setup Layout Part - Sidebar */
controllers.controller('PageHeadController', ['$scope', function ($scope){
   $scope.$on('$includeContentLoaded', function (){

   });
}]);


controllers.controller('FooterController', function ($rootScope, $scope){
   $scope.$on('$includeContentLoaded', function (){

   });
});