controllers.controller('ExecutorListController', function ($scope, $state, $rootScope, OrdersLoader, ConfirmDialogNumber, $timeout, $modal, toastr, $stateParams){

         // загружаем данные
         $scope.dataIsLoading = true;
         OrdersLoader({
            'page': $stateParams.page,
         }).then(function (response){
            $scope.dataIsLoading = false;
            $scope.data = response;
         });


         // delete items
         $scope.deleteItem = function (project_key, campaign_key, group_key, type, item){
            var dialogResult = ConfirmDialogNumber();
            dialogResult.then(function (){

               if(type == 'campaign'){
                  var res = new Campaign(item);
               } else if(type == 'group'){
                  var res = new Group(item);
               } else{
                  var res = new Project(item);
               }

               return res.$delete().then(function (data){
                  if(type == 'campaign'){
                     $scope.data[project_key].campaigns.splice(campaign_key, 1);
                  } else if(type == 'group'){
                     $scope.data[project_key].campaigns[campaign_key].groups.splice(group_key, 1);
                  } else{
                     $scope.data.splice(project_key, 1);
                  }

                  return true;
               });
            });
         }
      }
);
