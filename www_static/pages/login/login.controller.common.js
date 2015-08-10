controllers.controller('LoginCommonController', function ($rootScope, $scope, toastr, $timeout, $state, Login){
   /* basic */

   $rootScope.state = 'login';
   $scope.loginFail = false;

   $scope.login = function(){
      if(typeof($scope.loginInput) == 'undefined' || ($scope.loginInput != 'customer' && $scope.loginInput != 'executor')){
         $scope.loginFail = true;
         toastr.error('Вы можете быть: customer или executor', 'Неверный логин');
         return true;
      }

      $scope.dataIsLoading = true;
      Login.executeAction({action: 'login', username: $scope.loginInput}).$promise.then(function (ajax){
         $scope.dataIsLoading = false;
         if(typeof(ajax.status) != 'undefined' && ajax.status === 1){
            document.location = '//dynamic.vk.dchistyakov.ru/index.php?module=login&op=login&username=' + $scope.loginInput + '&redirect_url=/';
         } else {
            toastr.error(ajax.data.title, 'Ошибка');
         }
      });
   }

   $scope.clearLoginError = function(){
      $scope.loginFail = false;
   }
});