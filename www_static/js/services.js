var services = angular.module('VkTestApp.services', []);


services.factory('ActiveDataProvider', function ($state){
   function Provider(loader, params){
      var self = this;
      self.loader = loader;
      self.dataIsLoading = false;
      self.state = params.state;
      self.data = [];
      self.currentPage = 1;
      self.currentSort = '';
      self.currentSortPrefix = '';
      self.filter = {};
      self.meta = {};

      self.setSort = function (sort){
         if(!sort){
            self.currentSort = '';
            self.currentSortPrefix = '';
            return;
         }

         if(sort.indexOf('-') === 0){
            self.currentSortPrefix = '-';
            self.currentSort = sort.replace('-', '');
            return;
         }

         if(sort == self.currentSort){
            self.currentSortPrefix = (self.currentSortPrefix == '-') ? '' : '-';
         } else{
            self.currentSort = sort;
         }
      };

      self.setSortAndReload = function (sort){
         self.setSort(sort);
         self.reloadData();
      };

      self.setPage = function (page){
         if(!page){
            page = 1;
         }
         self.currentPage = page;
      };

      self.reloadData = function (){
         var params = angular.extend({
            'page': self.currentPage,
            'sort': self.currentSortPrefix + self.currentSort
         }, self.filter);

         $state.go(self.state, params);

         var promise = loader(params);

         self.dataIsLoading = true;
         promise.then(function (response){
            self.dataIsLoading = false;
            self.data = response;
            self.meta = response._meta;
         });
         return promise;
      };

      self.getSortingClass = function (sort){
         if(sort == self.currentSort){
            if(self.currentSortPrefix == '-'){
               return 'sorting_asc';
            } else{
               return 'sorting_desc';
            }
         }
         return 'sorting';
      };

      self.setFilter = function (filter){
         self.filter = filter;
      };

      self.isSameState = function (params){
         if(params.page == self.currentPage
               && params.sort == self.currentSortPrefix + self.currentSort){
            return true;
         }
         return false;
      };
   }

   return Provider;
});

/**
 * Произвольный конфирм диалог
 var $is_error = false || 'Вы неверно ввели число!';
 var dialogResult = ConfirmDialog('Введите число', 'Сохранить', 'Отмена', 'Введите бюджет', $is_error, $hideInput);
 dialogResult.then(function() {
    console.log('good');
   }, function() {
    console.log('cancel');
   });
 */
services.factory('ConfirmDialog', function ($modal){
   return function ($title, $message, $btn_ok, $btn_cancel, $placeholder, $error_message, $hideInput, size){

      var modalInstance = $modal.open({
         templateUrl: '/tpl/modals/confirm.html',
         size: size,
         controller: function ($scope, $modalInstance){
            $scope.title = $title;
            $scope.message = $message;
            $scope.placeholder = $placeholder;
            $scope.btn_ok = $btn_ok;
            $scope.btn_cancel = $btn_cancel;

            $scope.input = '';
            $scope.hideInput = $hideInput;
            $scope.error_message = $error_message;

            $scope.ok = function (){
               $modalInstance.close($scope.input);
            };

            $scope.cancel = function (){
               $modalInstance.dismiss('cancel');
            };
         }
      });

      return modalInstance.result;
   }
});

/**
 * Произвольный диалог для получаения числа от пользователя
 var dialogResult = ConfirmDialogNumber();
 dialogResult.then(function() {
    console.log('good');
   }, function() {
    console.log('cancel');
   });
 */
services.factory('ConfirmDialogNumber', function ($modal){
   return function (size){
      var modalInstance = $modal.open({
         templateUrl: '/tpl/modals/confirm-number.html',
         size: size,
         controller: function ($scope, $modalInstance, password){
            $scope.password = password;
            $scope.userInputPassword = '';
            $scope.userPasswordError = false;

            $scope.ok = function (){
               if($scope.userInputPassword != $scope.password){
                  $scope.userPasswordError = true;
               } else{
                  $modalInstance.close($scope.userInputPassword);
               }
            };

            $scope.cancel = function (){
               $modalInstance.dismiss('cancel');
            };
         },
         resolve: {
            password: function (){
               var min = 1000;
               var max = 9999;
               return Math.floor(Math.random() * (max - min + 1)) + min;
            }
         }
      });

      return modalInstance.result;
   }
});

services.factory('CommonInfo', function ($rootScope, $q, $state, toastr, $timeout, CommonInfoLoader){
   var INFO = null;
   var timerStarted = false;
   var timer = null;
   return {
      EVENT_UPDATE: 'CommonInfo.update',

      getData: function (){
         if(!INFO){
            return this.doReload();
         }
         return $q.when(INFO);
      },
      startTimer: function (){
         var self = this;
         if(timerStarted) return;
         timerStarted = true;

         return refreshData();

         function refreshData(){
            $timeout(refreshData, 10000);
            return self.doReload();
         }
      },
      doReload: function (){
         var self = this;
         var delay = $q.defer();
         CommonInfoLoader().then(function (response){
            if(typeof response.data.code != 'undefined' && response.data.code == 1000){
               console.log($state['current'].name);

               $state.go('login', {
                  redirect_url: $state['current'].name
               }, {location: 'replace'});

               return true;
            }

            if(response.status > 0){
               // сохраняем для отдачи через getData.
               INFO = response.data;
               // отправляем событие
               $rootScope.$broadcast(self.EVENT_UPDATE, response.data);
               delay.resolve(response.data);
            } else{
               toastr.error('Ошибка загрузки данных пользователя');
               delay.reject();
            }
         });

         return delay.promise;
      }
   }
});