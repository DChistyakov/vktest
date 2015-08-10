controllers.controller('ExecutorListController', function ($scope, $state, $rootScope, EOrdersLoader, EOrder, ConfirmDialog, $timeout, $modal, toastr, $stateParams){

   // загружаем данные
   $scope.dataIsLoading = true;
   EOrdersLoader({
      'page': $stateParams.page,
   }).then(function (response){
      $scope.dataIsLoading = false;
      $scope.data = response;
   });


   // delete items
   $scope.reserveItem = function (key, id){
      var dialogResult = ConfirmDialog('Необходимо подтверждение', 'Просто окошко, что после подтверждение Вам на счет капнет денежка.', 'Подтверждаю', 'Отмена', '', false, true);
      dialogResult.then(function (){

         EOrder.executeAction({action: 'reserveOrder', id: id}).$promise.then(function (ajax){
            if(typeof(ajax.status) == 'undefined' || ajax.status === 0){
               toastr.error(ajax.data.title, 'Ошибка');
               return true;
            }

            $scope.data.splice(key, 1);
            toastr.success('Задание успешно выполнено', 'Успешно');
            return true;
         });
      });
   }
});
