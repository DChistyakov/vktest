controllers.controller('CustomerListController', function ($scope, $state, $rootScope, Order, OrdersLoader, ConfirmDialogNumber, $timeout, $modal, toastr, $stateParams){

   $scope.filter = {
      page: parseInt($stateParams.page) || '1'
   };

   // загружаем данные
   $scope.dataIsLoading = true;
   OrdersLoader({
      'page': $stateParams.page,
   }).then(function (response){
      $scope.dataIsLoading = false;
      $scope.data = response;
   });


   // delete items
   $scope.deleteItem = function (key, id){
      var dialogResult = ConfirmDialogNumber();
      dialogResult.then(function (){

         Order.executeAction({action: 'deleteItem', id: id}).$promise.then(function (ajax){
            if(typeof(ajax.status) == 'undefined' || ajax.status === 0){
               toastr.error(ajax.data.title, 'Ошибка');
               return true;
            }

            $scope.data.splice(key,1);
            toastr.success('Задание успешно удалено', 'Успешно');
            return true;
         });
      });
   }


   $scope.section = 'list';
   $scope.changeSection = function (type){
      $scope.section = 'create';
   }


   $scope.formErrors = {
      title: false,
      descr: false,
      amount: false
   }
   $scope.createOrder = function (){
      var process = true;

      if(typeof($scope.orderTitle) == 'undefined' || $scope.orderTitle.length < 1){
         $scope.formErrors.title = true;
         process = false;
      }

      if(typeof($scope.orderDescr) == 'undefined' || $scope.orderDescr.length < 1){
         $scope.formErrors.descr = true;

         process = false;
      }

      if(typeof($scope.orderAmount) == 'undefined' || parseFloat($scope.orderAmount) <= 0){
         $scope.formErrors.amount = true;

         process = false;
      }


      if(process){
         Order.executeAction({action: 'createItem', title: $scope.orderTitle, descr: $scope.orderDescr, amount: $scope.orderAmount}).$promise.then(function (ajax){
            if(typeof(ajax.status) == 'undefined' || ajax.status === 0){
               toastr.error(ajax.data.title, 'Ошибка');
               return true;
            }

            toastr.success('Задание успешно создано', 'Успешно');
            return true;
         });
      }
   }


   $scope.clearLoginError = function(){
      $scope.formErrors = {
         title: false,
         descr: false,
         amount: false
      }
   }


   $scope.ChangeState = function (isPageChanged){
      var stateParams = angular.extend($scope.filter, {
         page: (isPageChanged) ? $scope.data._meta.currentPage : 1
      });
      $state.go('customer.list', stateParams, {location: 'replace'});
   }
});
